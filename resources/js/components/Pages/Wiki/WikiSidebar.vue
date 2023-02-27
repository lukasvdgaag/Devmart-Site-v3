<template>
    <div class="col-span-3 flex flex-col items-end bg-gray-75 dark:bg-gray-800 border-r border-r-gray-200 dark:border-r-gray-700 wsb p-5 min-h-full">
        <h2 class="text-lg">Table Of Contents</h2>

        <ul class="m-0 mt-4">
            <WikiSidebarItem v-for="route in routes" :route="route" :key="route.path" />
        </ul>
    </div>
</template>

<script>
import wikiRoutes from "@/router/wiki-routes.js";
import WikiSidebarItem from "@/components/Pages/Wiki/WikiSidebarItem.vue";

export default {
    name: "WikiSidebar",
    components: {WikiSidebarItem},

    data() {
        return {
            routes: wikiRoutes[0].children
        }
    }
}
</script>

<style scoped>
    .wsb > * {
        @apply max-w-[320px] w-full;
    }
</style>
