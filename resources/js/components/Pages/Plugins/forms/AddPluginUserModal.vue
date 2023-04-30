<template>

    <Modal id="pl-add-user"
           title="Add user"
           ref="modal"
           @submit="handleSubmit($event)"
           @cancel="clear"
           :disabled="fetcher.loading || !fetcher.canRequest()"
           :description="`You are about to give a user access to <span class='font-bold'>${plugin?.title}</span>. Enter the username below.`">
        <UserSelectInput class="mt-4" v-model="selectedUser" @update:modelValue="errors = {}; fetcher.query = $event.username" ref="userSelect"/>

        <ValidationError item="error" :errors="errors"/>
    </Modal>

</template>

<script>
import UserSelectInput from "@/components/Common/Form/UserSelectInput.vue";
import Modal from "@/components/Common/Modal/Modal.vue";

import ValidationError from "@/components/Common/Form/ValidationError.vue";
import PluginRepository from "@/services/PluginRepository";
import Fetchable from "@/models/fetchable/Fetchable";

export default {
    name: "AddPluginUserModal",
    components: {ValidationError, Modal, UserSelectInput},
    emits: ['userAdded'],

    methods: {
        async handleSubmit(event) {
            event.cancel();
            if (!this.selectedUser || !this.selectedUser.id) {
                this.errors = {error: ['Please select a user']};
                return;
            }

            this.errors = {};

            await this.fetcher.fetch(this);
        },
        clear() {
            this.selectedUser = null;
            this.fetcher.lastQuery = null;
            this.fetcher.query = '';
            this.$refs.userSelect.users = [];
            this.$refs.userSelect.query = '';
            this.errors = {};
        },
        async submitAccessGrantingRequest() {
            try {
                const res = await PluginRepository.grantPluginAccess(this.plugin.id, this.selectedUser.id);
                this.$emit('userAdded', {
                    user: this.selectedUser,
                    created: res.data
                });

                this.$refs.modal.modal.hide();
                this.clear();
            } catch (e) {
                this.errors = e.response.data;
            }
        }
    },

    data() {
        return {
            selectedUser: null,
            errors: {},
            fetcher: new Fetchable(this.submitAccessGrantingRequest, ''),
        }
    },

    props: {
        plugin: {
            type: Object,
            required: true,
        }
    },
}
</script>

<style scoped>

</style>
