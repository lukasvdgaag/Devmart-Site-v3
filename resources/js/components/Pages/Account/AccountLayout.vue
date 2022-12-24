<template>
    <div class="flex flex-row">
        <div class="w-full flex align-items-center m-0 p-0">
            <Navbar :background="true"/>

            <div class="d-grid d-grid-12 mb-6 mt-4">
                <AccountSidebar/>


                <div class="content content-right-margin pt-2">
                    <router-view/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import AccountSidebar from "@/components/Pages/Account/AccountSidebar.vue";

export default {
    name: "AccountHome",
    components: {AccountSidebar, Navbar},
}
</script>

<style scoped>

</style>
