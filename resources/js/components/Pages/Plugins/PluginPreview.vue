<template>
    <router-link :to="{name: 'plugin-overview', params: {pluginId: plugin.id}}" class="plain">
        <div class="gap-x-4 w-full col-gap-4 flex flex-row">
            <img :src="plugin.banner_url ?? 'https://cdn.discordapp.com/discovery-splashes/536178805828485140/e3cf88323111aa759f8764230c3c440c.jpg?size=2048'"
                 alt="Banner image"
                 class="plugin-preview-banner hide-small">
            <img :src="plugin.logo_url"
                 alt="Logo image"
                 class="resource-icon hide-big">
            <div class="h-full lg:min-h-[9rem] flex flex-col">
                <div class="flex flex-row items-center">
                    <img :src="plugin.logo_url ?? 'img/logo.png'" alt="Plugin logo" class="hide-small w-6 h-6 rounded-md mr-1.5">
                    <h2 class="text-base font-bold break-words">{{ plugin.title }}</h2>
                </div>
                <div class="text-sm mt-1">{{ plugin.description }}</div>
                <Stats>
                    <Stat :small="true" :dot="false">{{ StringService.formatNumber(plugin.downloads) }} Downloads</Stat>
                    <Stat :small="true">By {{ plugin.author_username }}</Stat>
                    <Stat :small="true">Updated {{ formattedDate }}</Stat>
                </Stats>
                <div class="mt-1.5 lg:mt-auto gap-x-2 flex flex-row">
                    <PluginLabel v-if="plugin.custom" label="Custom"/>
                    <PluginLabel v-if="plugin.price > 0" label="Paid"/>
                    <PluginLabel v-if="DateService.isAfter(new Date(plugin.last_updated), DateService.offset(-7))" label="Recently Updated"
                                 icon="fa-calendar-days"/>
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
        formattedDate(){
            return DateService.formatDateRelatively(new Date(this.plugin.last_updated), true);
        }
    },

    props: {
        plugin: {
            type: Object,
            required: true
        }
    }
}
</script>

<style scoped>

</style>
