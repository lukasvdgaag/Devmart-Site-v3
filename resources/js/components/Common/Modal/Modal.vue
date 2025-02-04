<template>
    <div :id="id" tabindex="-1" aria-hidden="true"
         ref="modal"
         class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full"
         @click="$emit('cancel')"
    >
        <div class="relative w-full max-w-md max-h-full" @click.stop>
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button"
                        class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-800 dark:hover:text-white"
                        @click="$emit('cancel')"
                        :data-modal-hide="id">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <!-- Modal header -->
                <div v-if="title" class="px-6 py-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-base font-semibold text-gray-900 lg:text-xl dark:text-white">
                        {{ title }}
                    </h3>
                </div>
                <!-- Modal body -->
                <div class="p-6">
                    <p v-if="description" class="text-sm" v-html="description"></p>

                    <slot/>

                    <div class="mt-4 flex gap-2 justify-center items-center">
                        <button v-if="danger"
                                :disabled="disabled"
                                :data-modal-hide="id"
                                class="text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium
                            rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
                                :class="{'opacity-50 cursor-not-allowed': disabled}"
                                type="button"
                                @click="handleCancellableEvent($event, 'submit')">
                            {{ confirmText }}
                        </button>
                        <button v-if="cancelButton"
                                :data-modal-hide="id"
                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200
                    rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500
                    dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600" type="button"
                                @click="handleCancellableEvent($event, 'cancel')">{{ cancelText }}
                        </button>
                        <button v-if="!danger"
                                :disabled="disabled"
                                class="text-white bg-primary-500 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
                                :class="[disabled ? 'opacity-50' : 'hover:bg-primary-600 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:focus:ring-primary-800']"
                                type="button"
                                @click="handleCancellableEvent($event, 'submit')">
                            {{ confirmText }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {initModals, Modal as FlowbiteModal} from "flowbite";
import CancellableEvent from "@/models/CancellableEvent";

export default {
    name: "Modal",
    emits: ['cancel', 'submit'],

    mounted() {
        this.modal = new FlowbiteModal(this.$refs.modal);

        document.querySelectorAll(`[data-modal-toggle='${this.id}']`).forEach((e) => {
            e.addEventListener('click', () => this.modal.toggle());
        });
        document.querySelectorAll(`[data-modal-hide='${this.id}']`).forEach((e) => {
            e.addEventListener('click', () => {
                if (this.modal.isVisible) this.modal.hide()
            });
        });
        document.querySelectorAll(`[data-modal-show='${this.id}']`).forEach((e) => {
            e.addEventListener('click', () => {
                if (this.modal.isHidden) this.modal.show()
            });
        });
    },

    methods: {
        handleCancellableEvent(event, eventName) {
            let cancellable = new CancellableEvent();

            this.$emit(eventName, cancellable);

            if (!cancellable.isCancelled()) {
                if (this.modal.isVisible) this.modal.hide();
            }
        },
    },

    data() {
        return {
            modal: null,
        }
    },

    props: {
        id: {
            type: String,
            required: true,
        },
        title: {
            type: String,
            required: false,
        },
        description: {
            type: String,
            required: false,
        },
        cancelButton: {
            type: Boolean,
            default: true,
        },
        cancelText: {
            type: String,
            default: 'Cancel',
        },
        danger: {
            type: Boolean,
            default: false,
        },
        confirmText: {
            type: String,
            default: 'Confirm',
        },
        disabled: {
            type: Boolean,
            default: false,
        }
    }
}
</script>

<style scoped>

</style>
