<template>
    <div class="flex flex-row h-full">
        <div class="w-full flex flex-col items-center m-0 p-0 h-full">
            <Navbar :background="true"/>
            <AuthCard :header="header" :subheader="subheader" class="w-full">
                <router-view/>
            </AuthCard>
        </div>
    </div>
</template>

<script>
import AuthCard from "@/components/Common/AuthCard.vue";
import Navbar from "@/components/Common/Navbar.vue";
import {useAuth} from "@/store/authStore";

export default {
    name: "AuthPageLayout",
    components: {Navbar, AuthCard},

    created() {
        let loggedIn = useAuth().loggedIn;
        if (loggedIn) {
            this.$router.push("/");
        }
    },

    props: {
        header: {
            type: String,
        },
        subheader: {
            type: String
        }
    }
}
</script>

<style scoped>

</style>
