import HomePage from "@/components/pages/HomePage";
import {createRouter, createWebHistory} from 'vue-router';
import LoginPage from "@/components/pages/auth/LoginPage";
import RegisterPage from "@/components/pages/auth/RegisterPage";
import {checkAuthenticated} from "@/middleware";
import AccountLayout from "@/components/Pages/Account/AccountLayout.vue";
import AccountHome from "@/components/Pages/Account/Views/AccountHome.vue";
import AccountBilling from "@/components/Pages/Account/Views/AccountBilling.vue";
import AccountSales from "@/components/Pages/Account/Views/AccountSales.vue";
import PluginsListPage from "@/components/Pages/Plugins/Views/PluginsListPage.vue";
import PluginPageLayout from "@/components/Pages/Plugins/PluginPageLayout.vue";
import PluginOverviewPage from "@/components/Pages/Plugins/Views/PluginOverviewPage.vue";
import PageNotFound from "@/components/Pages/Errors/PageNotFound.vue";
import UpdatePluginPage from "@/components/Pages/Plugins/Views/UpdatePluginPage.vue";
import PluginVersionsPage from "@/components/Pages/Plugins/Views/PluginVersionsPage.vue";
import PluginUpdatesPage from "@/components/Pages/Plugins/Views/PluginUpdatesPage.vue";
import PluginActionPageLayout from "@/components/Pages/Plugins/PluginActionPageLayout.vue";
import EditPluginPage from "@/components/Pages/Plugins/Views/EditPluginPage.vue";

const routes = [
    {
        path: '/',
        name: 'home',
        component: HomePage,
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
        component: PluginsListPage,
    }, {
        path: '/plugins/:pluginId',
        props: true,
        component: PluginPageLayout,
        children: [
            {
                path: '',
                name: 'plugin-overview',
                component: PluginOverviewPage
            }, {
                path: 'versions',
                name: 'plugin-versions',
                component: PluginVersionsPage,
            }, {
                path: 'updates',
                name: 'plugin-updates',
                component: PluginUpdatesPage
            }
        ]
    }, {
        path: '/plugins/:pluginId',
        props: true,
        component: PluginActionPageLayout,
        meta: {
            requireAuth: true,
        },
        children: [
            {
                path: 'edit',
                name: 'edit-plugin',
                component: EditPluginPage,
                meta: {
                    title: 'Edit Plugin'
                }
            }, {
                path: 'update',
                name: 'update-plugin',
                component: UpdatePluginPage,
                meta: {
                    title: 'Update Plugin'
                }
            }
        ]
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
    }, {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: PageNotFound
    }
];

let index = createRouter({
    history: createWebHistory(),
    routes: routes
});
index.beforeEach(checkAuthenticated);
export default index;
