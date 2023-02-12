import './bootstrap';

import {createApp, markRaw} from 'vue'
import App from './App.vue'
import router from "@/router/index";
import "../css/output.css";

import {faDiscord, faGithub} from "@fortawesome/free-brands-svg-icons";
import {
    faAlignLeft,
    faBolt,
    faCalendarDays,
    faCartShopping,
    faChevronDown,
    faChevronLeft,
    faChevronRight,
    faChevronUp,
    faCircleCheck,
    faCircleExclamation,
    faCircleXmark,
    faClipboard,
    faCloudArrowUp,
    faCompass,
    faDownLeftAndUpRightToCenter,
    faDownload,
    faEye,
    faEyeSlash,
    faFileArrowDown,
    faGear,
    faGem,
    faHouseUser,
    faLightbulb,
    faMagnifyingGlass,
    faMoneyBillTransfer,
    faMoon,
    faPaste,
    faPen,
    faRightFromBracket,
    faSackDollar,
    faShareNodes,
    faSun,
    faTrashCan,
    faUpRightAndDownLeftFromCenter
} from "@fortawesome/free-solid-svg-icons";

import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {library} from "@fortawesome/fontawesome-svg-core";
import 'highlight.js/lib/common';
// import 'highlight.js/styles/github.css';
import {createPinia} from "pinia";
import {createMetaManager} from "vue-meta";

library.add(
    faGithub, faDiscord,
    faGear, faRightFromBracket, faEye, faEyeSlash, faHouseUser, faMoneyBillTransfer, faPaste, faCircleCheck, faCircleXmark, faCircleExclamation, faSackDollar,
    faCalendarDays, faMagnifyingGlass, faCompass, faCartShopping, faBolt, faGem, faChevronLeft, faChevronRight, faChevronUp, faFileArrowDown, faCloudArrowUp,
    faChevronDown, faShareNodes, faDownload, faAlignLeft, faPen, faClipboard, faTrashCan, faUpRightAndDownLeftFromCenter, faDownLeftAndUpRightToCenter,
    faSun, faMoon, faLightbulb
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
