import WikiLayout from "@/components/Pages/Wiki/WikiLayout.vue";
import WikiHome from "@/components/Pages/Wiki/WikiHome.vue";

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
                    }, {
                        path: 'actions',
                    }
                ],
            }
        ]
    }
]
