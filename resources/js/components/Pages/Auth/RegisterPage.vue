<template>
    <a class="button rounded-md primary flex flex-row gap-2 align-center plain p-3"
       href="/register-with-discord">
        <font-awesome-icon :icon="['fab', 'discord']" class="icon light"/>
        <div class="text-base font-bold">Sign up with Discord</div>
    </a>
    <ValidationError :errors="errors" item="discord"/>

    <Hr/>

    <Alert type="warning" class="mb-4">
        The username that is linked to your Discord account is already in use or contains non-alphanumeric characters.
        Please pick another one.
    </Alert>

    <form method="POST">
        <div>
            <Label for="username" value="Username"/>
            <Input id="username"
                   v-model="data.username"
                   :errors="errors"
                   autofocus
                   class="block mt-1 w-full"
                   item="username"
                   maxlength="50"
                   pattern="([A-Za-z][0-9]-_){50}"
                   placeholder="Username"
                   required
                   type="text"
            />
            <ValidationError :errors="errors" item="username"/>
        </div>

        <div class="mt-3">
            <Label for="email" value="Email"/>

            <Input id="email"
                   v-model="data.email"
                   class="block mt-1 w-full"
                   maxlength="255"
                   name="email"
                   placeholder="Email"
                   required
                   type="email"/>
        </div>

        <div class="mt-3">
            <Label for="password" value="Password"/>

            <div class="relative">
                <Input id="password" v-model="data.password"
                       autocomplete="new-password"
                       class="block mt-1 w-full"
                       name="password"
                       placeholder="Password"
                       required type="password"/>

                <p class="muted text-sm mt-1 italic">The password must be at least 8 characters
                    long.</p>

                <div class="password-view-toggle">
                    <font-awesome-icon icon="eye-slash"/>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <label class="inline-flex items-center h-full" for="accept_tos">
                <Input id="accept_tos" class="rounded"
                       name="accept_tos"
                       required type="checkbox"/>

                <span class="ml-2 text-sm text-gray-600">I have read and agree to Devmart's
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
            <router-link class="static" :to="{name: 'login'}">Login Now!</router-link>
        </div>
    </form>
</template>

<script>
import Label from "@/components/Common/Label.vue";
import Input from "@/components/Common/Input.vue";
import Hr from "@/components/Common/Hr.vue";
import ValidationError from "@/components/Common/ValidationError.vue";
import Alert from "@/components/Common/Alert.vue";

export default {
    name: "RegisterPage",
    components: {Alert, ValidationError, Hr, Input, Label},

    created() {
        let query = Object.assign({}, this.$route.query);

        if (query.email) {
            this.data.email = query.email;
            delete query.email;
        }
        if (query.username) {
            this.data.username = query.username;
            delete query.username;
        }

        if (query.discord_error) {
            this.discordErrorType = query.discord_error;

            if (query.discord_error !== 'username_in_use') this.errors.discord = [this.determineDiscordErrorMessage()]

            delete query.discord_error;
            this.$router.replace({query});
        }
    },

    data() {
        return {
            discordErrorType: 0,
            errors: {},
            data: {
                username: '',
                email: '',
                password: '',
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
        }
    }
}
</script>

<style scoped>

</style>
