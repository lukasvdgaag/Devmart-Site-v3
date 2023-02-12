<template>
    <div class="flex flex-col gap-2 w-full h-full">
        <h2 class="dark:text-gray-100">{{ pasteId ? 'Edit your Paste' : 'Create a new Paste' }}</h2>

        <form class="h-full">
            <div class="flex flex-col gap-1">
                <Label class="uppercase text-md">Title</Label>
                <Input v-model="paste.title"
                       :errors="errors"
                       class="w-full px-4 text-base"
                       item="title"
                       maxlength="50"
                       placeholder="Paste title"
                       tabindex="0"
                />
                <ValidationError :errors="errors" class="mt-0" item="title"/>
            </div>

            <div class="flex flex-col-reverse flex-wrap md:flex-col md:flex-nowrap gap-2 mt-2">
                <div class="flex gap-1 justify-between items-end flex-wrap ">
                    <Label class="uppercase text-md md:hidden">Options</Label>
                    <Label class="uppercase text-md hidden md:block">Content</Label>
                    <div class="flex gap-2 md:gap-1 w-full flex-wrap md:flex-nowrap">
                        <DropdownSelect
                            id="dd-lifetime"
                            v-model="selectedLifetime"
                            :items="lifetimeSelectItems"
                            class="w-full"
                            description="How long should this paste be available?"
                            header="Paste Lifetime"
                            placeholder="Lifetime"
                        />
                        <DropdownSelect
                            id="dd-visibility"
                            v-model="selectedVisibility"
                            :items="visibilitySelectItems"
                            class="w-full"
                            description="How should this paste be listed?"
                            header="Visibility"
                            placeholder="Visibility"
                        />
                        <DropdownSelect
                            id="dd-style"
                            v-model="selectedStyle"
                            :items="styleSelectItems"
                            class="w-full"
                            description="How should we style your paste?"
                            header="Styling"
                            placeholder="Style"
                        />
                        <button v-if="pasteId"
                                class="w-fit px-4 py-2 mt-0 rounded-md flex align-center gap-2 !bg-red-400 text-white hidden md:block" data-modal-target="confirm-delete-modal"
                                data-modal-toggle="confirm-delete-modal"
                                type="button">
                            <font-awesome-icon icon="fa-solid fa-trash-can"/>
                        </button>
                        <div class="flex gap-2 w-full">

                            <button v-if="pasteId"
                                    class="w-fit px-4 py-2 mt-0 rounded-md flex align-center gap-2 !bg-red-400 text-white md:hidden" data-modal-target="confirm-delete-modal"
                                    data-modal-toggle="confirm-delete-modal"
                                    type="button">
                                <font-awesome-icon icon="fa-solid fa-trash-can"/>
                                <span class="break-keep">Delete</span>
                            </button>
                            <button :disabled="loading"
                                    class="primary w-full p-2 mt-0 rounded-md flex align-center gap-2"
                                    type="submit"
                                    @click.prevent="uploadPaste">
                                <font-awesome-icon class="text-sm" icon="fa-solid fa-cloud-arrow-up"/>
                                <span class="break-keep">{{ loading ? "Uploading..." : this.pasteId ? "Update Paste" : "Upload Paste" }}</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="lg:mb-6">
                    <Label class="uppercase text-md mb-1 md:hidden">Content</Label>

                    <div :class="[ fullScreen ? 'fixed top-0 left-0 w-screen h-screen z-20': 'relative']" class="transition transition-all">
                        <Input ref="content"
                               v-model="paste.content"
                               :class="{'!rounded-none h-full': fullScreen}"
                               :errors="errors"
                               :is-textarea="true"
                               class="w-full min-h-[34rem] lg:h-full relative"
                               item="content"
                               placeholder="Start typing here..."
                               required
                               wrap="off"
                               @update:modelValue="delete this.errors.content"
                        />
                        <ValidationError :errors="errors" class="mt-0" item="content"/>

                        <button
                            ref="fullScreenButton"
                            :style="contentStyling"
                            class="top-2 absolute bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:bg-gray-600 py-2 px-3 rounded-md cursor-pointer transition select-none"
                            type="button"
                            @click="toggleFullScreen">
                            <font-awesome-icon v-if="fullScreen" class="text-gray-900 dark:text-gray-300" icon="fa-solid fa-down-left-and-up-right-to-center"/>
                            <font-awesome-icon v-else class="text-gray-900 dark:text-gray-300" icon="fa-solid fa-up-right-and-down-left-from-center"/>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <ConfirmationModal v-if="pasteId"
                       id="confirm-delete-modal"
                       title="Are you sure you want to delete this paste?"
                       @submit="deletePaste"/>
</template>

<script>
import Input from "@/components/Common/Input.vue";
import Paste from "@/models/rest/paste/Paste";
import Label from "@/components/Common/Label.vue";
import StickyFooter from "@/components/Common/StickyFooter.vue";
import DropdownSelect from "@/components/Common/Form/DropdownSelect.vue";
import {initDropdowns, initModals} from "flowbite";
import DropdownSelectItemModel from "@/models/DropdownSelectItemModel";
import PasteCreateBody from "@/models/rest/paste/PasteCreateBody";
import PastesRepository from "@/services/PastesRepository";
import ValidationError from "@/components/Common/ValidationError.vue";
import {useAuth} from "@/store/authStore";
import ConfirmationModal from "@/components/Common/Modal/ConfirmationModal.vue";

export default {
    name: "PasteCreatePage",
    components: {ConfirmationModal, ValidationError, DropdownSelect, StickyFooter, Label, Input},

    created() {
        if (this.pasteId) {
            this.fetchPaste();
        }
    },

    mounted() {
        initDropdowns();
        initModals();
    },

    computed: {
        contentStyling() {
            return {
                right: this.fullScreenBtnRight,
            };
        }
    },

    watch: {
        'paste.content'() {
            this.onContentScroll();
        }
    },

    methods: {
        async fetchPaste() {
            try {
                this.paste = await PastesRepository.fetchPaste(this.pasteId);
                if ((!this.paste.creator || this.paste.creator !== useAuth().user.id) && useAuth().user.role !== 'admin') {
                    this.paste = new Paste();
                    return this.$router.push({name: 'paste'});
                }

                this.selectedVisibility = this.visibilitySelectItems.find(item => {
                    console.log(item.value, this.paste.visibility);
                    return item.value === this.paste.visibility
                });
                this.selectedLifetime = this.lifetimeSelectItems.find(item => item.value === this.paste.lifetime);
                this.selectedStyle = this.styleSelectItems.find(item => item.value === this.paste.style);
                setTimeout(this.onContentScroll, 10);
            } catch (e) {
                return this.$router.push({name: 'paste'});
            }
        },
        checkForErrors() {
            this.errors = {};

            if (!this.paste.content || this.paste.content?.length === 0) {
                this.errors.content = ["The content field is required."];
            }

            return Object.keys(this.errors)?.length === 0;
        },
        async uploadPaste() {
            if (this.loading) return;

            this.loading = true;
            if (!this.checkForErrors()) {
                this.loading = false;
                return;
            }

            const body = new PasteCreateBody(
                this.paste.title,
                this.selectedStyle?.value,
                this.selectedVisibility?.value,
                this.selectedLifetime?.value,
                this.paste.content
            );

            try {
                // updating when editing, creating new otherwise.
                const res = this.pasteId ? await PastesRepository.updatePaste(this.pasteId, body) : await PastesRepository.createPaste(body);
                this.errors = {};
                if (res) {
                    this.$router.push({name: 'paste-info', params: {pasteId: res.name}});
                }
            } catch (e) {
                console.error(e);
                this.errors = e.response.data.errors;
            } finally {
                this.loading = false;
            }
        },
        async deletePaste() {
            if (this.loading) return;

            this.loading = true;
            try {
                if (await PastesRepository.deletePaste(this.pasteId)) {
                    this.paste = new Paste();
                    this.$router.push({name: 'paste'});
                }
            } catch (e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },
        toggleFullScreen() {
            this.fullScreen = !this.fullScreen;
            setTimeout(this.onContentScroll, 10);
        },
        onContentScroll() {
            if (this.$refs.content.$el.scrollHeight >= this.$refs.content.$el.clientHeight) {
                const scrollBarWidth = this.$refs.content.$el.offsetWidth - this.$refs.content.$el.clientWidth;
                this.fullScreenBtnRight = 'calc(0.5rem + ' + scrollBarWidth + 'px)';
            } else {
                this.fullScreenBtnRight = '0.5rem';
            }
        }
    },

    data() {
        return {
            paste: new Paste(),
            fullScreenBtnRight: '0.5rem',
            fullScreen: false,
            loading: false,
            selectedLifetime: null,
            selectedVisibility: null,
            selectedStyle: null,
            errors: {},
            lifetimeSelectItems: [
                new DropdownSelectItemModel('7 days', null, '7d'),
                new DropdownSelectItemModel('2 weeks', null, '2w'),
                new DropdownSelectItemModel('1 month', null, '1m', () => useAuth().loggedIn),
                new DropdownSelectItemModel('3 months', null, '3m', () => useAuth().loggedIn && useAuth().user.role === 'admin'),
                new DropdownSelectItemModel('Unlimited', 'This paste will never expire.', null, () => useAuth().loggedIn && useAuth().user.role === 'admin')
            ],
            visibilitySelectItems: [
                new DropdownSelectItemModel('Public', 'Anyone can view this paste.', 'PUBLIC'),
                new DropdownSelectItemModel('Unlisted', 'Only people with the link can view this paste.', 'UNLISTED', () => useAuth().loggedIn),
                new DropdownSelectItemModel('Private', 'Only you can view this paste.', 'PRIVATE', () => useAuth().loggedIn)
            ],
            styleSelectItems: [
                new DropdownSelectItemModel('Automatic', 'The style will be automatically detected.', null),
                new DropdownSelectItemModel('Java', null, 'Java'),
                new DropdownSelectItemModel('YAML', null, 'YAML'),
                new DropdownSelectItemModel('XML', null, 'XML'),
                new DropdownSelectItemModel('Error', null, 'less'),
                new DropdownSelectItemModel('JSON', null, 'JSON'),
                new DropdownSelectItemModel('HTML', null, 'HTML'),
                new DropdownSelectItemModel('JavaScript', null, 'JavaScript'),
                new DropdownSelectItemModel('PHP', null, 'PHP'),
                new DropdownSelectItemModel('No Style', "Don't apply any styling.", 'none')
            ]
        }
    },

    props: {
        pasteId: {
            type: [String, null],
            required: false
        }
    }
}
</script>

<style scoped>

</style>
