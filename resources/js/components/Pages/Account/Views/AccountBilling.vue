<template>
    <h1>Billing</h1>
    <hr>

    <Alert v-if="justUpdated" class="mb-4" icon="fa-circle-check" type="success">
        {{ isAdmin ? `You saved the account settings of ${user.username}` : "Your settings have been saved!" }}
    </Alert>
    <Alert v-else-if="Object.keys(errors).length !== 0" class="mb-4" icon="fa-circle-xmark" type="error">
        There was an error saving your settings. Please try again.
    </Alert>

    <div class="flex flex-row gap-2 items-center">
        <img src="/assets/img/discord-finance-bot-logo.png" alt="Discord Finance Bot Logo" class="icon big rounded-full">
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
                <Input class="block mt-1 w-full"
                       v-model="data.name"
                       :placeholder="user.name"
                       required
                       :errors="errors"
                       item="name"/>
                <ValidationError item="name" :errors="errors"/>

            </div>
            <div class="w-full">
                <Label class="font-bold" value="Last Name"/>
                <Input class="block mt-1 w-full"
                       v-model="data.surname"
                       :placeholder="user.surname"
                       required
                       :errors="errors"
                       item="surname"/>
                <ValidationError item="surname" :errors="errors"/>
            </div>
        </div>

        <div class="mt-3">
            <Label class="font-bold" value="Email Address"/>
            <Input class="block mt-1 w-full"
                   type="email"
                   v-model="data.email"
                   :placeholder="user.email"
                   required
                   :errors="errors"
                   item="email"/>
            <ValidationError item="email" :errors="errors"/>
        </div>

        <div class="mt-3">
            <Label class="font-bold" value="Business Name (Optional)"/>
            <Input class="block mt-1 w-full"
                   v-model="data.business"
                   placeholder="Business Name"
                   :errors="errors"
                   item="business"/>
            <ValidationError item="business" :errors="errors"/>
        </div>

        <StickyFooter>
            <button class="primary w-full md:w-2/4 p-2 mt-0" type="submit" :disabled="loading">
                {{ loading ? "Updating..." : "Save Settings" }}
            </button>
        </StickyFooter>
    </form>

</template>

<script>
import AdminEditingWarning from "@/components/Pages/Account/AdminEditingWarning.vue";
import Label from "@/components/Common/Label.vue";
import Input from "@/components/Common/Input.vue";
import StickyFooter from "@/components/Common/StickyFooter.vue";
import UserRepository from "@/services/UserRepository";
import ValidationError from "@/components/Common/ValidationError.vue";
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
