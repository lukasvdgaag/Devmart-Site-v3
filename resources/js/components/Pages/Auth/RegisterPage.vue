<template>
    <div class="flex flex-row h-full">
        <div class="w-full flex flex-col items-center m-0 p-0 h-full">
            <Navbar :background="true"/>
            <div class="flex flex-row h-full w-full justify-center items-center minus-header">
                <div class="d-grid mb-6 h-full">
                    <div class="col-span-6 flex flex-col align-center grid-full-small">
                        <div class="w-full sm:max-w-md pb-3">
                            <h1 class="mb-6 text-center">Create An Account.</h1>

                            <router-link to="register-discord"
                                         class="button primary flex flex-col align-center plain p-3">
                                <div class="flex flex-row gap-2">
                                    <font-awesome-icon :icon="['fab', 'discord']" class="icon light"/>
                                    <div class="text-base font-bold">Sign up with Discord</div>
                                </div>
                            </router-link>

                            <hr>

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
                                    <Label for="name" value="Name"/>

                                    <div class="flex flex-row gap-2">
                                        <Input id="name"
                                               class="block mt-1 w-full"
                                               type="text"
                                               name="name"
                                               maxlength="50"
                                               placeholder="First name"
                                               required/>
                                        <Input id="surname"
                                               class="block mt-1 w-full"
                                               type="text"
                                               name="surname"
                                               maxlength="50"
                                               placeholder="Last name"
                                               required/>
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
                        </div>
                    </div>
                    <div class="col-span-6 flex flex-col hide-small align-center">
                        <register-svg class="w-full"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar";
import Label from "@/components/Common/Label";
import Input from "@/components/Common/Input";
import RegisterSvg from "@/components/SVG/RegisterSvg";

export default {
    name: "RegisterPage",
    components: {RegisterSvg, Input, Label, Navbar},

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
