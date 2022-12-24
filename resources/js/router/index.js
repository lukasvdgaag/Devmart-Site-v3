import HomePage from "@/components/pages/HomePage";
import {createRouter, createWebHistory} from 'vue-router';
import LoginPage from "@/components/pages/auth/LoginPage";
import RegisterPage from "@/components/pages/auth/RegisterPage";
import {checkAuthenticated} from "@/middleware";
import AccountLayout from "@/components/Pages/Account/AccountLayout.vue";
import AccountHome from "@/components/Pages/Account/AccountHome.vue";

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
        path: '/admin',
        name: 'admin',
        meta: {
            requireAuth: true,
            requireAdmin: true
        }
    }, {
        path: '/account',
        name: 'account',
        component: AccountLayout,
        meta: {
            requireAuth: true
        },
        children: [{
            path: '',
            name: 'accountHome',
            component: AccountHome
        }, {
            path: 'billing',
            name: 'accountBilling',
            component: AccountHome
        }, {
            path: 'sales',
            name: 'accountSales',
            component: AccountHome
        }, {
            path: 'pastes',
            name: 'accountPastes',
            component: AccountHome
        }]
    }
];

let index = createRouter({
    history: createWebHistory(),
    routes: routes
});
index.beforeEach(checkAuthenticated);
export default index;
