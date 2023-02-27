<template>
    <h1>Billing</h1>
    <hr/>

    <Alert v-if="justUpdated" class="mb-4 font-medium" icon="fa-circle-check" type="success">
        {{ isAdmin ? `You saved the account settings of ${user.username}` : "Your settings have been saved!" }}
    </Alert>
    <Alert v-else-if="Object.keys(errors).length !== 0" class="mb-4 font-medium" icon="fa-circle-xmark" type="error">
        There was an error saving your settings. Please try again.
    </Alert>

    <div class="flex flex-row gap-2 items-center">
        <img alt="Discord Finance Bot Logo" class="icon big rounded-full" src="/assets/img/discord-finance-bot-logo.png">
        <h2>PayPal Information</h2>
    </div>

    <div class="mt-2 text-base">
        Additional PayPal account information that is used for invoicing custom services.
        Your account details will be used by default, but you can override these details below if
        they do not match your PayPal account details. Please enter the exact PayPal details.
    </div>

    <form @submit.prevent="savePayPalInformation">
        <div class="flex flex-row gap-4 mt-3">
            <div class="w-full">
                <Label class="font-bold" value="First Name"/>
                <Input v-model="data.name"
                       :errors="errors"
                       :placeholder="user.name"
                       class="block mt-1 w-full"
                       item="name"
                       required/>
                <ValidationError :errors="errors" item="name"/>

            </div>
            <div class="w-full">
                <Label class="font-bold" value="Last Name"/>
                <Input v-model="data.surname"
                       :errors="errors"
                       :placeholder="user.surname"
                       class="block mt-1 w-full"
                       item="surname"
                       required/>
                <ValidationError :errors="errors" item="surname"/>
            </div>
        </div>

        <div class="mt-3">
            <Label class="font-bold" value="Email Address"/>
            <Input v-model="data.email"
                   :errors="errors"
                   :placeholder="user.email"
                   class="block mt-1 w-full"
                   item="email"
                   required
                   type="email"/>
            <ValidationError :errors="errors" item="email"/>
        </div>

        <div class="mt-3">
            <Label class="font-bold" value="Business Name (Optional)"/>
            <Input v-model="data.business"
                   :errors="errors"
                   class="block mt-1 w-full"
                   item="business"
                   placeholder="Business Name"/>
            <ValidationError :errors="errors" item="business"/>
        </div>

        <StickyFooter>
            <button :disabled="loading" class="primary w-full md:w-2/4 p-2 mt-0" type="submit">
                {{ loading ? "Updating..." : "Save Settings" }}
            </button>
        </StickyFooter>
    </form>

</template>

<script>
import AdminEditingWarning from "@/components/Pages/Account/AdminEditingWarning.vue";
import Label from "@/components/Common/Form/Label.vue";
import Input from "@/components/Common/Form/Input.vue";
import StickyFooter from "@/components/Common/StickyFooter.vue";
import UserRepository from "@/services/UserRepository";
import ValidationError from "@/components/Common/Form/ValidationError.vue";
import Alert from "@/components/Common/Alert.vue";

export default {
    name: "AccountBilling",
    components: {Alert, ValidationError, StickyFooter, Input, Label, AdminEditingWarning},

    created() {
        this.loadPayPalInformation();
    },

    data() {
        return {
            errors: {},
            loading: false,
            justUpdated: false,
            data: {
                name: "",
                surname: "",
                email: "",
                business: "",
            }
        }
    },

    methods: {
        async loadPayPalInformation() {
            this.loading = true;
            try {
                let response = await UserRepository.fetchUserPayPalById(this.userId);
                if (response.status === 200) {
                    this.data = response.data.paypal_information;
                }
            } catch (e) {
                console.error(e);
            }
            this.loading = false;
        },
        async savePayPalInformation() {
            if (this.loading) return;
            this.loading = true;
            this.justUpdated = false;

            try {
                let response = await UserRepository.updateUserPayPalById(this.userId, this.data);
                if (response.status === 200) {
                    this.data = response.data.paypal_information;

                    this.errors = {};
                    this.justUpdated = true;
                }
            } catch (e) {
                if (e.response) {
                    this.errors = e.response.data.errors;
                } else {
                    this.errors = {}
                }
            }
            this.loading = false;
        }
    },

    props: ['user', 'userId', 'userLoading', 'isAdmin']
}
</script>

<style scoped>

</style>
