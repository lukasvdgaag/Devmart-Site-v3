<template>
    <h2 class="my-4">Version History</h2>

    <table>
        <thead>
        <tr>
            <th>Version</th>
            <th>Release Date</th>
            <th class="hidden md:table-cell">File Size</th>
            <th>Downloads</th>
            <th v-if="permissions?.download && anyDownloadsAvailable"></th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="!updatesFetchable.loading && updates?.length === 0">
            <td class="text-red-400" colspan="5">This plugin has not released a version yet.</td>
        </tr>

        <tr v-for="version in updates" :key="version.id">
            <td>{{ version.effective_version }}</td>
            <td>{{ DateService.formatDateRelatively(new Date(version.created_at), true) }}</td>
            <td class="hidden md:table-cell">{{ version.file_size ? StringService.formatFileSize(version.file_size) : 'No download available' }}</td>
            <td>{{ StringService.formatNumber(version.downloads) }}</td>
            <td v-if="permissions?.download && anyDownloadsAvailable && version.file_size" class="text-right">
                <a :href="`/plugins/${pluginId}/download/${version.file_name}`" class="static" target="_blank">
                    Download
                </a>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
import Fetchable from "@/models/fetchable/Fetchable";
import Pagination from "@/components/Common/Pagination/Pagination.vue";
import DateService from "../../../../services/DateService";
import StringService from "../../../../services/StringService";
import PluginPermissions from "@/models/rest/plugin/PluginPermissions";
import Plugin from "@/models/rest/plugin/Plugin";

export default {
    name: "PluginVersionsPage",
    computed: {
        StringService() {
            return StringService
        },
        DateService() {
            return DateService
        },
        anyDownloadsAvailable() {
            return this.updates.some(update => update.file_size);
        }
    },
    components: {Pagination},

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
