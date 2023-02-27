import WikiLayout from "@/components/Pages/Wiki/WikiLayout.vue";
import WikiHome from "@/components/Pages/Wiki/WikiHome.vue";
import APSetupScoreboards from "@/components/Pages/Wiki/additionsplus/APSetupScoreboards.vue";

export default [
    {
        path: '/wiki',
        name: 'wiki',
        component: WikiLayout,
        children: [
            {
                path: '',
                name: 'wiki-home',
                component: WikiHome,
                meta: {
                    name: 'Home'
                }
            }, {
                path: '/additionsplus',
                children: [
                    {
                        path: 'setup-scoreboards',
                        name: 'wiki-ap-setup-scoreboards',
                        component: APSetupScoreboards,
                    }, {
                        path: 'actions',
                        name: 'wiki-ap-actions',
                    }
                ],
            }
        ]
    }
]
