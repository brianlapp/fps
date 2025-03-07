import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import {VueReCaptcha} from "vue-recaptcha-v3";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
import { initFlowbite } from 'flowbite';
import Vue3TouchEvents from "vue3-touch-events";
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v21.0&appId=363718286984198";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(VueReCaptcha, { siteKey: import.meta.env.VITE_GOOGLE_RECAPTCHA_KEY })
            .use(Vue3TouchEvents)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
}).then(() => {
    // on first load
    initFlowbite();
});
