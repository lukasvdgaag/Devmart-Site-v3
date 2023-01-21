<template>
    <div class="w-full flex flex-col items-center m-0 p-0">
        <Navbar :background="true"/>

        <div class="d-grid !grid-cols-12 gap-0" v-if="this.plugin && this.plugin?.name">
            <div class="titled-header col-span-12 text-center">
                <h1 class="page-title">{{title}}</h1>
                <p class="page-subtitle">{{ plugin.name }}</p>

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

                <slot/>
            </div>
        </div>
    </div>
</template>

<script>
import PluginPreview from "@/components/Pages/Plugins/PluginPreview.vue";
import Navbar from "@/components/Common/Navbar.vue";

export default {
    name: "PluginActionPageLayout",
    components: {Navbar, PluginPreview},

    props: {
        title: {
          type: String,
          required: true,
        },
        plugin: {
            type: Object,
            required: true,
        }
    }
}
</script>

<style scoped>

</style>
