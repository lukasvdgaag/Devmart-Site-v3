<template>

    <PluginActionPageLayout title="Update Plugin" :plugin="plugin">
        <form @submit.prevent="updatePlugin">
            <FormSection>
                <FormRow label="Version">
                    <Input v-model="updateInfo.version"
                           class="block w-full"
                           name="version"
                           :placeholder="plugin?.latest_update?.version ?? '1.0.0'"
                           required/>
                </FormRow>
                <FormRow label="Beta number">
                    <Input v-model="updateInfo.beta_number"
                           class="block w-full"
                           name="beta_number"
                           type="number"
                           min="0"
                           :placeholder="plugin?.latest_update?.beta_number ?? '0'"
                           required/>
                    <MutedText>
                        Enter a number higher than zero to indicate that this is a beta version.
                    </MutedText>
                </FormRow>
                <FormRow label="Title">
                    <Input v-model="updateInfo.title"
                           class="block w-full"
                           name="title"
                           placeholder="Title"
                           required/>
                </FormRow>
            </FormSection>
            <FormSection>
                <FormRow label="File">
                    <FileInput @upload="file = $event.target ?? null" accept=".zip,.jar,.tar,.sk,.rar"/>
                </FormRow>
            </FormSection>
            <FormSection>
                <FormRow label="Changelog">
                    <Input v-model="updateInfo.changelog"
                           class="block w-full"
                           :is-textarea="true"
                           name="changelog"
                           placeholder="Full changelog..."
                           required/>
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
                           required/>
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
                <button class="primary w-full md:w-2/4 p-2 mt-0" type="submit">Update</button>
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

export default {
    name: "UpdatePluginPage",
    components: {Input, PluginActionPageLayout, StickyFooter, MutedText, FileInput, FormRow, FormSection},

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
            updateInfo: {
                version: '',
                beta_number: 0,
                title: '',
                changelog: '',
                short_changelog: '',
            },
        }
    },

    props: {
        pluginId: {
            type: String,
            required: true
        }
    },

    methods: {
        async updatePlugin() {
            // TODO.
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
