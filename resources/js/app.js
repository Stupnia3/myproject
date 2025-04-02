import '../css/app.css';
import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js'; // Импортируем ZiggyVue
import AppLayout from './Layouts/AppLayout.vue';
import AOS from 'aos';
import 'aos/dist/aos.css';
import eventBus from './eventBus';

AOS.init({
    duration: 500,
    easing: 'ease-in-out',
    once: true,
    mirror: false,
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Обновляем CSRF-токен после каждого успешного запроса
router.on('success', (event) => {
    const newCsrfToken = event.detail.page.props.csrf_token || document.querySelector('meta[name="csrf-token"]')?.content;
    if (newCsrfToken) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = newCsrfToken;
        window.csrf_token = newCsrfToken;
    }
});

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
        app.use(ZiggyVue); // Подключаем ZiggyVue
        app.config.globalProperties.$eventBus = eventBus;
        app.config.globalProperties.$route = route; // Делаем route() доступным глобально

        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
