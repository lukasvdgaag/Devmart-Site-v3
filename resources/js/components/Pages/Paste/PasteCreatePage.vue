<template>
    <div class="flex flex-col gap-2 w-full h-full">
        <h2 class="dark:text-gray-100">Create a new Paste</h2>

        <form class="h-full">
            <div class="flex flex-col gap-1">
                <Label class="uppercase text-md">Title</Label>
                <Input class="w-full bg-gray-75 px-4 text-base"
                       tabindex="0"
                       placeholder="Paste title"
                       v-model="paste.title"
                       :errors="errors"
                       item="title"
                       required
                />
                <ValidationError item="title" :errors="errors" class="mt-0" />
            </div>

            <div class="flex flex-col-reverse flex-wrap md:flex-col md:flex-nowrap gap-2 mt-2">
                <div class="flex gap-1 justify-between items-end flex-wrap ">
                    <Label class="uppercase text-md md:hidden">Options</Label>
                    <Label class="uppercase text-md hidden md:block">Content</Label>
                    <div class="flex gap-2 md:gap-1 w-full flex-wrap md:flex-nowrap ">
                        <DropdownSelect
                            placeholder="Lifetime"
                            id="dd-lifetime"
                            :items="lifetimeSelectItems"
                            header="Paste Lifetime"
                            description="How long should this paste be available?"
                            v-model="selectedLifetime"
                            class="w-full"
                        />
                        <DropdownSelect
                            placeholder="Visibility"
                            id="dd-visibility"
                            :items="visibilitySelectItems"
                            header="Visibility"
                            description="How should this paste be listed?"
                            v-model="selectedVisibility"
                            class="w-full"
                        />
                        <DropdownSelect
                            placeholder="Style"
                            id="dd-style"
                            :items="styleSelectItems"
                            header="Styling"
                            description="How should we style your paste?"
                            v-model="selectedStyle"
                            class="w-full"
                        />
                        <button class="primary w-full p-2 mt-0 rounded-md flex align-center gap-2"
                                :disabled="loading"
                                @click.prevent="uploadPaste"
                                type="submit">
                            <font-awesome-icon icon="fa-solid fa-cloud-arrow-up" class="text-sm"/>
                            <span class="break-keep">{{ loading ? "Uploading..." : "Upload Paste" }}</span>
                        </button>
                    </div>
                </div>
                <div class="lg:mb-6">
                    <Label class="uppercase text-md mb-1 md:hidden">Content</Label>

                    <Input class="w-full min-h-[34rem] lg:h-full relative"
                           :is-textarea="true"
                           placeholder="Start typing here..."
                           required
                           v-model="paste.content"
                           :errors="errors"
                           @update:modelValue="delete this.errors.content"
                           item="content"
                    />
                    <ValidationError item="content" :errors="errors" class="mt-0"/>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import Input from "@/components/Common/Input.vue";
import Paste from "@/models/rest/paste/Paste";
import Label from "@/components/Common/Label.vue";
import StickyFooter from "@/components/Common/StickyFooter.vue";
import DropdownSelect from "@/components/Common/Form/DropdownSelect.vue";
import {initDropdowns} from "flowbite";
import DropdownSelectItemModel from "@/models/DropdownSelectItemModel";
import PasteCreateBody from "@/models/rest/paste/PasteCreateBody";
import PastesRepository from "@/services/PastesRepository";
import ValidationError from "@/components/Common/ValidationError.vue";

export default {
    name: "PasteCreatePage",
    components: {ValidationError, DropdownSelect, StickyFooter, Label, Input},

    mounted() {
        initDropdowns();
    },

    methods: {
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
                console.log('we have errors!', this.errors);
                this.loading = false;
                return;
            }

            const body = new PasteCreateBody(this.paste.title,
                this.selectedStyle?.value,
                this.selectedVisibility?.value,
                this.selectedLifetime?.value,
                this.paste.content
            );

            try {
                const res = await PastesRepository.createPaste(body);
                this.errors = {};
                if (res) {
                    this.$router.push({name: 'paste-info', params: {pasteId: res.name}});
                }
            } catch (e) {
                this.errors = e.response.data.errors;
            } finally {
                this.loading = false;
            }
        },
    },

    data() {
        return {
            paste: new Paste(),
            loading: false,
            selectedLifetime: null,
            selectedVisibility: null,
            selectedStyle: null,
            errors: {},
            lifetimeSelectItems: [
                new DropdownSelectItemModel('7 days', null, '7d'),
                new DropdownSelectItemModel('2 weeks', null, '2w'),
                new DropdownSelectItemModel('1 month', null, '1m'),
                new DropdownSelectItemModel('3 months', null, '3m'),
                new DropdownSelectItemModel('Unlimited', 'This paste will never expire.', null)
            ],
            visibilitySelectItems: [
                new DropdownSelectItemModel('Public', 'Anyone can view this paste.', 'PUBLIC'),
                new DropdownSelectItemModel('Unlisted', 'Only people with the link can view this paste.', 'UNLISTED'),
                new DropdownSelectItemModel('Private', 'Only you can view this paste.', 'PRIVATE')
            ],
            styleSelectItems: [
                new DropdownSelectItemModel('Automatic', 'The style will be automatically detected.', null),
                new DropdownSelectItemModel('Java', null, 'Java'),
                new DropdownSelectItemModel('YAML', null, 'YAML'),
                new DropdownSelectItemModel('Error', null, 'less'),
                new DropdownSelectItemModel('JSON', null, 'JSON'),
                new DropdownSelectItemModel('HTML', null, 'HTML'),
                new DropdownSelectItemModel('JavaScript', null, 'JavaScript'),
                new DropdownSelectItemModel('PHP', null, 'PHP'),
                new DropdownSelectItemModel('No Style', "Don't apply any styling.", 'none')
            ]
        }
    }
}
</script>

<style scoped>

</style>
