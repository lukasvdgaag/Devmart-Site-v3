<template>
    <Highlights>
        <Highlight title="Supported Versions" :description="supportedVersions" image="/assets/img/getbukkit.png"/>
        <Highlight title="Last Updated" :description="formattedLastUpdated" image="/assets/img/calendar.svg"/>
        <Highlight v-if="plugin.dependencies.length > 0" title="(Soft) Dependencies" :description="plugin.dependencies"
                   image="/assets/img/download.svg"/>
    </Highlights>

    <BBCode :source="plugin.features">
    </BBCode>
</template>

<script>
import BBCode from "@/components/Common/BBCode.vue";
import Highlights from "@/components/Pages/Plugins/Highlights.vue";
import Highlight from "@/components/Pages/Plugins/Highlight.vue";
import DateService from "@/services/DateService";
import Plugin from "@/models/rest/Plugin";
import PluginPermissions from "@/models/rest/PluginPermissions";

export default {
    name: "PluginOverviewPage",
    components: {Highlight, Highlights, BBCode},

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
