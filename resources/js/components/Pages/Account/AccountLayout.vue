<template>
    <div class="flex flex-row">
        <div class="w-full flex flex-col items-center m-0 p-0">
            <Navbar :background="true"/>

            <div class="d-grid mb-6 mt-4">
                <AccountSidebar/>

                <div class="col-span-12 lg:col-span-9 pt-2">
                    <AdminEditingWarning v-if="!loading && isAdmin" :username="user?.username ?? null"/>

                    <router-view :isAdmin="isAdmin" :user="user" :userId="userId" :userLoading="loading"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import AccountSidebar from "@/components/Pages/Account/AccountSidebar.vue";
import {useAuth} from "@/store/authStore";
import UserRepository from "@/services/UserRepository";
import AdminEditingWarning from "@/components/Pages/Account/AdminEditingWarning.vue";

export default {
    name: "AccountHome",
    components: {AdminEditingWarning, AccountSidebar, Navbar},

    async created() {
        this.userId = this.$route.query.user ? Number.parseInt(this.$route.query.user) : null;
        if (this.user?.role !== "admin" || !this.userId) {
            this.userId = useAuth().user?.id;
        }
        await this.fetchUserInfo(this.userId);
    },

    computed: {
        isAdmin() {
            return useAuth().user?.role === "admin" && (this.$route.query.user != null || useAuth().user?.id !== this.user?.id);
        },
    },

    data() {
        return {
            /**
             * @type {User}
             */
            user: useAuth().user,
            loading: true,
            userId: useAuth().user?.id
        }
    },

    methods: {
        async fetchUserInfo(id) {
            this.loading = true;
            this.user = await UserRepository.fetchUserById(id);
            if (!this.user) {
                this.$router.push({name: "not-found"});
            }
            this.loading = false;
        },
    },
}
</script>

<style scoped>

</style>
