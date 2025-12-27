import './bootstrap';
import "bootstrap/dist/css/bootstrap.min.css"
import '../css/app.css';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import "bootstrap/dist/js/bootstrap.js"
import "primevue/resources/themes/bootstrap4-light-purple/theme.css"
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { fas } from '@fortawesome/free-solid-svg-icons'
library.add(fas);
import { fab } from '@fortawesome/free-brands-svg-icons';
library.add(fab);
import PrimeVue from 'primevue/config';
import Tailwind from "primevue/passthrough/tailwind";
import { dom } from "@fortawesome/fontawesome-svg-core";
dom.watch();

const appName =
    import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`,
        import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('FontAwesomeIcon', FontAwesomeIcon)
            .use(PrimeVue, { unstyled: true, pt: Tailwind })
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
