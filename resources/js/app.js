import './bootstrap';

import {createApp, markRaw} from 'vue'
import App from './App.vue'
import router from "@/router/index";
import "../css/output.css";

import {faDiscord, faGithub} from "@fortawesome/free-brands-svg-icons";
import {
    faBolt,
    faCalendarDays,
    faCartShopping,
    faChevronLeft,
    faChevronRight,
    faCircleCheck,
    faCircleExclamation,
    faCircleXmark,
    faCompass,
    faEye,
    faEyeSlash,
    faGear,
    faGem,
    faHouseUser,
    faMagnifyingGlass,
    faMoneyBillTransfer,
    faPaste,
    faRightFromBracket,
    faSackDollar,
} from "@fortawesome/free-solid-svg-icons";

import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {library} from "@fortawesome/fontawesome-svg-core";
import {createPinia} from "pinia";

library.add(
    faGithub, faDiscord,
    faGear, faRightFromBracket, faEye, faEyeSlash, faHouseUser, faMoneyBillTransfer, faPaste, faCircleCheck, faCircleXmark, faCircleExclamation, faSackDollar,
    faCalendarDays, faMagnifyingGlass, faCompass, faCartShopping, faBolt, faGem, faChevronLeft, faChevronRight,
);

const pinia = createPinia().use(({store}) => {
    store.router = markRaw(router);
});

createApp(App)
    .use(router)
    .use(pinia)
    .component('font-awesome-icon', FontAwesomeIcon)
    .mount("#app");
