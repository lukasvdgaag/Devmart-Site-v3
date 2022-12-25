<template>

    <header :class="{'header-filled': this.background}" class="header-big d-grid">
        <nav class="flex flex-row justify-space-between align-items-center">
            <a href="/" class="nav-side">
                <Logo class="header-logo"/>
            </a>

            <div class="nav-links">
                <a class="nav-link" href="/">Home</a>
                <a class="nav-link" href="/plugins">Plugins</a>
                <a class="nav-link" href="/paste">Paste</a>
                <a class="nav-link" href="/builds">Builds</a>
                <a class="nav-link" href="/wiki">Wiki</a>
                <a v-if="this.user !== null && this.user.role === 'admin'" class="nav-link" href="/admin">Admin</a>
            </div>

            <div class="nav-button nav-side">
                <div v-if="this.user !== null" class="nav-action account-link pointer relative">Account
                    <div class="account-popup-container pt-1">
                        <div class="account-popup">
                            <div class="size-14 bold account-popup-info cursor-default">
                                Hi, {{ this.user.username }} 👋
                            </div>
                            <div class="size-14 flex flex-row align-items-center justify-space-between account-popup-item">
                                <label class="w-100 pointer color-inherit" for="dark_mode">Dark Mode</label>
                                <div>
                                    <label class="switch small mt-0">
                                        <input type="checkbox"
                                               name="dark_mode"
                                               id="dark_mode" :selected="this.user !== null && this.user.theme === 'dark-theme'">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <a class="flex flex-row plain align-items-center gap-2 account-link account-popup-item"
                               href="/account">
                                <font-awesome-icon icon="gear" class="icon"/>
                                <span>Settings</span>
                            </a>
                            <button class="flex flex-row plain align-items-center gap-2 logout-link border-radius-small-bottom account-popup-item"
                                    type="submit"
                                    @click="this.logoutUser($event)">
                                <font-awesome-icon icon="right-from-bracket" class="icon"/>
                                <span>Log out</span>
                            </button>
                        </div>
                    </div>
                </div>
                <a v-else class="nav-action" href="/login">Login</a>
            </div>
        </nav>
    </header>
</template>

<script>
import Logo from "@/components/Common/Logo";
import {useAuth} from "@/store/authStore";
export default {
    name: "Navbar",
    components: {Logo},
    props: {
        background: {
            type: Boolean,
            default: false
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
