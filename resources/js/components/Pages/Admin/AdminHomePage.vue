<template>
    <div class="flex flex-row">
        <div class="w-full flex flex-col items-center m-0 p-0">
            <Navbar :background="true"/>

            <div class="d-grid mb-6 mt-4">
                <div class="col-span-12 pt-2">
                    <div class="flex flex-col gap-2 w-full items-center">
                        <h1 class="text-center">Admin Panel</h1>
                        <p class="text-center">Hey there, admin! What do you want to do next? 👋</p>
                    </div>
                    <hr>

                    <Quicklinks>
                        <div class="hidden lg:block"></div>
                        <QuickLink label="User management"
                                   icon="house-user"
                                   url="/admin/users"
                                   description="View user data, edit account information, and manage plugin access."
                                   link-text="Let's edit some users!"
                        />
                        <div></div>
                    </Quicklinks>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import Quicklinks from "@/components/Common/Quicklinks.vue";
import QuickLink from "@/components/Common/QuickLink.vue";
import WikiQuicklink from "@/components/Pages/Wiki/WikiQuicklink.vue";

export default {
    name: "AdminHomePage",
    components: {WikiQuicklink, QuickLink, Quicklinks, Navbar},


}
</script>

<style scoped>

</style>
