import WikiLayout from "@/components/Pages/Wiki/WikiLayout.vue";
import WikiHome from "@/components/Pages/Wiki/WikiHome.vue";
import APSetupScoreboards from "@/components/Pages/Wiki/additionsplus/APSetupScoreboards.vue";
import PageNotFound from "@/components/Pages/Errors/PageNotFound.vue";

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
                        redirect: {
                            name: 'wiki-ap-setup-scoreboards'
                        },
                    },
                    {
                        path: 'setup-scoreboards',
                        name: 'wiki-ap-setup-scoreboards',
                        component: APSetupScoreboards,
                    }, {
                        path: 'actions',
                        name: 'wiki-ap-actions',
                    }
                ],
            },
        ]
    }
]
