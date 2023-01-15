<template>
    <div class="w-full flex flex-col items-center m-0 p-0">
        <Navbar :background="!plugin"/>

        <template v-if="plugin">
            <div class="absolute top-0 w-full min-h-[178px] lg:h-[320px] bg-primary max-h-[320px]"></div>
            <div class="mt-10 w-full d-grid z-10">
                <img :src="bannerUrl"
                     alt="Banner"
                     class="bg-gray-75 col-span-12 w-full h-[218px] lg:h-[400px] object-cover rounded-2xl"
                />
            </div>

            <div class="flex flex-col items-center">
                <div class="mt-[-28px] lg:mt-[-52px] z-10 d-grid hide-small">
                    <img :src="`/assets/img/${this.plugin.logo_url}`"
                         alt="Resource Icon"
                        class="ml-12 w-12 h-12 max-w-none lg:w-22 lg:h-22 lg:border-8 lg:rounded-3xl object-cover rounded-2xl bg-white border-4 border-white"
                    >
                </div>

                <div class="plugin-content d-grid mt-2 mt-4-small">
                    <div class="col-span-12 lg:col-span-9 lg:pl-12 flex">
                        <div class="w-full">
                            <div class="flex flex-row" :class="{'border-b border-b-gray-200': !permissions?.modify}">
                                <img class="resource-icon hide-big" :src="plugin.logo_url" alt="Resource Icon">
                                <div class="ml-3-small">
                                    <router-link :to="{name: 'plugin-overview', params: {pluginId: pluginId}}" class="plain">
                                        <h1 class="plugin-header">{{ plugin.title }}</h1>
                                        <p class="plugin-description">{{ plugin.description }}</p>
                                    </router-link>

                                    <Stats class="pb-2">
                                        <Stat>{{ plugin.downloads }} Downloads</Stat>
                                        <Stat>By {{ plugin.author_username }}</Stat>
                                    </Stats>
                                </div>
                            </div>

                            <div v-if="permissions?.modify" class="mt-2 border-b border-b-gray-200 ">
                                <router-link :to="{name: 'update-plugin', params: {pluginId: plugin.id}}"
                                             class="action-button purple flex-col align-center flex lg:hidden"><span>Post Update</span></router-link>
                                <router-link :to="{name: 'edit-plugin', params: {pluginId: plugin.id}}"
                                             class="action-button purple flex-col align-center flex lg:hidden"><span>Edit Plugin</span></router-link>
                            </div>

                            <Highlights>
                                <Highlight title="Supported Versions" :description="supportedVersions" image="/assets/img/getbukkit.png"/>
                                <Highlight title="Last Updated" :description="formattedLastUpdated" image="/assets/img/calendar.svg"/>
                                <Highlight v-if="plugin.dependencies.length > 0" title="(Soft) Dependencies" :description="plugin.dependencies"
                                           image="/assets/img/download.svg"/>

                                <Highlight v-for="highlight in plugin.highlights" :description="highlight" image=""></Highlight>
                            </Highlights>

                            <router-view :plugin="plugin" :pluginId="pluginId"/>
                        </div>
                    </div>

                    <PluginSidebar :plugin="plugin" :permissions="permissions"/>

                </div>
            </div>
        </template>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import PluginRepository from "@/services/PluginRepository";
import Stats from "@/components/Common/Stats.vue";
import Stat from "@/components/Common/Stat.vue";
import Highlights from "@/components/Pages/Plugins/Highlights.vue";
import Highlight from "@/components/Pages/Plugins/Highlight.vue";
import DateService from "@/services/DateService";
import PluginSidebar from "@/components/Pages/Plugins/PluginSidebar.vue";

export default {
    name: "PluginOverviewPage",
    components: {PluginSidebar, Highlight, Highlights, Stat, Stats, Navbar},

    async created() {
        await Promise.all([
            this.fetchPluginData(),
            this.fetchPermissions(),
        ]);
    },

    computed: {
        supportedVersions() {
            const versions = this.plugin.minecraft_versions;
            if (!versions || versions.length === 0) return "Not specified.";

            return versions;
        },
        formattedLastUpdated() {
            return DateService.formatDateRelatively(new Date(this.plugin.last_updated), true);
        },
        bannerUrl() {
            if (this.plugin.banner_url) {
                return `/assets/img/${this.plugin.banner_url}`;
            }
            return '/assets/img/default-plugin-banner.png'
        }
    },

    data() {
        return {
            plugin: null,
            permissions: null,
        }
    },

    methods: {
        async fetchPluginData() {
            try {
                const res = await PluginRepository.fetchPlugin(this.pluginId);
                this.plugin = res.data;
            } catch (e) {
                this.$router.push({name: "not-found"});
            }
        },
        async fetchPermissions() {
            try {
                const res = await PluginRepository.fetchPluginPermissions(this.pluginId);
                this.permissions = res.data;
            } catch (e) {
                this.$router.push({name: "not-found"});
            }
        }
    },

    props: {
        pluginId: {
            type: String,
            required: true
        }
    }
}
</script>

<style scoped>

</style>
