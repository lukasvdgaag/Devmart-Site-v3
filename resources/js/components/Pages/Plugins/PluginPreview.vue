<template>
    <router-link :to="`/plugins/{plugin.id}`" class="plain">
        <div class="w-full col-gap-4 flex flex-row">
            <img :src="plugin.banner_url ?? 'https://cdn.discordapp.com/discovery-splashes/536178805828485140/e3cf88323111aa759f8764230c3c440c.jpg?size=2048'"
                 alt="Banner image"
                 class="plugin-preview-banner hide-small">
            <img :src="plugin.logo_url"
                 alt="Logo image"
                 class="resource-icon hide-big">
            <div class="plugin-preview-info flex">
                <div class="flex flex-row items-center">
                    <img :src="plugin.logo_url ?? 'img/logo.png'" alt="Plugin logo" class="hide-small">
                    <h2>{{plugin.title}}</h2>
                </div>
                <div class="text-sm mt-1">{{plugin.description}}</div>
                <div class="pb-0 stats flex flex-row">
                    <div class="text-xs flex flex-row align-items-center align-content-center flex-wrap">
                        <div class="stat-info">{{plugin.downloads}} Downloads</div>
                        <div class="stat-dot"></div> <!-- TODO: Add stat/dot component -->
                        <div class="stat-info">By {{plugin.author_username}}</div>
                        <div class="stat-dot"></div>
                        <div class="stat-info">Updated {{ plugin.last_updated }}</div>
                    </div>
                </div>
                <div class="plugin-preview-labels flex flex-row">
                    <PluginLabel v-if="plugin.custom" label="Custom"/>
                    <PluginLabel v-if="plugin.price > 0" label="Paid"/>
                    <PluginLabel v-if="DateService.isAfter(new Date(plugin.last_updated), DateService.offset(-7))" label="Recently Updated" icon="fa-calendar-days"/>
                </div>
            </div>
        </div>
    </router-link>
</template>

<script>
import DateService from "@/services/DateService";
import PluginLabel from "@/components/Pages/Plugins/PluginLabel.vue";

export default {
    name: "PluginPreview",
    components: {PluginLabel},
    computed: {
        DateService() {
            return DateService
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
