import WikiLayout from "@/components/Pages/Wiki/WikiLayout.vue";
import WikiHome from "@/components/Pages/Wiki/WikiHome.vue";
import APSetupScoreboards from "@/components/Pages/Wiki/additionsplus/APSetupScoreboards.vue";
import ApApiHome from "@/components/Pages/Wiki/additionsplus/api/ApApiHome.vue";
import ApApiHook from "@/components/Pages/Wiki/additionsplus/api/ApApiHook.vue";
import ApApiUseActions from "@/components/Pages/Wiki/additionsplus/api/ApApiActions.vue";

export default [
    {
        path: '/wiki',
        component: WikiLayout,
        children: [
            {
                path: '',
                name: 'wiki',
                component: WikiHome,
                meta: {
                    name: 'Home'
                }
            }, {
                path: 'additionsplus',
                children: [
                    {
                        path: '',
                        name: 'wiki.additions',
                        redirect: {
                            name: 'wiki.additions.setup-scoreboards'
                        },
                    }, {
                        path: 'api',
                        children: [
                            {
                                path: '',
                                name: 'wiki.additions.api',
                                component: ApApiHome
                            }, {
                                path: 'usage',
                                name: 'wiki.additions.api.usage',
                                component: ApApiHook,
                            }, {
                                path: 'actions',
                                name: 'wiki.additions.api.actions',
                                component: ApApiUseActions,
                            }
                        ]
                    },
                    {
                        path: 'setup-scoreboards',
                        name: 'wiki.additions.setup-scoreboards',
                        component: APSetupScoreboards,
                    }, {
                        path: 'actions',
                        name: 'wiki.additions.actions',
                    }
                ],
            },
        ]
    }
]
