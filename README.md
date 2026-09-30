# TryoutKu: online tryout system (Laravel 12)

This folder is an **overlay**: it holds only the files that are new or replaced. Copy it over a fresh Laravel 12 project.

## Setup

1. Create the project (choose "none" for the starter kit, then MySQL or PostgreSQL):

   ```bash
   composer create-project laravel/laravel tryoutku
   ```

2. Copy this overlay into it. Overwrites `bootstrap/app.php`, `config/auth.php`, `routes/web.php`, `resources/css/app.css`, `resources/js/app.js`:

   ```bash
   cp -R path/to/this/folder/* tryoutku/
   cd tryoutku
   ```

3. Install the packages:

   ```bash
   composer require maatwebsite/excel
   npm install alpinejs chart.js lucide
   ```

4. Edit `.env`:

   ```
   APP_TIMEZONE=Asia/Jakarta
   DB_CONNECTION=mysql        # or pgsql
   DB_DATABASE=tryoutku
   DB_USERNAME=...
   DB_PASSWORD=...
   MAIL_MAILER=log            # registration mails land in storage/logs/laravel.log while developing
   ```

5. Create the tables and sample data:

   ```bash
   php artisan migrate --seed
   ```

6. Run it:

   ```bash
   composer run dev           # or: php artisan serve + npm run dev
   ```

Student area: `/`  |  Admin area: `/admin` (admin@tryoutku.test / password, **change it**).

## What is where

| Area | Files |
|---|---|
| Routes | `routes/web.php` |
| Guards (student and admin) | `config/auth.php`, `bootstrap/app.php` |
| Schema | `database/migrations/2026_10_01_00000*` (payment tables are in `..._000004`) |
| Exam engine | `Student/TryoutController.php`, `TryoutSession.php`, `resources/js/components.js` (`exam`) |
| Admin | `app/Http/Controllers/Admin/*`, `resources/views/admin/*` |
| Sample subjects and questions | `database/seeders/SubjectSeeder.php` |

## Behaviour worth knowing

- **Student ID**: `STU-<year>-<4 random characters>`. Random (not sequential) because the ID is the only login credential. Login is rate limited to 10 attempts per minute.
- **Verification email**: sent to the parent email only when one was entered (the field is optional). Verifying is not required to log in.
- **Timer**: the server stores `started_at` and computes the deadline, so refreshing the page or changing the device clock does not extend the exam. Every click is saved immediately; when time runs out the browser submits, and if the tab was closed the session is graded the next time the student, or the monitoring page, loads.
- **Answer keys** and explanations are never sent to the browser during an exam, only on the result page.
- **Live monitoring** polls every 8 seconds. For push updates later, Laravel Reverb can replace the polling.
- **Extra column**: `student_answers.is_flagged` stores the "ragu-ragu" marker so it survives a refresh.
- **UI language**: student pages are Indonesian, admin pages are English; strings are inline in the Blade files.

## Not included yet

- Admin screens to add or import questions (the seeder holds 9 sample questions). A quick option is to add Filament for the `subjects` and `questions` tables.
- Payment flow: `packages`, `transactions` and `subscriptions` exist with models, but nothing uses them yet.
