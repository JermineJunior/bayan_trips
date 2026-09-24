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
    };
}