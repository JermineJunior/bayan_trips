import Alpine from 'alpinejs';
import sidebar from './components/sidebar';
import themeSwitcher from './components/theme-switcher';
import quickDates from './components/quick-dates';

window.Alpine = Alpine;

Alpine.store('ui', {
    sidebarOpen: false,
});

Alpine.data('sidebar', sidebar);
Alpine.data('themeSwitcher', themeSwitcher);
Alpine.data('quickDates', quickDates);

Alpine.start();