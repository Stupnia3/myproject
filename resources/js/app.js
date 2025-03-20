// resources/js/app.js
import '../css/app.css';
import './bootstrap'; // Только локальный bootstrap.js

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import AppLayout from './Layouts/AppLayout.vue';
import AOS from 'aos';
import 'aos/dist/aos.css';
import eventBus from './eventBus';

// Инициализация AOS
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    mirror: false,
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue')
        ).then((page) => {
            page.default.layout = page.default.layout || AppLayout;
            return page;
        }),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.use(plugin);
        app.config.globalProperties.$eventBus = eventBus;

        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
