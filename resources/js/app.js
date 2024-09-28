import './bootstrap';
import '../css/app.css'
import '@fortawesome/fontawesome-free/css/all.css';

import MainLayout from './Layouts/MainLayout.vue';

import { ZiggyVue } from '../../vendor/tightenco/ziggy'

import { createApp, h } from 'vue'
import { createInertiaApp, Head, Link } from '@inertiajs/vue3'
import store from "./store";

import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";



createInertiaApp({
    title: (title) => `CRM ${title}`,
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })

        let page = pages[`./Pages/${name}.vue`]
        page.default.layout = page.default.layout || MainLayout
        return page
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(store)
 
            .use(Toast, {
                position: "top-right",
                timeout: 3000,
                closeOnClick: true,
                pauseOnFocusLoss: true,
                draggable: true,
            })
            .component('Head', Head)
            .component('Link', Link)
            .mount(el)
    },
})

