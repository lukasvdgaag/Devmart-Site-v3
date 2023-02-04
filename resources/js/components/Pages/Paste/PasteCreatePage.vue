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
                       required
                />
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
                <div>
                    <Label class="uppercase text-md mb-1 md:hidden">Content</Label>

                    <Input class="w-full min-h-[34rem] lg:h-full relative lg:mb-6"
                           :is-textarea="true"
                           placeholder="Start typing here..."
                           required
                           v-model="paste.content"
                    />
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import Input from "@/components/Common/Input.vue";
import Paste from "@/models/rest/Paste";
import Label from "@/components/Common/Label.vue";
import StickyFooter from "@/components/Common/StickyFooter.vue";
import DropdownSelect from "@/components/Common/Form/DropdownSelect.vue";
import {initDropdowns} from "flowbite";
import DropdownSelectItemModel from "@/models/DropdownSelectItemModel";

export default {
    name: "PasteCreatePage",
    components: {DropdownSelect, StickyFooter, Label, Input},

    mounted() {
        initDropdowns();
    },

    methods: {
        async uploadPaste() {
            if (this.loading) return;

            this.loading = true;


        },
    },

    data() {
        return {
            paste: new Paste(),
            loading: false,
            selectedLifetime: null,
            selectedVisibility: null,
            selectedStyle: null,
            lifetimeSelectItems: [
                new DropdownSelectItemModel('7 days', null, '7d'),
                new DropdownSelectItemModel('2 weeks', null, '2w'),
                new DropdownSelectItemModel('1 month', null, '1m'),
                new DropdownSelectItemModel('3 months', null, '3m'),
                new DropdownSelectItemModel('Unlimited', 'This paste will never expire.', 'unlimited')
            ],
            visibilitySelectItems: [
                new DropdownSelectItemModel('Public', 'Anyone can view this paste.', 'public'),
                new DropdownSelectItemModel('Unlisted', 'Only people with the link can view this paste.', 'unlisted'),
                new DropdownSelectItemModel('Private', 'Only you can view this paste.', 'private')
            ],
            styleSelectItems: [
                new DropdownSelectItemModel('Automatic', 'The style will be automatically detected.', 'automatic'),
                new DropdownSelectItemModel('Java', null, 'java'),
                new DropdownSelectItemModel('YAML', null, 'yaml'),
                new DropdownSelectItemModel('Error', null, 'less'),
                new DropdownSelectItemModel('JSON', null, 'json'),
                new DropdownSelectItemModel('HTML', null, 'html'),
                new DropdownSelectItemModel('JavaScript', null, 'javascript'),
                new DropdownSelectItemModel('PHP', null, 'php'),
                new DropdownSelectItemModel('No Style', "Don't apply any styling.", 'none')
            ]
        }
    }
}
</script>

<style scoped>

</style>
