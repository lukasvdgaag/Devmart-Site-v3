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
    faFileArrowDown,
    faChevronUp,
    faChevronDown,
} from "@fortawesome/free-solid-svg-icons";

import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {library} from "@fortawesome/fontawesome-svg-core";
import {createPinia} from "pinia";
import {createMetaManager} from "vue-meta";

library.add(
    faGithub, faDiscord,
    faGear, faRightFromBracket, faEye, faEyeSlash, faHouseUser, faMoneyBillTransfer, faPaste, faCircleCheck, faCircleXmark, faCircleExclamation, faSackDollar,
    faCalendarDays, faMagnifyingGlass, faCompass, faCartShopping, faBolt, faGem, faChevronLeft, faChevronRight,faChevronUp, faFileArrowDown,
    faChevronDown,
);

const pinia = createPinia().use(({store}) => {
    store.router = markRaw(router);
});

createApp(App)
    .use(router)
    .use(pinia)
    .use(createMetaManager())
    .component('font-awesome-icon', FontAwesomeIcon)
    .mount("#app");
