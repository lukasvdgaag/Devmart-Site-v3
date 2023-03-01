<template>
    <div class="min-h-full flex">
        <div class="w-full flex-grow flex flex-col items-center m-0 p-0 min-h-full">
            <Navbar background/>

            <div class="flex flex-col flex-grow lg:grid grid-cols-12 w-full min-h-[calc(100%-80px)] border-t border-t-gray-200 dark:border-t-gray-700">
                <WikiSidebar/>
                <div class="flex flex-col flex-grow wiki col-span-12 lg:col-span-9 w-full min-h-full p-4 py-8 lg:p-12 max-w-6xl">
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
.wiki ul, .wiki ol {
    @apply list-inside pl-5 my-3;
}
.wiki ul ul, .wiki ol ul, .wiki ul ol, .wiki ol ol {
    @apply my-0;
}

.wiki ul {
    @apply list-disc;
}
.wiki ol {
    @apply list-decimal;
}

.wiki ul ul {
    @apply list-circle;
}

.wiki p, .wiki .codeblock {
    @apply my-2;
}

.wiki h2:not(:first-of-type) {
    @apply mt-6;
}

.wiki h3 {
    @apply mt-4;
}
</style>
