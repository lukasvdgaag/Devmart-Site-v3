<template>
    <div class="flex flex-col gap-2 w-full h-full">
        <h2 class="dark:text-gray-100">Create a new Paste</h2>

        <form>
            <div class="flex flex-col gap-1">
                <Label class="uppercase text-md">Title</Label>
                <Input class="w-full bg-gray-75 px-4 text-base"
                       tabindex="0"
                       v-model="paste.title"
                />
            </div>

            <div class="flex flex-col gap-1 mt-2 h-full">
                <div class="flex gap-1 justify-between items-end">
                    <Label class="uppercase text-md">Content</Label>
                    <div class="flex gap-1">
                        <DropdownSelect
                            placeholder="Lifetime"
                            id="dd-lifetime"
                            :items="lifetimeSelectItems"
                            header="Paste Lifetime"
                            description="How long should this paste be available?"
                            v-model="selectedLifetime"
                        />
                        <DropdownSelect
                            placeholder="Visibility"
                            id="dd-visibility"
                            :items="visibilitySelectItems"
                            header="Visibility"
                            description="How should this paste be listed?"
                            v-model="selectedVisibility"
                        />
                        <DropdownSelect
                            placeholder="Style"
                            id="dd-style"
                            :items="styleSelectItems"
                            header="Styling"
                            description="How should we style your paste?"
                            v-model="selectedStyle"
                        />
                    </div>
                </div>
                <Input class="w-full bg-gray-75 h-full min-h-[300px]"
                       :is-textarea="true"
                       v-model="paste.content"
                />
            </div>

            <StickyFooter>
                <button class="primary w-full md:w-2/4 p-2 mt-0"
                        :disabled="loading"
                        type="submit">{{ loading ? "Uploading..." : "Upload Paste" }}
                </button>
            </StickyFooter>
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
