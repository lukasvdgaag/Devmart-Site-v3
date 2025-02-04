import AuthService from "@/services/AuthService";
import router from "@/router/index";
import {defineStore} from "pinia";
import User from "@/models/rest/user/User";
import AccountTheme from "@/models/AccountTheme";

export const useAuth = defineStore({
    id: "authStore",
    state: () => ({
        /**
         * @type {User}
         */
        user: null,
        loaded: false,
        error: null,
    }),

    actions: {
        async login(payload) {
            try {
                this.loaded = false;
                return await AuthService.login(payload);
            } catch (error) {
                return error.response;
            }
        },
        async logout() {
            try {
                await AuthService.logout();
                this.user = null;
                router.push({name: "login"});
            } catch (error) {
                this.user = null;
                this.error = error;
            }
        },
        async getAuthUser(force = false) {
            if (!force && this.loaded) return this.user;
            try {
                const res = await AuthService.getAuthUser();
                this.user = User.fromJson(res.data.data);
                this.loaded = true;
                return this.user;
            } catch (error) {
                this.user = null;
                this.loaded = true;
                this.error = error;
                return error;
            }
        },
        applyTheme(theme = undefined) {
            if (!theme) theme = this.user?.theme ?? AccountTheme.SYSTEM;

            if (theme === AccountTheme.SYSTEM) {
                theme = window.matchMedia("(prefers-color-scheme: dark)").matches ? AccountTheme.DARK : AccountTheme.LIGHT
            }

            if (theme === AccountTheme.DARK) {
                document.documentElement.classList.add("dark");
            } else {
                document.documentElement.classList.remove("dark");
            }
        }
    },

    getters: {
        loggedIn: (state) => !!state.user,
        isAdmin: (state) => (state.user ? state.user.isAdmin : false),
    }
});
