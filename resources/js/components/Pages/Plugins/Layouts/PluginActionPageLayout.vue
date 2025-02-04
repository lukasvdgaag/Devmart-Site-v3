<template>
    <div class="w-full flex flex-col items-center m-0 p-0">
        <Navbar :background="true"/>

        <div v-if="this.plugin && this.plugin?.name" class="d-grid !grid-cols-12 gap-0">
            <div class="titled-header col-span-12 text-center">
                <h1>{{ title }}</h1>
                <p class="text-lg">{{ plugin.name }}</p>

                <div class="grid !grid-cols-12 gap-0">
                    <div class="col-span-12 lg:col-span-10 lg:col-start-2 rounded-xl py-9 md:px-5 text-left mt-2">
                        <PluginPreview :plugin="plugin"/>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-10 lg:col-start-2 rounded-xl pt-3 md:px-5 flex flex-col">
                <div class="text-base mb-2 font-bold">
                    <router-link :to="{name: 'plugin-overview', params: {pluginId: plugin.id}}" class="static">Return to the plugin page.</router-link>
                </div>

                <router-view :plugin="plugin" :pluginId="pluginId"/>
            </div>
        </div>
    </div>
</template>

<script>
import PluginPreview from "@/components/Pages/Plugins/PluginPreview.vue";
import Navbar from "@/components/Common/Navbar.vue";
import PluginRepository from "@/services/PluginRepository";
import EditPluginPage from "@/components/Pages/Plugins/Views/EditPluginPage.vue";

export default {
    name: "PluginActionPageLayout",
    components: {Navbar, PluginPreview},

    async created() {
        this.title = this.$route?.matched[1]?.meta?.title;

        const [perms, plugin] = await Promise.all([
            this.fetchPermissions(),
            this.fetchPluginData()
        ]);

        if (!plugin || !perms || !perms?.modify) {
            this.$router.push({name: "plugin-overview", params: {pluginId: this.pluginId}});
            return;
        }

        this.plugin = plugin;
    },

    data() {
        return {
            title: '',
            /**
             * @type {Plugin}
             */
            plugin: null,
        }
    },

    methods: {
        async fetchPluginData() {
            try {
                let withFeaturesField = this.$route.matched.filter(r => r?.components?.default === EditPluginPage).length > 0;

                return PluginRepository.fetchPlugin(this.pluginId, withFeaturesField);
            } catch (e) {
                this.$router.push({name: "not-found"});
            }
        },
        async fetchPermissions() {
            try {
                return await PluginRepository.fetchPluginPermissions(this.pluginId);
            } catch (e) {
                this.$router.push({name: "not-found"});
            }
        },
    },

    props: ['pluginId']
}
</script>
