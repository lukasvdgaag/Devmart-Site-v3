<template>
    <Modal :id="id" @click="$emit('cancel')"
           @cancel="$emit('cancel')"
           @submit="$emit('submit')"
           ref="modal"
           :danger="dangerous"
           :confirm-text="confirmationText"
           :cancel-text="cancelText"
    >
        <div class="text-center">
            <svg aria-hidden="true" class="mx-auto mb-4 text-gray-400 w-14 h-14 dark:text-gray-200" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
            </svg>
            <h3 class="text-lg font-normal text-gray-500 dark:text-gray-400"
             :class="[description ? 'mb-2' : 'mb-5']">{{ title }}</h3>
            <MutedText class="text-sm" v-if="description" v-html="description"></MutedText>
        </div>
    </Modal>
</template>

<script>
import Modal from "@/components/Common/Modal/Modal.vue";
import MutedText from "@/components/Common/MutedText.vue";

export default {
    name: "ConfirmationModal",
    components: {MutedText, Modal},
    emits: ['submit', 'cancel'],

    mounted() {
        if (this.show) {
            this.$refs.modal.modal.show();
        }
    },

    props: {
        id: {
            type: String,
            required: true,
        },
        title: {
            type: String,
            required: true,
        },
        description: {
            type: String,
            required: false,
        },
        confirmationText: {
            type: String,
            default: "Yes, I'm sure"
        },
        cancelText: {
            type: String,
            default: "No, cancel"
        },
        dangerous: {
            type: Boolean,
            default: false,
        },
        show: {
            type: Boolean,
            default: false,
        },
    }
}
</script>

<style scoped>

</style>
