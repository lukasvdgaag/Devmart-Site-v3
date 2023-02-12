<template>
    <a class="button rounded-md primary flex flex-row gap-2 align-center plain p-3"
       href="/register-with-discord">
        <font-awesome-icon :icon="['fab', 'discord']" class="icon light"/>
        <div class="text-base font-bold">Sign up with Discord</div>
    </a>
    <ValidationError :errors="errors" item="discord"/>

    <Hr/>

    <Alert v-if="discordErrorType === 'username_in_use'" type="warning" class="mb-4">
        The username that is linked to your Discord account is already in use or contains non-alphanumeric characters.
        Please pick another one.
    </Alert>

    <form @submit.prevent="register">
        <div class="relative">
            <Label for="username" value="Username"/>
            <Input id="username"
                   v-model="data.username"
                   @update:modelValue="checkUsernameValidity"
                   :errors="errors"
                   autofocus
                   class="block mt-1 w-full"
                   item="username"
                   maxlength="50"
                   placeholder="Username"
                   required
                   ref="usernameInput"
                   type="text"
            />
            <ValidationError :errors="errors" item="username"/>

            <!--            <div id="popover-password" ref="usernamePopover" role="tooltip" class="absolute z-10 invisible inline-block text-sm font-light text-gray-500-->
            <!--            transition-opacity duration-300 bg-gray-200 border border-gray-200 rounded-lg shadow-sm opacity-0 dark:bg-gray-800 dark:border-gray-600-->
            <!--             dark:text-gray-400 p-3 w-full">-->
            <!--                <div class="font-semibold">Username requirements</div>-->
            <!--                <div data-popper-arrow></div>-->
            <!--            </div>-->
            <InputRequirementList :value="data.username" ref="usernameReqs" :requirements="['min:3', 'type:username']"/>

        </div>

        <div class="mt-3">
            <Label for="email" value="Email"/>

            <Input id="email"
                   v-model="data.email"
                   class="block mt-1 w-full"
                   @update:modelValue="delete this.errors.email"
                   maxlength="255"
                   name="email"
                   placeholder="Email"
                   required
                   item="email"
                   :errors="errors"
                   type="email"/>

            <ValidationError :errors="errors" item="email"/>
        </div>

        <div class="mt-3">
            <Label for="password" value="Password"/>

            <PasswordInput v-model="data.password" item="password" :errors="errors"
                           @update:modelValue="delete this.errors.password"
            />

            <ValidationError :errors="errors" item="password"/>
            <InputRequirementList :value="data.password" :requirements="['min:8']" ref="passwordReqs"/>
        </div>

        <div class="mt-3">
            <label class="inline-flex items-center h-full" for="accept_tos">
                <Input id="accept_tos" class="rounded"
                       name="accept_tos"
                       v-model="data.accept_tos"
                       required type="checkbox"/>

                <span class="ml-2 text-sm text-gray-600">I have read and agree to Devmart's
                                            <a class="static"
                                               href="/terms-of-service"
                                               target="_blank">Terms of Service</a>.</span>
            </label>
        </div>

        <div class="flex flex-col items-center justify-end mt-4">
            <button class="primary w-full p-2" :disabled="loading">
                {{ loading ? 'Signing up...' : 'Sign Up' }}
            </button>

            <ValidationError :errors="errors" item="general"/>
        </div>
        <div class="mt-4 text-center">
            Already got an account?
            <router-link class="static" :to="{name: 'login'}">Login Now!</router-link>
        </div>
    </form>
</template>

<script>
import Label from "@/components/Common/Form/Label.vue";
import Input from "@/components/Common/Form/Input.vue";
import Hr from "@/components/Common/Hr.vue";
import ValidationError from "@/components/Common/Form/ValidationError.vue";
import Alert from "@/components/Common/Alert.vue";
import PasswordInput from "@/components/Common/Form/PasswordInput.vue";
import InputRequirementList from "@/components/Common/Form/InputRequirementList.vue";
import {initPopovers} from "flowbite";
import AuthService from "@/services/AuthService.js";
import {useAuth} from "@/store/authStore.js";

export default {
    name: "RegisterPage",
    components: {InputRequirementList, PasswordInput, Alert, ValidationError, Hr, Input, Label},

    created() {
        let query = Object.assign({}, this.$route.query);

        if (query.email) {
            this.data.email = query.email;
        }
        if (query.username) {
            this.data.username = query.username;
        }

        if (query.discord_error) {
            this.discordErrorType = query.discord_error;
        }
    },

    mounted() {
        initPopovers();

        let query = Object.assign({}, this.$route.query);
        if (query.discord_error) {
            if (query.discord_error === 'username_in_use') {
                this.errors.username = [this.$refs.usernameReqs.metAllRequirements() ? 'This username is taken.' : 'This username is invalid.'];
            } else {
                this.errors.discord = [this.determineDiscordErrorMessage()]
            }
        }
    },

    data() {
        return {
            loading: false,
            discordErrorType: null,
            errors: {},
            data: {
                username: '',
                email: '',
                password: '',
                accept_tos: false,
            }
        }
    },

    methods: {
        determineDiscordErrorMessage() {
            switch (this.discordErrorType) {
                case 'invalid_request':
                    return 'No code was provided or the code was invalid.';
                case 'email_not_verified':
                    return 'The email address associated with your Discord account is not verified.';
                case 'email_in_use':
                    return 'The email address associated with your Discord account is already in use.';
                case 'discord_in_use':
                    return 'The Discord account is already in use.';
                case 'creation_failed':
                    return 'The account could not be created.';
                case 'verification_failed':
                    return 'We failed to verify your authentication request.';
                default:
                    return 'An unknown error occurred.';
            }
        },
        checkUsernameValidity() {
            this.data.username = this.data.username.replaceAll(' ', '_');
            delete this.errors.username;
        },
        checkErrors() {
            if (!this.$refs.usernameReqs.metAllRequirements()) {
                this.errors.username = ['Please enter a valid username.'];
            }
            if (!this.$refs.passwordReqs.metAllRequirements()) {
                this.errors.password = ['Please enter a valid password.'];
            }

            return Object.keys(this.errors).length === 0;
        },
        async register() {
            this.loading = true;
            if (!this.checkErrors()) {
                this.loading = false;
                return;
            }

            try {
                const res = await AuthService.registerUser(this.data, this.$route.query.discord_auth_token);
                if (res && res.status === 201) {
                    useAuth().user = res.data;
                    useAuth().loaded = true;

                    this.$router.push({name: 'home'});
                } else {
                    this.errors = {general: ['An unknown error occurred.']};
                }
            } catch (res) {
                this.errors = res.response.data?.errors ?? {};
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<style scoped>

</style>
