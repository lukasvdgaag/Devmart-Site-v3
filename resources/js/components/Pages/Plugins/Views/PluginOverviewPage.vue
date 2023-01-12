<template>
    <BBCode :source="plugin.features">
    </BBCode>
</template>

<script>
import BBCode from "@/components/Common/BBCode.vue";

export default {
    name: "PluginOverviewPage",
    components: {BBCode},

    props: {
        plugin: {
            type: Object,
            required: true,
        },
        pluginId: {
            type: String,
            required: true,
        }
    }
}
</script>

<style scoped>

</style>
