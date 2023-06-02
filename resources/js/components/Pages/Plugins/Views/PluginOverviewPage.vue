<template>
    <Highlights>
        <Highlight :description="supportedVersions" image="/assets/img/getbukkit.png" title="Supported Versions"/>
        <Highlight :description="formattedLastUpdated" image="/assets/img/calendar.svg" title="Last Updated"/>
        <Highlight v-if="plugin.dependencies.length > 0" :description="plugin.dependencies" image="/assets/img/download.svg"
                   title="(Soft) Dependencies"/>
    </Highlights>

    <BBCode :source="plugin?.features ?? ''">
    </BBCode>
</template>

<script>
import BBCode from "@/components/Common/BBCode.vue";
import Highlights from "@/components/Pages/Plugins/Highlights.vue";
import Highlight from "@/components/Pages/Plugins/Highlight.vue";
import DateService from "@/services/DateService";
import Plugin from "@/models/rest/plugin/Plugin";
import PluginPermissions from "@/models/rest/plugin/PluginPermissions";
import SeoBuilder from "@/services/SeoBuilder";
import PluginRepository from "@/services/PluginRepository";

export default {
    name: "PluginOverviewPage",
    components: {Highlight, Highlights, BBCode},

    head() {
        return new SeoBuilder(this)
            .title(this?.plugin?.title + " - Plugins")
            .withReturn()
            .build()
    },

    async created() {
        if (!this.plugin?.features) {
            this.plugin.features = (await PluginRepository.fetchPlugin(this.pluginId, true)).features;
        }
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
    },

    props: {
        plugin: {
            type: Plugin,
            required: true,
        },
        pluginId: {
            type: String,
            required: true,
        },
        permissions: {
            type: [PluginPermissions, null],
            required: true,
        }
    }
}
</script>
