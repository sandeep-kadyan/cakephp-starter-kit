import '../css/app.css';
import './ajaxtable.js';

import Alpine from 'alpinejs';
import {
    createIcons,
    Menu,
    X,
    Moon,
    Sun,
    LayoutDashboard,
    LogOut,
    Gauge,
    Cpu,
    Bell,
    Filter,
    ExternalLink,
    Ellipsis,
    Check,
    MessageSquare,
    Settings,
    ChevronDown,
    ChevronUp,
    ChevronRight,
    Minus,
    Trash2,
    Download,
    History,
    Plus,
    CircleUser,
    Users,
    Activity,
    FileText,
    Terminal,
    BookOpen,
    Building2,
    Square,
    Database,
    Home,
    User,
    ShieldCheck,
    Zap,
    Code,
    CheckCircle,
    CircleX,
    TriangleAlert,
    Folder,
    Server,
    Bug,
    Shield,
    LogIn,
    Pencil,
    UserPlus,
    UserCog,
    Mail,
    Table,
    CreditCard,
    List,
    Lock,
    Monitor,
    Palette,
} from 'lucide';

const lucideIcons = {
    Menu,
    X,
    Moon,
    Sun,
    LayoutDashboard,
    LogOut,
    Gauge,
    Cpu,
    Bell,
    Filter,
    ExternalLink,
    Ellipsis,
    Check,
    MessageSquare,
    Settings,
    ChevronDown,
    ChevronUp,
    ChevronRight,
    Minus,
    Trash2,
    Download,
    History,
    Plus,
    CircleUser,
    Users,
    Activity,
    FileText,
    Terminal,
    BookOpen,
    Building2,
    Square,
    Database,
    Home,
    User,
    ShieldCheck,
    Zap,
    Code,
    CheckCircle,
    CircleX,
    TriangleAlert,
    Folder,
    Server,
    Bug,
    Shield,
    LogIn,
    Pencil,
    UserPlus,
    UserCog,
    Mail,
    Table,
    CreditCard,
    List,
    Lock,
    Monitor,
    Palette,
};

(() => {
    /**
     * Initializes form submit spinner logic.
     * @returns {void}
     */
    const formSpinner = () => {
        const forms = document.querySelectorAll('form');
        forms.forEach(function (form) {
            form.addEventListener('submit', function (e) {
                let submitBtn = form.querySelector('button[type="submit"]:not([disabled])');
                if (submitBtn) {
                    if (submitBtn.classList.contains('loading')) return;
                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                    submitBtn.dataset.originalContent = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="inline-block h-4 w-4 align-middle border-2 border-current border-t-transparent rounded-full animate-spin"></span> Please wait...';
                }
            });
        });
    };

    /**
     * Initializes password visibility toggle buttons.
     * Buttons use a `data-password-toggle` attribute pointing to the input id.
     * @returns {void}
     */
    const passwordToggle = () => {
        document.querySelectorAll('[data-password-toggle]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.dataset.passwordToggle);
                if (!input) return;
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.querySelector('svg').innerHTML = show
                    ? '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>'
                    : '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            });
        });
    };

    document.addEventListener('DOMContentLoaded', () => {
        formSpinner();
        passwordToggle();
        // Replace all [data-lucide] placeholders with inline SVGs before Alpine starts.
        createIcons({ icons: lucideIcons, inTemplates: true });
        // Allow dynamically injected content (e.g. AjaxTable rows) to re-render icons.
        window.createLucideIcons = () => createIcons({ icons: lucideIcons, inTemplates: true });
        // Initialize Alpine.js after all other JS
        Alpine.start();
    });
})();
