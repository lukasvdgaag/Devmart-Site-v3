<template>
    <div class="flex flex-row h-full">
        <div class="w-full flex flex-col items-center m-0 p-0 h-full">
            <Navbar background/>

            <div class="grid grid-cols-12 w-full h-full border-t border-t-gray-200 dark:border-t-gray-700">
                <WikiSidebar/>
                <div class="col-span-9 w-full h-full p-12 max-w-6xl">
                    <router-view/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import WikiSidebar from "@/components/Pages/Wiki/WikiSidebar.vue";

export default {
    name: "WikiLayout",
    components: {WikiSidebar, Navbar}
}
</script>

<style scoped>

</style>
