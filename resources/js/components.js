export const fmt = (s) => {
    s = Math.max(0, Math.floor(s));
    return String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0');
};

export default function register(Alpine) {
    /* Small countdown chip, e.g. on a dashboard card for an unfinished tryout. */
    Alpine.data('countdown', (seconds) => {
        const end = Date.now() + seconds * 1000;
        return {
            s: seconds,
            init() { setInterval(() => { this.s = Math.max(0, Math.round((end - Date.now()) / 1000)); }, 1000); },
            get t() { return fmt(this.s); },
        };
    });

    /* Exam engine. Every click is saved to the server; the deadline itself is decided server-side. */
    Alpine.data('exam', (cfg) => {
        let queue = Promise.resolve();               // kept outside Alpine's reactive state (Promises don't survive proxies)
        const end = Date.now() + cfg.remaining * 1000; // wall-clock based, so a throttled background tab can't drift

        const send = async (body) => {
            for (let attempt = 0; attempt < 3; attempt++) {
                try {
                    const r = await fetch(cfg.answerUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': cfg.csrf },
                        body,
                    });
                    if (r.ok || r.status === 410) return;
                } catch (e) { /* offline: retry */ }
                await new Promise((res) => setTimeout(res, 800));
            }
        };

        return {
            qs: cfg.questions,
            answers: Array.isArray(cfg.answers) ? {} : cfg.answers, // PHP serialises an empty map as []
            remaining: cfg.remaining,
            i: 0,
            confirming: false,
            submitting: false,

            init() {
                const tick = setInterval(() => {
                    this.remaining = Math.max(0, Math.round((end - Date.now()) / 1000));
                    if (this.remaining === 0) { clearInterval(tick); this.submit(); }
                }, 1000);
            },

            get q() { return this.qs[this.i]; },
            get clock() { return fmt(this.remaining); },
            get answered() { return Object.values(this.answers).filter((a) => a.o).length; },
            get flagged() { return Object.values(this.answers).filter((a) => a.f).length; },

            state(id) {
                const a = this.answers[id];
                return a && a.f ? 'flagged' : a && a.o ? 'answered' : 'empty';
            },
            go(n) {
                if (n < 0 || n >= this.qs.length) return;
                this.i = n;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },
            choose(o) {
                const id = this.q.id;
                this.answers[id] = { ...(this.answers[id] || {}), o };
                this.save(id);
            },
            flag() {
                const id = this.q.id;
                const a = this.answers[id] || {};
                this.answers[id] = { ...a, f: !a.f };
                this.save(id);
            },
            save(id) {
                const a = this.answers[id];
                const body = JSON.stringify({ question_id: id, selected_option: a.o || null, is_flagged: !!a.f });
                queue = queue.then(() => send(body));
            },
            async submit() {
                if (this.submitting) return;
                this.submitting = true;
                await queue;                 // let in-flight saves finish before grading
                this.$refs.form.submit();
            },
        };
    });

    /* Admin: registrations line chart with a daily / weekly / monthly switch. */
    Alpine.data('regChart', (url) => {
        let chart;                            // not in reactive state: Chart.js instances break inside Alpine proxies
        return {
            period: 'daily',
            init() { this.load(); },
            set(p) { this.period = p; this.load(); },
            async load() {
                const r = await fetch(`${url}?period=${this.period}`, { headers: { Accept: 'application/json' } });
                const d = await r.json();
                if (chart) chart.destroy();
                chart = new Chart(this.$refs.c, {
                    type: 'line',
                    data: {
                        labels: d.labels,
                        datasets: [{
                            label: 'New students', data: d.data, borderColor: '#8b5cf6',
                            backgroundColor: 'rgba(139,92,246,.15)', fill: true, tension: 0.35, pointRadius: 3,
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
                    },
                });
            },
        };
    });

    /* Admin: any Chart.js config built on the server. */
    Alpine.data('chartBox', (cfg) => ({
        init() {
            cfg.options = Object.assign({ maintainAspectRatio: false }, cfg.options || {});
            new Chart(this.$refs.c, cfg);
        },
    }));

    /* Admin: live monitoring table, polled from the server; countdowns tick locally between polls. */
    Alpine.data('monitor', (url) => ({
        rows: [],
        live: 0,
        fmt,
        init() {
            this.load();
            setInterval(() => this.load(), 8000);
            setInterval(() => this.rows.forEach((r) => { if (r.status === 'in_progress' && r.remaining > 0) r.remaining--; }), 1000);
        },
        async load() {
            try {
                const r = await fetch(url, { headers: { Accept: 'application/json' } });
                const d = await r.json();
                this.rows = d.rows;
                this.live = d.live;
            } catch (e) { /* keep last known data */ }
        },
    }));
}
