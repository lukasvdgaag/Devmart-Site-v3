<template>
    <header :class="{'header-filled': this.background}" class="header-big d-grid z-10">
        <nav class="flex flex-row justify-between items-center">
            <router-link :to="{name: 'home'}" class="nav-side" exact-active-class="lmWixQ">
                <Logo class="header-logo"/>
            </router-link>

            <div class="nav-links">
                <router-link :to="{name: 'home'}" class="nav-link" exact-active-class="active">Home</router-link>
                <router-link :to="{name: 'plugins'}" active-class="active" class="nav-link">Plugins</router-link>
                <router-link :to="{name: 'paste'}" active-class="active" class="nav-link">Paste</router-link>
                <router-link :to="{name: 'builds'}" active-class="active" class="nav-link">Builds</router-link>
                <router-link :to="{name: 'wiki'}" active-class="active" class="nav-link">Wiki</router-link>
                <router-link v-if="this.user !== null && this.user.role === 'admin'" :to="{name: 'admin'}" active-class="active" class="nav-link">Admin
                </router-link>
            </div>

            <div class="nav-button nav-side">
                <div v-if="user" id="user-menu-button"
                     aria-expanded="false" class="nav-action account-link cursor-pointer relative" data-dropdown-placement="bottom-end" data-dropdown-toggle="user-dropdown">Account
                </div>
                <router-link v-else :to="loginLink" class="nav-action" exact-active-class="lmWixQ">Login</router-link>

                <div v-if="user"
                     id="user-dropdown"
                     :class="[!background ? 'bg-white divide-gray-100': 'bg-gray-700 divide-gray-600 dark:bg-white dark:divide-gray-100']"
                     class="z-50 hidden my-4 text-base list-none divide-y rounded-lg shadow">
                    <div class="px-4 py-3">
                        <span :class="[!background ? 'text-gray-900': 'text-gray-100 dark:text-black']" class="block text-sm">Hi, {{
                                this.user.username
                            }} 👋</span>
                    </div>
                    <ul aria-labelledby="user-menu-button" class="py-2">
                        <li>
                            <NavPopupItem :background="background"
                                          :icon="darkMode ? 'lightbulb' : 'moon'"
                                          :label="darkMode ? 'Switch to light mode' : 'Switch to dark mode'"
                                          @click.prevent="switchTheme()"/>
                        </li>
                        <li>
                            <NavPopupItem :background="background" :to="{name: 'account'}" icon="gear" label="Settings" type="link"/>
                        </li>
                        <li>
                            <NavPopupItem :background="background" icon="right-from-bracket" label="Sign out" @click.prevent="this.logoutUser($event)"/>
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
import SwitchInput from "@/components/Common/Form/SwitchInput.vue";
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
