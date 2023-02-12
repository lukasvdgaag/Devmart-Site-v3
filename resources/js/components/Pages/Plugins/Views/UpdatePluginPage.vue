<template>
    <form @submit.prevent="updatePlugin">
        <FormSection>
            <FormRow label="Version">
                <Input v-model="updateInfo.version"
                       :errors="errors"
                       :placeholder="plugin?.latest_update?.version ?? '1.0.0'"
                       class="block w-full"
                       item="version"
                       name="version"
                       required
                       @input="checkForStringInputError(updateInfo.version, 'version')"/>
                <ValidationError :errors="errors" item="version"/>
            </FormRow>
            <FormRow label="Beta number">
                <Input v-model="updateInfo.beta_number"
                       :placeholder="plugin?.latest_update?.beta_number ?? '0'"
                       class="block w-full"
                       min="0"
                       name="beta_number"
                       type="number"
                />
                <MutedText>
                    Enter a number higher than zero to indicate that this is a beta version.
                </MutedText>
            </FormRow>
            <FormRow label="Title">
                <Input v-model="updateInfo.title"
                       :errors="errors"
                       class="block w-full"
                       item="title"
                       name="title"
                       placeholder="Title"
                       required
                       @input="checkForStringInputError(updateInfo.title, 'title')"/>
                <ValidationError :errors="errors" item="title"/>
            </FormRow>
        </FormSection>
        <FormSection>
            <FormRow label="File">
                <FileInput v-model="fileName"
                           :accept="allowedFileTypes"
                           :errors="errors"
                           item="file"
                           required
                           @upload="handleFileUpload($event)"/>
                <ValidationError :errors="errors" item="file"/>
            </FormRow>
        </FormSection>
        <FormSection>
            <FormRow label="Changelog">
                <Input v-model="updateInfo.changelog"
                       :errors="errors"
                       :is-textarea="true"
                       class="block w-full"
                       item="changelog"
                       name="changelog"
                       placeholder="Full changelog..."
                       required
                       @input="checkForStringInputError(updateInfo.changelog, 'changelog')"/>
                <ValidationError :errors="errors" item="changelog"/>
                <MutedText>
                    The complete changelog for this update. Include all changes, fixes, and
                    improvements. This will be displayed on the update page.
                </MutedText>
            </FormRow>
            <FormRow label="Shortened Changelog">
                <Input v-model="updateInfo.short_changelog"
                       :is-textarea="true"
                       class="block w-full"
                       name="changelog"
                       placeholder="Short changelog..."
                />
                <MutedText>
                    A shortened, compact version of the changelog. Include only important
                    changes, fixes, and improvements and don't make it too long.
                    This will be sent as part of the Discord update announcement.
                    <br>
                    <span class="italic">Leave empty to prevent the announcement from being sent.</span>
                </MutedText>
            </FormRow>
        </FormSection>
        <StickyFooter class="!mt-2">
            <button :class="{'bg-opacity-50': isUpdating}" :disabled="isUpdating" class="primary w-full md:w-2/4 p-2 mt-0" type="submit">
                {{ isUpdating ? 'Updating...' : 'Update' }}
            </button>
            <ValidationError :errors="errors" item="general"/>
        </StickyFooter>
    </form>
</template>

<script>
import FormSection from "@/components/Pages/Plugins/forms/FormSection.vue";
import FormRow from "@/components/Pages/Plugins/forms/FormRow.vue";
import PluginRepository from "@/services/PluginRepository";
import FileInput from "@/components/Common/FileInput.vue";
import MutedText from "@/components/Common/MutedText.vue";
import StickyFooter from "@/components/Common/StickyFooter.vue";
import Input from "@/components/Common/Form/Input.vue";
import ValidationError from "@/components/Common/Form/ValidationError.vue";
import Plugin from "@/models/rest/Plugin";

export default {
    name: "UpdatePluginPage",
    components: {ValidationError, Input, StickyFooter, MutedText, FileInput, FormRow, FormSection},

    data() {
        return {
            file: null,
            fileName: null,
            isUpdating: false,
            updateInfo: {
                version: '',
                beta_number: 0,
                title: '',
                changelog: '',
                short_changelog: '',
            },
            allowedFileTypes: '.zip,.jar,.tar,.sk,.rar',
            errors: {},
        }
    },

    props: {
        pluginId: {
            type: String
        },
        plugin: {
            type: Plugin,
        }
    },

    methods: {
        checkForStringInputError(value, key) {
            if (!value || value.length === 0) {
                this.errors[key] = ['This field is required.'];
            } else {
                delete this.errors[key];
            }
        },
        checkErrors() {
            this.errors = {};
            this.checkForStringInputError(this.updateInfo.version, 'version');
            this.checkForStringInputError(this.updateInfo.title, 'title');
            this.checkForStringInputError(this.updateInfo.changelog, 'changelog');
            this.handleFileUpload(this.file);

            return this.errors === {};
        },
        handleFileUpload(e) {
            this.file = e ?? null

            if (!this.file) {
                this.errors.file = ["Please select a file to upload."];
            } else if (!this.allowedFileTypes.split(',').includes('.' + this.file.name.split('.').pop())) {
                this.errors.file = ["Wrong file type. Allowed file types are: " + this.allowedFileTypes];
            }
        },
        async updatePlugin() {
            if (this.checkErrors()) return;
            this.isUpdating = true;

            let formData = new FormData();
            for (const key of Object.keys(this.updateInfo)) {
                formData.set(key, this.updateInfo[key]);
            }
            formData.set('file', this.file);

            try {
                const response = await PluginRepository.updatePlugin(this.pluginId, formData);
                this.isUpdating = false;
                if (response.status === 201) {
                    this.$router.push({name: "plugin-overview", params: {pluginId: this.pluginId}});
                } else {
                    this.errors = {'general': ['No 200 response code received.']};
                }
            } catch (e) {
                this.errors = e.response.data.errors ?? {'general': ['An unknown error occurred.']};
            }
            this.isUpdating = false;
        },
    },
}
</script>

<style scoped>

</style>
