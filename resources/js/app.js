import './bootstrap';

import {createApp} from 'vue'
import App from './App.vue'
import router from "@/router";
import store from "@/store";

import {faGithub, faDiscord} from "@fortawesome/free-brands-svg-icons";
import {faGear, faRightFromBracket, faEye, faEyeSlash} from "@fortawesome/free-solid-svg-icons";

import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {library} from "@fortawesome/fontawesome-svg-core";

library.add(faGithub, faGear, faRightFromBracket, faDiscord, faEye, faEyeSlash);

createApp(App)
    .use(router)
    .use(store)
    .component('font-awesome-icon', FontAwesomeIcon)
    .mount("#app")
