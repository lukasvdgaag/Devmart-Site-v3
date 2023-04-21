<template>
    <h2 class="my-4">Updates</h2>

    <div class="flex flex-col gap-2">
        <span v-if="!updatesFetchable.loading && updates?.length === 0" class="text-red-400">This resource has no updates yet.</span>
        <span v-else-if="updatesFetchable.loading" class="text-gray-400">Loading updates...</span>

        <PluginUpdateInformation v-for="update in updates" :key="update.id" :permissions="permissions" :update="update" :keep-expanded="false"/>
    </div>
</template>

<script>
import Plugin from "@/models/rest/Plugin";
import PluginPermissions from "@/models/rest/PluginPermissions";
import Fetchable from "@/models/fetchable/Fetchable";
import DateService from "../../../../services/DateService";
import PluginUpdateInformation from "@/components/Pages/Plugins/PluginUpdateInformation.vue";

export default {
    name: "PluginUpdatesPage",
    computed: {
        DateService() {
            return DateService
        }
    },
    components: {PluginUpdateInformation},

    props: {
        pluginId: {
            required: true,
        },
        plugin: {
            type: Plugin,
            required: true,
        },
        permissions: {
            type: [PluginPermissions, null],
            required: true,
        },
        updates: {
            type: Array,
            required: true,
        },
        pageCount: {
            type: Number,
            required: true,
        },
        updateCount: {
            type: Number,
            required: true,
        },
        updatesFetchable: {
            type: Fetchable,
            required: true,
        }
    }
}
</script>

<style scoped>

</style>
