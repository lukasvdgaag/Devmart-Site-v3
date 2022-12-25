<template>
    <div class="flex flex-row h-100">
        <div class="w-100 flex align-items-center m-0 p-0 h-100">
            <Navbar :background="true"/>
            <AuthCard class="w-100">
                <form method="post" @submit.prevent="login">
                    <a href="/login-with-discord"
                       class="button primary flex align-center plain p-3">
                        <div class="flex flex-row gap-2">
                            <font-awesome-icon :icon="['fab', 'discord']" class="icon light"/>
                            <div class="size-18 bold">Log in with Discord</div>
                        </div>
                    </a>

                    <hr>

                    <div>
                        <Label for="username" value="Username or Email"/>
                        <Input id="username"
                               v-model="data.username"
                               class="block mt-1 w-full"
                               type="text"
                               :errors="errors"
                               item="username"
                               required
                               autofocus/>
                    </div>
                    <div class="mt-4">
                        <Label for="password" value="Password"/>
                        <Input id="password"
                               v-model="data.password"
                               class="block mt-1 w-full"
                               type="password"
                               :errors="errors"
                               item="username"
                               required
                               autocomplete="current-password"/>
                    </div>

                    <ValidationError item="username" :errors="errors"/>

                    <div class="flex flex-row mt-2 center justify-space-between">
                        <div>
                            <label for="remember_me" class="inline-flex items-center h-100">
                                <Input id="remember_me"
                                       v-model="data.remember"
                                       type="checkbox"/>

                                <span class="ml-2 text-sm text-gray-600">Remember me</span>
                            </label>
                        </div>
                        <div>
                            <router-link to="forgot-password" class="underline static size-14">Forgot your password?</router-link>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button class="primary w-100 p-2" :disabled="loggingIn">
                            {{ loggingIn ? "Logging you in..." : "Log in" }}
                        </button>
                    </div>
                </form>
            </AuthCard>
        </div>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar";
import AuthCard from "@/components/Common/AuthCard";
import Label from "@/components/Common/Label";
import Input from "@/components/Common/Input";
import {useAuth} from "@/store/authStore";
import ValidationError from "@/components/Common/ValidationError.vue";

export default {
    name: "LoginPage",
    components: {ValidationError, Input, Label, AuthCard, Navbar},

    created() {
        let loggedIn = this.authStore.loggedIn;
        if (loggedIn) {
            this.$router.push("/");
        }
    },

    data() {
        return {
            authStore: useAuth(),
            errors: {},
            data: {
                username: '',
                password: '',
                remember: false
            },
            loggingIn: false
        }
    },

    methods: {
        async login() {
            if (this.loggingIn) return;
            this.loggingIn = true;

            try {
                const response = await this.authStore.login(this.data);
                if (response.status === 200) {
                    this.$router.push({name: "home"});
                } else {
                    this.errors = response.data.errors;
                }
            } catch (e) {
                this.errors = e?.response?.data?.errors ?? {};
            }

            this.loggingIn = false;
        }
    },

    computed: {
        redirectUrl() {
            return this.$route.params.redirect ?? "/";
        }
    }
}
</script>

<style scoped>

</style>
