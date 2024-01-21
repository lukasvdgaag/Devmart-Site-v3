import './bootstrap';

import {createApp, markRaw} from 'vue'
import App from './App.vue'
import router from "@/router/index";
import "aos/dist/aos.css";
import "../css/output.css";

import {faDiscord, faGithub} from "@fortawesome/free-brands-svg-icons";
import {fas} from "@fortawesome/free-solid-svg-icons";

import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {library} from "@fortawesome/fontawesome-svg-core";
import 'highlight.js/lib/common';
import {createPinia} from "pinia";

library.add(
    faGithub, faDiscord,
    fas
);

const pinia = createPinia().use(({store}) => {
    store.router = markRaw(router);
});

createApp(App)
    .use(router)
    .use(pinia)
    .component('font-awesome-icon', FontAwesomeIcon)
    .mount("#app");
