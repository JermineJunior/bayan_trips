export default function () {
    return {
        collapsed: (() => {
            try {
                return JSON.parse(localStorage.getItem('sidebar-collapsed') || 'false') === true;
            } catch {
                return false;
            }
        })(),

        get mobileOpen() {
            return this.$store.ui.sidebarOpen;
        },

        // Reports section accordion. Persisted so the choice survives reloads.
        reportsOpen: (() => {
            try {
                return JSON.parse(localStorage.getItem('sidebar-reports-open') || 'true') !== false;
            } catch {
                return true;
            }
        })(),

        toggleReports() {
            this.reportsOpen = !this.reportsOpen;

            try {
                localStorage.setItem('sidebar-reports-open', JSON.stringify(this.reportsOpen));
            } catch {
                // Storage unavailable — the toggle still applies for this session.
            }
        },

        toggle() {
            this.collapsed = !this.collapsed;

            // Keep the html[data-sidebar-collapsed] attribute (set pre-render to
            // avoid an FOUC) in sync so the CSS width rules agree with the Alpine
            // runtime state. Without this, toggling back from a persisted collapsed
            // state width both selectors (:collapsed class + [data-sidebar-collapsed])
            // stays stuck at 4rem.
            document.documentElement.dataset.sidebarCollapsed = this.collapsed ? '1' : '0';

            try {
                localStorage.setItem('sidebar-collapsed', JSON.stringify(this.collapsed));
            } catch {
                // Storage unavailable — the collapse still applies for this session.
            }
        },

        // Logo acts as the sidebar toggle: collapse/expand the desktop rail, and
        // dismiss the off-canvas drawer on small screens.
        toggleSidebar() {
            if (window.matchMedia('(min-width: 64rem)').matches) {
                this.toggle();
            } else {
                this.$store.ui.sidebarOpen = false;
            }
        },
    };
}