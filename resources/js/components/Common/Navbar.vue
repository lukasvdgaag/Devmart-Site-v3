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
                     class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                     id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 dark:text-white">Hi, {{ this.user.username }} 👋</span>
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <button href="#" class="block px-4 py-2 w-full rounded-none text-sm text-gray-700 hover:bg-gray-100 flex flex-row items-center gap-2
                        dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white" type="button" @click.prevent="switchTheme()">
                                <label class="flex gap-2 items-center w-full cursor-pointer text-inherit" for="dark_mode">
                                    <font-awesome-icon :icon="darkMode ? 'lightbulb' : 'moon'" class="text-xs min-w-3"/>
                                    <span>{{ darkMode ? 'Switch to light mode' : 'Switch to dark mode' }}</span>
                                </label>
                            </button>
                        </li>
                        <li>
                            <router-link :to="{name: 'account'}"
                                         class="block flex gap-2 items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                <font-awesome-icon icon="gear" class="text-xs min-w-3"/>
                                <span>Settings</span>
                            </router-link>
                        </li>
                        <li>
                            <button
                                class="flex plain w-full rounded-none items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                                type="submit"
                                @click="this.logoutUser($event)">
                                <font-awesome-icon icon="right-from-bracket" class="text-xs min-w-3"/>
                                <span>Sign out</span>
                            </button>
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

export default {
    name: "Navbar",
    components: {SwitchInput, Logo},
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
