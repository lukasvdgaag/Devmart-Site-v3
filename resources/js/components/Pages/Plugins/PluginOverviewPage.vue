<template>

</template>

<script>
export default {
    name: "PluginOverviewPage",

    data() {
        return {
            plugin: {},

        }
    },

    props: {
        pluginId: {
            type: String|Number,
            required: true
        }
    }
}
</script>

<style scoped>

</style>
