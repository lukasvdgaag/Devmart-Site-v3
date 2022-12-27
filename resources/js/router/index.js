import HomePage from "@/components/pages/HomePage";
import {createRouter, createWebHistory} from 'vue-router';
import LoginPage from "@/components/pages/auth/LoginPage";
import RegisterPage from "@/components/pages/auth/RegisterPage";
import {checkAuthenticated} from "@/middleware";
import AccountLayout from "@/components/Pages/Account/AccountLayout.vue";
import AccountHome from "@/components/Pages/Account/AccountHome.vue";
import AccountBilling from "@/components/Pages/Account/AccountBilling.vue";
import AccountSales from "@/components/Pages/Account/AccountSales.vue";
import PluginsListPage from "@/components/Pages/Plugins/PluginsListPage.vue";

const routes = [
    {
        path: '/',
        name: 'home',
        component: HomePage
    }, {
        path: '/login',
        name: 'login',
        component: LoginPage
    }, {
        path: '/register',
        name: 'register',
        component: RegisterPage
    }, {
        path: '/paste',
        name: 'paste',
    }, {
        path: '/builds',
        name: 'builds',
    }, {
        path: '/wiki',
        name: 'wiki',
    }, {
        path: '/admin',
        name: 'admin',
        meta: {
            requireAuth: true,
            requireAdmin: true
        }
    }, {
        path: '/plugins',
        name: 'plugins',
        component: PluginsListPage
    }, {
        path: '/plugins/:pluginId',
        name: 'plugin-overview'
    }, {
        path: '/account',
        component: AccountLayout,
        meta: {
            requireAuth: true
        },
        children: [
            {
                path: '',
                name: 'account',
                component: AccountHome
            }, {
                path: 'billing',
                name: 'accountBilling',
                component: AccountBilling
            }, {
                path: 'sales',
                name: 'accountSales',
                component: AccountSales
            }, {
                path: 'pastes',
                name: 'accountPastes',
                component: AccountHome
            }
        ]
    }
];

let index = createRouter({
    history: createWebHistory(),
    routes: routes
});
index.beforeEach(checkAuthenticated);
export default index;
