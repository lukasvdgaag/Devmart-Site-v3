<template>
    <router-link :to="{name: 'plugin-overview', params: {pluginId: plugin.id}}" class="plain">
        <div class="gap-x-4 w-full col-gap-4 flex flex-row">
            <img :src="plugin.getBannerUrl()"
                 alt="Banner image"
                 class="plugin-preview-banner hide-small">
            <img :src="plugin.getLogoUrl()"
                 alt="Logo image"
                 class="resource-icon hide-big">
            <div class="h-full lg:min-h-[9rem] flex flex-col">
                <div class="flex flex-row">
                    <img :src="plugin.getLogoUrl()" alt="Plugin logo" class="hide-small w-6 h-6 rounded-md mr-1.5">
                    <h2 class="text-base font-bold break-words">
                        {{ plugin.title }}
                        <span v-if="plugin?.version" class="text-gray-400 font-normal ml-1">{{ plugin?.version }}</span>
                    </h2>
                </div>
                <div class="text-sm mt-1">{{ plugin.description }}</div>
                <Stats>
                    <Stat :dot="false" :small="true">{{ StringService.formatNumber(plugin.downloads) }} Downloads</Stat>
                    <Stat :small="true">By {{ plugin.author_username }}</Stat>
                    <Stat :small="true">Updated {{ formattedDate }}</Stat>
                </Stats>
                <div class="mt-1.5 lg:mt-auto gap-x-2 flex flex-row">
                    <PluginLabel v-if="plugin.sale" :background="`bg-red-400`" :label="`${plugin.sale.percentage.toFixed(0)}% Sale`"/>
                    <PluginLabel v-if="plugin.custom" label="Custom"/>
                    <PluginLabel v-if="plugin.price > 0" label="Paid"/>
                    <PluginLabel v-if="plugin.isRecentlyUpdated()" icon="fa-calendar-days"
                                 label="Recently Updated"/>
                </div>
            </div>
        </div>
    </router-link>
</template>

<script>
import DateService from "@/services/DateService";
import PluginLabel from "@/components/Pages/Plugins/PluginLabel.vue";
import Stats from "@/components/Common/Stats.vue";
import Stat from "@/components/Common/Stat.vue";
import StringService from "../../../services/StringService";
import Plugin from "@/models/rest/plugin/Plugin";

export default {
    name: "PluginPreview",
    components: {Stat, Stats, PluginLabel},

    computed: {
        StringService() {
            return StringService
        },
        DateService() {
            return DateService
        },
        formattedDate() {
            let date = new Date(this.plugin.last_updated);
            return DateService.formatDateRelatively(date, DateService.diffInDays(new Date(), date) <= 7);
        },
    },

    props: {
        plugin: {
            type: Plugin,
            required: true
        }
    }
}
</script>

<style scoped>

</style>
