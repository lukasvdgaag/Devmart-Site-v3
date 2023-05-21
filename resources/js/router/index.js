import HomePage from "@/components/Pages/HomePage.vue";
import {createRouter, createWebHistory} from 'vue-router';
import LoginPage from "@/components/Pages/Auth/LoginPage.vue";
import RegisterPage from "@/components/Pages/Auth/RegisterPage.vue";
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
import wikiRoutes from "@/router/wiki-routes.js";
import AccountPastes from "@/components/Pages/Account/Views/AccountPastes.vue";
import PluginPurchasesPage from "@/components/Pages/Plugins/Views/PluginPurchasesPage.vue";
import PaymentConfirmedPage from "@/components/Pages/Payments/Views/PaymentConfirmedPage.vue";
import AdminHomePage from "@/components/Pages/Admin/AdminHomePage.vue";

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
        path: '/admin',
        children: [
            {
                path: '',
                name: 'admin',
                component: AdminHomePage,
            }
        ],
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
                path: 'purchases',
                name: 'plugin-purchases',
                component: PluginPurchasesPage,
            }, {
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
                component: AccountPastes
            }
        ]
    }, {
        path: '/payment-confirmed',
        name: 'payment-confirmed',
        component: PaymentConfirmedPage
    }, {
        path: '/not-found',
        name: 'not-found',
        component: PageNotFound
    }, {
        path: '/:pathMatch(.*)*',
        redirect: '/not-found'
    }
];

let index = createRouter({
    history: createWebHistory(import.meta.env.APP_URL),
    routes: [
        ...routes,
        ...wikiRoutes
    ]
});
index.beforeEach(checkAuthenticated);
export default index;
