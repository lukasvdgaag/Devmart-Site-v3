<template>
    <h2 class="my-2">Version History</h2>

    <table>
        <thead>
        <tr>
            <th>Version</th>
            <th>Release Date</th>
            <th class="hidden md:table-cell">File Size</th>
            <th>Downloads</th>
            <th v-if="permissions?.download"></th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="!updatesFetchable.loading && updates?.length === 0">
            <td colspan="5" class="text-red-400">This plugin has not released a version yet.</td>
        </tr>

        <tr v-for="version in updates" :key="version.id">
            <td>{{ version.display_name }}</td>
            <td>{{ DateService.formatDateRelatively(new Date(version.created_at), true) }}</td>
            <td class="hidden md:table-cell">{{ version.file_size }}</td>
            <td>{{ StringService.formatNumber(version.downloads) }}</td>
            <td class="text-right" v-if="permissions?.download">
                <a :href="`/plugins/${pluginId}/download/${version.file_name}`" target="_blank" class="static">
                    Download
                </a>
            </td>
        </tr>
        </tbody>
    </table>
</template>

<script>
import Fetchable from "@/models/Fetchable";
import Pagination from "@/components/Common/Pagination/Pagination.vue";
import DateService from "../../../../services/DateService";
import StringService from "../../../../services/StringService";
import PluginPermissions from "@/models/rest/PluginPermissions";
import Plugin from "@/models/rest/Plugin";

export default {
    name: "PluginVersionsPage",
    computed: {
        StringService() {
            return StringService
        },
        DateService() {
            return DateService
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
