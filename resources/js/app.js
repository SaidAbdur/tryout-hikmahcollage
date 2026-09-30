import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import {
    createIcons, Activity, BookOpen, CircleCheck, CircleMinus, CircleX, Clock, Download, Eye, Flag,
    GraduationCap, History, LayoutDashboard, Lightbulb, LogIn, LogOut, Pencil, Play, Rocket,
    RotateCcw, School, Search, Send, Sparkles, Star, Timer, Trash2, TrendingUp, Trophy, UserPlus, Users,
    ChevronLeft, ChevronRight,
} from 'lucide';
import registerComponents from './components';

window.Alpine = Alpine;
window.Chart = Chart;
Chart.defaults.font.family = "'Nunito', sans-serif";
Chart.defaults.font.weight = 600;

registerComponents(Alpine);
Alpine.start();

createIcons({
    icons: {
        Activity, BookOpen, CircleCheck, CircleMinus, CircleX, Clock, Download, Eye, Flag,
        GraduationCap, History, LayoutDashboard, Lightbulb, LogIn, LogOut, Pencil, Play, Rocket,
        RotateCcw, School, Search, Send, Sparkles, Star, Timer, Trash2, TrendingUp, Trophy, UserPlus, Users,
        ChevronLeft, ChevronRight,
    },
});
