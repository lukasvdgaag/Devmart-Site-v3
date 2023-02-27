<template>
    <div class="col-span-12 lg:col-span-3 flex flex-col items-end bg-gray-75 dark:bg-gray-800
    border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 wsb p-5 lg:min-h-full">
        <div class="lg:hidden">
            <WikiCurrentPage/>
        </div>

        <div class="hidden lg:block">
            <h2 class="text-lg">Table Of Contents</h2>

            <ul class="m-0 mt-4">
                <WikiSidebarItem v-for="route in routes" :route="route" :key="route.path" :full-path="route.path"/>
            </ul>
        </div>
    </div>
</template>

<script>
import wikiRoutes from "@/router/wiki-routes.js";
import WikiSidebarItem from "@/components/Pages/Wiki/WikiSidebarItem.vue";
import WikiCurrentPage from "@/components/Pages/Wiki/WikiCurrentPage.vue";

export default {
    name: "WikiSidebar",
    components: {WikiCurrentPage, WikiSidebarItem},

    data() {
        return {
            routes: wikiRoutes[0].children
        }
    },
}
</script>

<style scoped>
.wsb > * {
    @apply lg:max-w-[320px] w-full;
}
</style>
