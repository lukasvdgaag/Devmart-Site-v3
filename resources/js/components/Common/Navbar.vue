<template>
    <header :class="{'header-filled': this.background}" class="header-big d-grid z-10">
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
                <div v-if="user" class="nav-action account-link cursor-pointer relative"
                     id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom-end">Account
                </div>
                <router-link exact-active-class="lmWixQ" :to="loginLink" v-else class="nav-action">Login</router-link>

                <div v-if="user"
                     class="z-50 hidden my-4 text-base list-none divide-y rounded-lg shadow"
                     :class="[!background ? 'bg-white divide-gray-100': 'bg-gray-700 divide-gray-600 dark:bg-white dark:divide-gray-100']"
                     id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm" :class="[!background ? 'text-gray-900': 'text-gray-100 dark:text-black']">Hi, {{ this.user.username }} 👋</span>
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <NavPopupItem :label="darkMode ? 'Switch to light mode' : 'Switch to dark mode'"
                                          :icon="darkMode ? 'lightbulb' : 'moon'"
                                          @click.prevent="switchTheme()"
                                          :background="background" />
                        </li>
                        <li>
                            <NavPopupItem label="Settings" icon="gear" :to="{name: 'account'}" type="link" :background="background" />
                        </li>
                        <li>
                            <NavPopupItem label="Sign out" icon="right-from-bracket" @click.prevent="this.logoutUser($event)" :background="background" />
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
</template>

<script>
import Logo from "@/components/Common/Logo";
import {useAuth} from "@/store/authStore";
import SwitchInput from "@/components/Common/SwitchInput.vue";
import {initDropdowns} from "flowbite";
import NavPopupItem from "@/components/Common/NavPopupItem.vue";

export default {
    name: "Navbar",
    components: {NavPopupItem, SwitchInput, Logo},
    props: {
        background: {
            type: Boolean,
            default: false
        }
    },

    created() {
        if (useAuth().loggedIn) {
            let theme = useAuth().user.theme;
            if (theme === "dark") {
                document.documentElement.classList.add("dark");
            }
        }

        this.darkMode = document.documentElement.classList.contains("dark");
    },

    mounted() {
        initDropdowns();
    },

    computed: {
        loginLink() {
            const uri = {name: 'login'};
            if (this.$route.name !== 'home') {
                uri.query = {redirect: this.$route.fullPath};
            }
            return uri;
        },
    },

    data() {
        return {
            user: useAuth().user,
            darkMode: false,
        }
    },

    methods: {
        logoutUser() {
            useAuth().logout();
        },
        switchTheme() {
            document.documentElement.classList.toggle("dark");
            this.darkMode = !this.darkMode;
        }
    }
}
</script>

<style scoped>

</style>
