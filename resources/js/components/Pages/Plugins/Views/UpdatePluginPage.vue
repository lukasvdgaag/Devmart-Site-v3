<template>

    <PluginActionPageLayout title="Update Plugin" :plugin="plugin">
        <form @submit.prevent="updatePlugin">
            <FormSection>
                <FormRow label="Version">
                    <Input v-model="updateInfo.version"
                           @input="checkForStringInputError(updateInfo.version, 'version')"
                           class="block w-full"
                           name="version"
                           :placeholder="plugin?.latest_update?.version ?? '1.0.0'"
                           :errors="errors"
                           item="version"
                           required/>
                    <ValidationError item="version" :errors="errors"/>
                </FormRow>
                <FormRow label="Beta number">
                    <Input v-model="updateInfo.beta_number"
                           class="block w-full"
                           name="beta_number"
                           type="number"
                           min="0"
                           :placeholder="plugin?.latest_update?.beta_number ?? '0'"
                    />
                    <MutedText>
                        Enter a number higher than zero to indicate that this is a beta version.
                    </MutedText>
                </FormRow>
                <FormRow label="Title">
                    <Input v-model="updateInfo.title"
                           class="block w-full"
                           name="title"
                           placeholder="Title"
                           @input="checkForStringInputError(updateInfo.title, 'title')"
                           :errors="errors"
                           item="title"
                           required/>
                    <ValidationError item="title" :errors="errors"/>
                </FormRow>
            </FormSection>
            <FormSection>
                <FormRow label="File">
                    <FileInput @upload="handleFileUpload($event)"
                               v-model="fileName"
                               :accept="allowedFileTypes"
                               required
                               :errors="errors"
                               item="file"/>
                    <ValidationError item="file" :errors="errors"/>
                </FormRow>
            </FormSection>
            <FormSection>
                <FormRow label="Changelog">
                    <Input v-model="updateInfo.changelog"
                           class="block w-full"
                           :is-textarea="true"
                           name="changelog"
                           placeholder="Full changelog..."
                           @input="checkForStringInputError(updateInfo.changelog, 'changelog')"
                           :errors="errors"
                           item="changelog"
                           required/>
                    <ValidationError item="changelog" :errors="errors"/>
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
                <button class="primary w-full md:w-2/4 p-2 mt-0" :class="{'bg-opacity-50': isUpdating}" :disabled="isUpdating" type="submit">
                    {{ isUpdating ? 'Updating...' : 'Update' }}
                </button>
                <ValidationError item="general" :errors="errors"/>
            </StickyFooter>
        </form>
    </PluginActionPageLayout>
</template>

<script>
import FormSection from "@/components/Pages/Plugins/forms/FormSection.vue";
import FormRow from "@/components/Pages/Plugins/forms/FormRow.vue";
import PluginRepository from "@/services/PluginRepository";
import FileInput from "@/components/Common/FileInput.vue";
import MutedText from "@/components/Common/MutedText.vue";
import StickyFooter from "@/components/Common/StickyFooter.vue";
import PluginActionPageLayout from "@/components/Pages/Plugins/PluginActionPageLayout.vue";
import Input from "@/components/Common/Input.vue";
import ValidationError from "@/components/Common/ValidationError.vue";

export default {
    name: "UpdatePluginPage",
    components: {ValidationError, Input, PluginActionPageLayout, StickyFooter, MutedText, FileInput, FormRow, FormSection},

    async created() {
        const [perms, plugin] = await Promise.all([
            this.fetchPermissions(),
            this.fetchPluginData()
        ]);

        if (!plugin?.data || !perms?.data || !perms?.data?.modify) {
            this.$router.push({name: "plugin-overview", params: {pluginId: this.pluginId}});
            return;
        }

        this.plugin = plugin?.data;
    },

    data() {
        return {
            plugin: null,
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
            type: String,
            required: true
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
                this.$router.push({name: "plugin-overview", params: {pluginId: this.pluginId}});
            } catch (e) {
                this.errors = e.response.data.errors ?? {'general': ['An unknown error occurred.']};
            }
            this.isUpdating = false;
        },
        async fetchPluginData() {
            try {
                return PluginRepository.fetchPlugin(this.pluginId);
            } catch (e) {
                this.$router.push({name: "not-found"});
            }
        },
        async fetchPermissions() {
            try {
                return PluginRepository.fetchPluginPermissions(this.pluginId);
            } catch (e) {
                this.$router.push({name: "not-found"});
            }
        },
    },
}
</script>

<style scoped>

</style>
