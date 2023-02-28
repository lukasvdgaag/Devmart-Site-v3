<template>
    <div class="col-span-12 lg:col-span-3 flex flex-col items-end bg-gray-75 dark:bg-gray-800
    border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 wsb p-5 lg:min-h-full">
        <div class="flex items-center gap-4 lg:hidden">
            <button data-drawer-target="drawer-navigation" data-drawer-show="drawer-navigation" aria-controls="drawer-navigation" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                </svg>
            </button>

            <WikiCurrentPage/>

            <div id="drawer-navigation" class="fixed top-0 left-0 z-40 w-[320px] h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white dark:bg-gray-800" tabindex="-1" aria-labelledby="drawer-navigation-label">
                <h5 id="drawer-navigation-label" class="text-base font-semibold text-gray-500 uppercase dark:text-gray-400">Devmart Wiki</h5>
                <button type="button" data-drawer-hide="drawer-navigation" aria-controls="drawer-navigation" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" >
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close menu</span>
                </button>
                <div class="py-4 overflow-y-auto">
                    <ul class="m-0 mt-4">
                        <WikiSidebarItem v-for="route in routes" :route="route" :key="route.path" :full-path="route.path"/>
                    </ul>
                </div>
            </div>
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
import {initDrawers} from "flowbite";

export default {
    name: "WikiSidebar",
    components: {WikiCurrentPage, WikiSidebarItem},

    mounted() {
        initDrawers();
    },

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
