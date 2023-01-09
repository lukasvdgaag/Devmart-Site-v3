<template>
    <div class="w-full flex flex-col items-center m-0 p-0">
        <Navbar :background="true"/>

        <div class="d-grid !grid-cols-12 mb-6 gap-0" >
            <div class="titled-header col-span-12 text-center">
                <h1 class="page-title">Edit Plugin</h1>
                <p class="page-subtitle">{{ plugin.name }}</p>

                <div class="grid !grid-cols-12 gap-0">
                    <div class="col-span-12 lg:col-span-10 lg:col-start-2 rounded-xl py-9 md:px-5 text-left mt-2">
                        <PluginPreview :plugin="plugin"/>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-10 lg:col-start-2 rounded-xl py-9 md:px-5 flex flex-col">
                <div class="text-base mb-2 font-bold">
                    <router-link :to="{name: 'plugin-overview', params: {pluginId: pluginId}}" class="static">Return to the plugin page.</router-link>
                </div>

                <form>
                    <FormSection>
                        <FormRow label="Title">
                            <Input v-model="plugin.title"
                                   class="block mt-1 w-full"
                                   name="title"
                                   required/>
                        </FormRow>
                        <FormRow label="Short Description">
                            <Input v-model="plugin.description"
                                   class="block mt-1 w-full"
                                   name="description"
                                   required/>
                        </FormRow>
                        <FormRow label="Categories">
                            <CategoryInput v-model="categories"/>
                        </FormRow>
                    </FormSection>
                </form>

            </div>


        </div>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import PluginPreview from "@/components/Pages/Plugins/PluginPreview.vue";
import FormSection from "@/components/Pages/Plugins/forms/FormSection.vue";
import FormRow from "@/components/Pages/Plugins/forms/FormRow.vue";
import Input from "@/components/Common/Input.vue";
import PluginRepository from "@/services/PluginRepository";
import CategoryInput from "@/components/Pages/Plugins/forms/CategoryInput.vue";

export default {
    name: "EditPluginPage",
    components: {CategoryInput, Input, FormRow, FormSection, PluginPreview, Navbar},

    async created() {
        const perms = await this.fetchPermissions();
        if (!perms || !perms?.modify) {
            this.$router.push({name: "plugin-overview", params: {pluginId: this.pluginId}});
            return;
        }

        this.plugin = await this.fetchPluginData();
    },

    data() {
        return {
            plugin: null,
            categories: []
        }
    },

    props: {
        pluginId: {
            type: String
        }
    },

    methods: {
        async fetchPluginData() {
            try {
                const res = await PluginRepository.fetchPlugin(this.pluginId);
                this.plugin = res.data;
                console.log(this.plugin)
            } catch (e) {
                this.$router.push({name: "not-found"});
            }
        },
        async fetchPermissions() {
            try {
                const res = await PluginRepository.fetchPluginPermissions(this.pluginId);
                return res.data;
            } catch (e) {
                this.$router.push({name: "not-found"});
            }
        }
    }
}
</script>

<style scoped>

</style>
