window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import { createApp, h } from 'vue'
import { createInertiaApp, Link } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import Layout from './Components/Layout'

/* Font Awesome */
import { library } from "@fortawesome/fontawesome-svg-core";
import { faBars, faTimes } from "@fortawesome/pro-solid-svg-icons";
import { faCopyright, faWindowClose } from "@fortawesome/pro-light-svg-icons";
import { faGithub, faTwitch } from "@fortawesome/free-brands-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(faBars, faTimes, faCopyright, faGithub, faTwitch, faWindowClose);

createInertiaApp({
    resolve: async name => {
        let page = (await import(`./Pages/${name}`)).default;

        if (page.layout === undefined) {
            page.layout = Layout;
        }

        return page;
    },
    // resolve: name => {
    //     let page = require(`./Pages/${name}`).default;
    //
    //     if (page.layout === undefined) {
    //         page.layout = Layout;
    //     }
    //
    //     return page;
    // },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component("Link", Link)
            .component("font-awesome-icon", FontAwesomeIcon)
            .mount(el)
    },
})

InertiaProgress.init({
    delay: 300,
    color: '#FF00FF',
    includeCSS: true,
    showSpinner: true,
})
