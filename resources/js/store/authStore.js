import AuthService from "@/services/AuthService";
import router from "@/router/index";
import {defineStore} from "pinia";

export const useAuth = defineStore({
    id: "authStore",
    state: () => ({
        user: null,
        loading: false,
        error: null,
    }),

    actions: {
        async login(payload) {
            try {
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
        async getAuthUser() {
            try {
                this.loading = true;
                const res = await AuthService.getAuthUser();
                this.user = res.data.data;
                this.loading = false;
                return this.user;
            } catch (error) {
                this.user = null;
                this.loading = false;
                this.error = error;
                return error;
            }
        }
    },

    getters: {
        loggedIn: (state) => !!state.user,
        isAdmin: (state) => (state.user ? state.user.isAdmin : false),
    }
});
