<template>

    <header :class="{'header-filled': this.background}" class="header-big d-grid">
        <nav class="flex flex-row justify-between items-center">
            <router-link exact-active-class="lmWixQ" :to="{name: 'home'}" class="nav-side">
                <Logo class="header-logo"/>
            </router-link>

            <div class="nav-links">
                <router-link exact-active-class="active" :to="{name: 'home'}" class="nav-link">Home</router-link>
                <router-link active-class="active" :to="{name: 'plugins'}" class="nav-link">Plugins</router-link>
                <router-link active-class="active" :to="{name: 'paste'}" class="nav-link">Paste</router-link>
                <router-link active-class="active" :to="{name: 'builds'}" class="nav-link">Builds</router-link>
                <router-link active-class="active" :to="{name: 'wiki'}" class="nav-link">Wiki</router-link>
                <router-link active-class="active" :to="{name: 'admin'}" v-if="this.user !== null && this.user.role === 'admin'" class="nav-link">Admin
                </router-link>
            </div>

            <div class="nav-button nav-side">
                <div v-if="this.user !== null" class="nav-action account-link pointer relative">Account
                    <div class="account-popup-container pt-1">
                        <div class="account-popup">
                            <div class="size-14 font-bold account-popup-info cursor-default">
                                Hi, {{ this.user.username }} 👋
                            </div>
                            <div class="size-14 flex flex-row items-center justify-between account-popup-item">
                                <label class="w-full pointer text-inherit" for="dark_mode">Dark Mode</label>
                                <div>
                                    <SwitchInput :small="true" :selected="this.user?.theme === 'dark-theme'"/>
                                </div>
                            </div>
                            <router-link exact-active-class="lmWixQ" :to="{name: 'account'}"
                                         class="flex flex-row plain items-center gap-2 account-link account-popup-item">
                                <font-awesome-icon icon="gear" class="icon"/>
                                <span>Settings</span>
                            </router-link>
                            <button class="flex flex-row plain items-center gap-2 logout-link border-radius-small-bottom account-popup-item"
                                    type="submit"
                                    @click="this.logoutUser($event)">
                                <font-awesome-icon icon="right-from-bracket" class="icon"/>
                                <span>Log out</span>
                            </button>
                        </div>
                    </div>
                </div>
                <router-link exact-active-class="lmWixQ" :to="loginLink" v-else class="nav-action">Login</router-link>
            </div>
        </nav>
    </header>
</template>

<script>
import Logo from "@/components/Common/Logo";
import {useAuth} from "@/store/authStore";
import SwitchInput from "@/components/Common/SwitchInput.vue";

export default {
    name: "Navbar",
    components: {SwitchInput, Logo},
    props: {
        background: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        loginLink() {
            const uri = {name: 'login'};
            if (this.$route.name !== 'home') {
                uri.query = {redirect: this.$route.fullPath};
            }
            return uri;
        }
    },

    data() {
        return {
            user: useAuth().user,
        }
    },

    methods: {
        logoutUser() {
            useAuth().logout();
        }
    }
}
</script>

<style scoped>

</style>
