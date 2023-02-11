<template>
    <a href="/register-discord"
                 class="button rounded-md primary flex flex-row gap-2 align-center plain p-3">
        <font-awesome-icon :icon="['fab', 'discord']" class="icon light"/>
        <div class="text-base font-bold">Sign up with Discord</div>
    </a>

    <Hr/>

    <form method="POST">
        <div v-if="discordAuthenticated" class="alert-success">
            You are about to sign up with your Discord account. We will automatically
            verify and link your Discord to your GCNT account, so you don't have to.
        </div>

        <div>
            <Label for="username" value="Username"/>
            <Input id="username"
                   class="block mt-1 w-full"
                   type="text"
                   pattern="([A-Za-z][0-9]-_){50}"
                   :value="discordAuthenticated ? discordUsername : ''"
                   placeholder="Username"
                   maxlength="50"
                   required
                   autofocus
            />

            <div v-if="discordAuthenticated && discordUsername !== discordInfo.username"
                 class="text-base text-warning mt-1">
                Your Discord username didn't meet our standards, so we entered a modified
                version.
            </div>
        </div>

        <div class="mt-3">
            <Label for="email" value="Email"/>

            <Input id="email"
                   class="block mt-1 w-full"
                   type="email"
                   name="email"
                   maxlength="255"
                   :value="discordAuthenticated ? discordInfo.email : ''"
                   placeholder="Email"
                   required/>
        </div>

        <div class="mt-3">
            <Label for="password" value="Password"/>

            <div class="relative">
                <Input id="password" class="block mt-1 w-full"
                       type="password"
                       name="password"
                       placeholder="Password"
                       required autocomplete="new-password"/>

                <p class="muted text-sm mt-1 italic">The password must be at least 8 characters
                    long.</p>


                <div class="password-view-toggle">
                    <font-awesome-icon icon="eye-slash"/>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <label for="accept_tos" class="inline-flex items-center h-full">
                <Input id="accept_tos" type="checkbox"
                       class="rounded"
                       name="accept_tos" required/>

                <span class="ml-2 text-sm text-gray-600">I have read and agree to GCNT's
                                            <a class="static"
                                               href="/terms-of-service"
                                               target="_blank">Terms of Service</a>.</span>
            </label>
        </div>

        <div class="flex flex-col items-center justify-end mt-4">
            <button class="primary w-full p-2">
                Sign Up
            </button>
        </div>
        <div class="mt-4 text-center">
            Already got an account?
            <router-link to="login" class="static">Login Now!</router-link>
        </div>
    </form>
</template>

<script>
import Navbar from "@/components/Common/Navbar";
import Label from "@/components/Common/Label";
import Input from "@/components/Common/Input";
import Hr from "@/components/Common/Hr.vue";
import AuthPageLayout from "@/components/Pages/Auth/AuthPageLayout.vue";

export default {
    name: "RegisterPage",
    components: {AuthPageLayout, Hr, Input, Label, Navbar},

    data() {
        return {
            discordAuthenticated: false,
            discordUsername: 'GCNT',

            discordInfo: {
                username: 'GCNT'
            }
        }
    }
}
</script>

<style scoped>

</style>
