import './bootstrap';
import '../scss/app.scss';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createI18n } from 'vue-i18n';
import { router } from '@inertiajs/vue3';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

// Create a new Vue app instance for Inertia
createInertiaApp({
    title: (title) => {
        const appName = import.meta.env.VITE_APP_NAME;

        if (!title) {
            return appName;
        }

        return `${title} - ${appName}`;
    },
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const i18n = createI18n({
            locale: props.initialPage.props.locales.currentLocale,
            messages: props.initialPage.props.translations,
            legacy: false,
            warnHtmlMessage: false
        });

        return createApp({
            created() {
                router.on('invalid', (event) => {
                    // Do not prevent the default behavior while developing
                    if (process.env.NODE_ENV !== 'production') {
                        return;
                    }

                    // Do not prevent the handler for server errors or validation checks
                    if (event.detail.response.status === 500 || event.detail.response.status === 403) {
                        return;
                    }

                    // Prevent a white modal
                    event.preventDefault();

                    // Log the invalid response info
                    console.error('An invalid Inertia response was received.');
                    console.error(event.detail.response);
                });
            },

            mounted() {
                // Remove the props to initialize Vue
                document.getElementById('app').removeAttribute('data-page');
            },

            render: () => h(App, props)
        })
            .use(plugin)
            .use(i18n)
            .use(ZiggyVue, Ziggy)
            .use(Toast, {
                position: 'bottom-right'
            })
            .mount(el);
    },
    progress: { color: 'var(--color-primary)' }
});
