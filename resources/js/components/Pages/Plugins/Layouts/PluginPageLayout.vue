<template>
    <div class="w-full flex flex-col items-center m-0 p-0">
        <Navbar :background="!plugin"/>

        <template v-if="plugin">
            <div class="absolute top-0 w-full min-h-[178px] lg:h-[320px] bg-primary max-h-[320px]"></div>
            <div class="mt-10 w-full d-grid z-1">
                <img :src="bannerUrl"
                     alt="Banner"
                     class="bg-gray-75 col-span-12 w-full h-[218px] lg:h-[400px] object-cover rounded-2xl"
                />
            </div>

            <div class="flex flex-col items-center w-full">
                <div class="mt-[-28px] lg:mt-[-52px] z-1 d-grid hide-small">
                    <img :src="`/assets/img/${this.plugin.logo_url}`"
                         alt="Resource Icon"
                         class="ml-12 w-12 h-12 max-w-none lg:w-22 lg:h-22 lg:border-8 lg:rounded-3xl object-cover rounded-2xl bg-white border-4 border-white"
                    >
                </div>

                <div class="d-grid mt-2 mt-4-small mb-32">
                    <div class="col-span-12 lg:col-span-9 lg:pl-12 flex">
                        <div class="w-full">
                            <div v-if="plugin?.sale" class="mb-3 flex gap-1 flex-wrap">
                                <PluginSalePart class="uppercase font-bold">{{ plugin.sale.percentage.toFixed(0) }}% sale</PluginSalePart>
                                <PluginSalePart>
                                    New price: <span>{{ StringService.formatMoney((plugin.price / 100) * (100 - plugin.sale.percentage), false) }}</span>
                                    <span class="line-through ml-2 text-xs">{{ StringService.formatMoney(this.plugin.price, false) }}</span>
                                </PluginSalePart>
                                <PluginSalePart v-if="saleTimeLeft">{{ saleTimeLeft }}</PluginSalePart>
                            </div>
                            <PluginLabel v-if="plugin.isRecentlyUpdated()" class="mb-2"
                                         icon="fa-calendar-days" label="Recently Updated"/>
                            <div :class="{'border-b border-b-gray-200': !permissions?.modify}" class="flex flex-row">
                                <img :src="`/assets/img/${this.plugin.logo_url}`" alt="Resource Icon" class="resource-icon hide-big">
                                <div class="ml-3-small">
                                    <router-link :to="{name: 'plugin-overview', params: {pluginId: pluginId}}" class="plain">
                                        <h1 class="font-black text-xl lg:text-4xl mb-1 lg:mb-2">
                                            {{ plugin.title }}
                                        </h1>
                                        <p class="text-gray-600 text-xs lg:text-xl mb-3 lg:mb-6 font-roboto">{{ plugin.description }}</p>
                                    </router-link>

                                    <Stats class="pb-2">
                                        <Stat>{{ plugin.downloads }} Downloads</Stat>
                                        <Stat>By {{ plugin.author_username }}</Stat>
                                        <Stat v-if="plugin?.latest_update?.version">Version: {{ plugin?.latest_update?.version }}</Stat>
                                    </Stats>
                                </div>
                            </div>

                            <div v-if="permissions?.modify" class="mt-2 border-b border-b-gray-200 ">
                                <router-link :to="{name: 'update-plugin', params: {pluginId: plugin.id}}"
                                             class="action-button purple flex-col align-center flex lg:hidden"><span>Post Update</span></router-link>
                                <router-link :to="{name: 'edit-plugin', params: {pluginId: plugin.id}}"
                                             class="action-button purple flex-col align-center flex lg:hidden"><span>Edit Plugin</span></router-link>
                            </div>

                            <router-view :permissions="permissions" :plugin="plugin" :pluginId="pluginId"/>
                        </div>
                    </div>

                    <PluginSidebar :permissions="permissions" :plugin="plugin"/>

                </div>
            </div>
        </template>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import PluginRepository from "@/services/PluginRepository.js";
import Stats from "@/components/Common/Stats.vue";
import Stat from "@/components/Common/Stat.vue";
import DateService from "@/services/DateService.js";
import PluginSidebar from "@/components/Pages/Plugins/PluginSidebar.vue";
import StringService from "@/services/StringService.js";
import PluginLabel from "@/components/Pages/Plugins/PluginLabel.vue";
import PluginSalePart from "@/components/Pages/Plugins/PluginSalePart.vue";
import PluginOverviewPage from "@/components/Pages/Plugins/Views/PluginOverviewPage.vue";

export default {
    name: "PluginOverviewPage",
    components: {PluginSalePart, PluginLabel, PluginSidebar, Stat, Stats, Navbar},

    created() {
        this.fetchPluginData();
        this.fetchPermissions();
    },

    beforeMount() {
        setInterval(() => {
            this.saleTimeLeft = this?.plugin?.sale?.end_date ? DateService.formatTimeLeft(new Date(this.plugin.sale?.end_date)) : null;
        }, 1000);
    },

    computed: {
        DateService() {
            return DateService
        },
        StringService() {
            return StringService
        },
        bannerUrl() {
            if (this.plugin.banner_url) {
                return `/assets/img/${this.plugin.banner_url}`;
            }
            return '/assets/img/default-plugin-banner.png'
        },
    },

    data() {
        return {
            /**
             * @type {Plugin}
             */
            plugin: null,
            /**
             * @type {PluginPermissions}
             */
            permissions: null,
            saleTimeLeft: null,
        }
    },

    methods: {
        async fetchPluginData() {
            try {
                let withFeaturesField = this.$route.matched.filter(r => r?.components?.default === PluginOverviewPage).length > 0;

                this.plugin = await PluginRepository.fetchPlugin(this.pluginId, withFeaturesField);
            } catch (e) {
                this.$router.push({name: "not-found"});
            }
        },
        async fetchPermissions() {
            try {
                this.permissions = await PluginRepository.fetchPluginPermissions(this.pluginId);
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
