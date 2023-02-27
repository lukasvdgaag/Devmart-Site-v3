<template>
    <div class="flex flex-row h-full">
        <div class="w-full flex flex-col items-center m-0 p-0 h-full">
            <Navbar background/>

            <div class="grid grid-cols-12 w-full h-full border-t border-t-gray-200 dark:border-t-gray-700">
                <WikiSidebar/>
                <div class="wiki col-span-12 lg:col-span-9 w-full h-full p-4 py-8 lg:p-12 max-w-6xl">
                    <div class="hidden lg:block mb-4">
                        <WikiCurrentPage/>
                    </div>

                    <router-view/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import WikiSidebar from "@/components/Pages/Wiki/WikiSidebar.vue";
import WikiCurrentPage from "@/components/Pages/Wiki/WikiCurrentPage.vue";

export default {
    name: "WikiLayout",
    components: {WikiCurrentPage, WikiSidebar, Navbar}
}
</script>

<style>
    .wiki ul {
        @apply list-disc list-inside pl-5 my-3;
    }
    .wiki p {
        @apply my-2;
    }
</style>
