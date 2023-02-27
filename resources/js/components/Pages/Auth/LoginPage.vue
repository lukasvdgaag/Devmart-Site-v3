<template>
    <form method="post" @submit.prevent="login">
        <a class="button rounded-md primary flex flex-col align-center plain !p-3" href="/login-with-discord">
            <div class="flex flex-row gap-2">
                <font-awesome-icon :icon="['fab', 'discord']" class="icon light"/>
                <div class="text-base font-bold">Log in with Discord</div>
            </div>
        </a>

        <hr/>

        <div>
            <Label for="username" value="Username or Email"/>
            <Input id="username"
                   v-model="data.username"
                   :errors="errors"
                   autofocus
                   class="block mt-1 w-full"
                   item="username"
                   required
                   type="text"/>
        </div>
        <div class="mt-4">
            <Label for="password" value="Password"/>
            <Input id="password"
                   v-model="data.password"
                   :errors="errors"
                   autocomplete="current-password"
                   class="block mt-1 w-full"
                   item="username"
                   required
                   type="password"/>
        </div>

        <ValidationError :errors="errors" item="username"/>

        <div class="flex flex-row mt-2 center justify-between">
            <div>
                <label class="inline-flex items-center h-full" for="remember_me">
                    <Input id="remember_me"
                           v-model="data.remember"
                           type="checkbox"/>

                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>
            <div>
                <router-link class="underline static text-sm" to="forgot-password">Forgot your password?</router-link>
            </div>
        </div>

        <div class="flex flex-col items-center justify-end mt-4">
            <button :disabled="loggingIn" class="primary w-full p-2">
                {{ loggingIn ? "Logging you in..." : "Log in" }}
            </button>
        </div>
        <div class="mt-4 text-center">
            No account yet?
            <router-link class="static" to="register">Sign up Now!</router-link>
        </div>
    </form>
</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import Label from "@/components/Common/Form/Label.vue";
import Input from "@/components/Common/Form/Input.vue";
import {useAuth} from "@/store/authStore";
import ValidationError from "@/components/Common/Form/ValidationError.vue";

export default {
    name: "LoginPage",
    components: {ValidationError, Input, Label, Navbar},

    data() {
        return {
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
                const response = await useAuth().login(this.data);
                if (response.status === 200) {
                    if (this.$route.query.redirect) {
                        this.$router.push(this.$route.query.redirect);
                    } else {
                        this.$router.push({name: "home"});
                    }
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
