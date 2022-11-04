<template>
    <div class="flex flex-row h-100">
        <div class="w-100 flex align-items-center m-0 p-0 h-100">
            <Navbar :background="true"/>
            <AuthCard class="w-100">
                <form>
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
                               v-bind="data.username"
                               class="block mt-1 w-full"
                               type="text"
                               name="username"
                               required
                               autofocus/>
                    </div>
                    <div class="mt-4">
                        <Label for="password" value="Username or Email"/>
                        <Input id="password"
                               v-bind="data.password"
                               class="block mt-1 w-full"
                               type="password"
                               name="password"
                               required
                               autocomplete="current-password"/>
                    </div>
                    <div class="flex flex-row mt-2 center justify-space-between">
                        <div>
                            <label for="remember_me" class="inline-flex items-center h-100">
                                <Input id="remember_me"
                                       v-bind="data.remember"
                                       type="checkbox"
                                       class="rounded"
                                       name="remember"/>

                                <span class="ml-2 text-sm text-gray-600">Remember me</span>
                            </label>
                        </div>
                        <div>
                            <router-link to="forgot-password" class="underline static size-14">Forgot your password?</router-link>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button class="primary w-100 p-2">
                            Log in
                        </button>
                    </div>

                    <div class="mt-4 text-center">
                        Not registered yet? <router-link to="register" class="static">Create an account!</router-link>
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

export default {
    name: "LoginPage",
    components: {Input, Label, AuthCard, Navbar},

    data() {
        return {
            data: {
                username: '',
                password: '',
                remember: false
            }
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
