import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp, Link } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import "bootstrap/dist/css/bootstrap.css";
import bootstrap from "bootstrap/dist/js/bootstrap.bundle.js";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .component("inertia-link", Link)
            .use(plugin)
            .use(bootstrap)
            .mixin({
                methods: {
                    authUserHasRole(verifiableRole) {
                        return this.$page.props.user.roles.some(
                            (role) => role.name === verifiableRole
                        );
                    },
                    isLoggedIn() {
                        return !!this.$page.props.user;
                    },
                    imagePath(path) {
                        return window.settings.images + "/" + path;
                    },
                },
            })
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
