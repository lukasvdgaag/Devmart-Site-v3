import WikiLayout from "@/components/Pages/Wiki/WikiLayout.vue";

export default [
    {
        path: '/wiki',
        name: 'wiki',
        component: WikiLayout,
        children: [
            {
                path: '',
                name: 'wiki-home',
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
