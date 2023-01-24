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
        <tr v-if="!versionsFetchable.loading && versions?.length === 0">
            <td colspan="5" class="text-red-400">This plugin has not released a version yet.</td>
        </tr>

        <tr v-for="version in versions" :key="version.id">
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

    <Pagination :last-page="pageCount" :current-page="versionsFetchable.page" :per-page="10" :total="versionCount" :fetchable="versionsFetchable"/>
</template>

<script>
import PluginRepository from "@/services/PluginRepository";
import Fetchable from "@/models/Fetchable";
import Pagination from "@/components/Common/Pagination/Pagination.vue";
import DateService from "../../../../services/DateService";
import StringService from "../../../../services/StringService";
import PluginPermissions from "@/models/rest/PluginPermissions";
import Plugin from "@/models/rest/Plugin";
import PluginUpdate from "@/models/rest/PluginUpdate";

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

    data() {
        return {
            /**
             * @type {PluginUpdate[]}
             */
            versions: [],
            pageCount: 0,
            versionCount: 0,
            versionsFetchable: new Fetchable(
                this.fetchVersions,
                '',
                Number.parseInt(this.$route.query.page ?? '1')
            ),
        }
    },

    async created() {
        await this.versionsFetchable.fetch(this);
    },

    methods: {
        async fetchVersions() {
            try {
                const res = await PluginRepository.fetchPluginUpdates(this.pluginId, this.versionsFetchable.page);
                this.versions = res.data.updates;
                this.pageCount = res.data.pages;
                this.versionCount = res.data.total;
            } catch (e) {
                this.$route.push({name: 'not-found'});
            }
        },
    },

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
        }
    }
}
</script>

<style scoped>

</style>
