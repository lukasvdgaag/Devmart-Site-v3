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
import PluginPageLayout from "@/components/Pages/Plugins/Layouts/PluginPageLayout.vue";
import PluginOverviewPage from "@/components/Pages/Plugins/Views/PluginOverviewPage.vue";
import PageNotFound from "@/components/Pages/Errors/PageNotFound.vue";
import UpdatePluginPage from "@/components/Pages/Plugins/Views/UpdatePluginPage.vue";
import PluginVersionsPage from "@/components/Pages/Plugins/Views/PluginVersionsPage.vue";
import PluginUpdatesPage from "@/components/Pages/Plugins/Views/PluginUpdatesPage.vue";
import PluginActionPageLayout from "@/components/Pages/Plugins/Layouts/PluginActionPageLayout.vue";
import EditPluginPage from "@/components/Pages/Plugins/Views/EditPluginPage.vue";
import PluginVersionsLayout from "@/components/Pages/Plugins/Layouts/PluginVersionsLayout.vue";
import PluginUpdateInfoPage from "@/components/Pages/Plugins/Views/PluginUpdateInfoPage.vue";
import PastePageLayout from "@/components/Pages/Paste/PastePageLayout.vue";
import PasteCreatePage from "@/components/Pages/Paste/PasteCreatePage.vue";
import PasteInformationPage from "@/components/Pages/Paste/PasteInformationPage.vue";
import AuthPageLayout from "@/components/Pages/Auth/AuthPageLayout.vue";

const routes = [
    {
        path: '/',
        name: 'home',
        component: HomePage,
    }, {
        path: '/login',
        component: AuthPageLayout,
        children: [{
            path: '',
            component: LoginPage,
            name: 'login'
        }]
    }, {
        path: '/register',
        component: AuthPageLayout,
        props: {
            header: 'Sign Up.',
            subheader: 'Welcome to Devmart 👋'
        },
        children: [{
            path: '',
            component: RegisterPage,
            name: 'register'
        }]
    }, {
        path: '/paste',
        component: PastePageLayout,
        children: [
            {
                path: '',
                name: 'paste',
                component: PasteCreatePage,
            }, {
                path: ':pasteId',
                name: 'paste-info',
                component: PasteInformationPage,
                props: true,
            }, {
                path: ':pasteId/edit',
                name: 'paste-edit',
                component: PasteCreatePage,
                props: true,
            }
        ],
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
            },
            {
                path: 'versions',
                component: PluginVersionsLayout,
                children: [
                    {
                        path: '',
                        name: 'plugin-versions',
                        component: PluginVersionsPage,
                    }
                ],
            }, {
                path: 'updates',
                component: PluginVersionsLayout,
                children: [
                    {
                        path: '',
                        name: 'plugin-updates',
                        component: PluginUpdatesPage,
                    },
                ],
            }, {
                path: 'updates/:updateId',
                name: 'plugin-update',
                props: true,
                component: PluginUpdateInfoPage,
            },
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
