<template>
    <router-view :pluginId="pluginId"
                 :plugin="plugin"
                 :permissions="permissions"
                 :updates="updates"
                 :pageCount="pageCount"
                 :updateCount="updateCount"
                 :updatesFetchable="updatesFetchable"/>

    <Pagination :last-page="pageCount" :current-page="updatesFetchable.page" :per-page="10" :total="updateCount" :fetchable="updatesFetchable"/>
</template>

<script>
import Plugin from "@/models/rest/Plugin";
import PluginPermissions from "@/models/rest/PluginPermissions";
import Fetchable from "@/models/Fetchable";
import PluginRepository from "@/services/PluginRepository";
import Pagination from "@/components/Common/Pagination/Pagination.vue";

export default {
    name: "PluginVersionsLayout",
    components: {Pagination},

    data() {
        return {
            /**
             * @type {PluginUpdate[]}
             */
            updates: [],
            pageCount: 0,
            updateCount: 0,
            updatesFetchable: new Fetchable(
                this.fetchVersions,
                '',
                Number.parseInt(this.$route.query.page ?? '1')
            ),
        }
    },

    async created() {
        await this.updatesFetchable.fetch(this);
    },

    methods: {
        async fetchVersions() {
            try {
                const res = await PluginRepository.fetchPluginUpdates(this.pluginId, this.updatesFetchable.page);
                this.updates = res.updates;
                this.pageCount = res.pages;
                this.updateCount = res.total;
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
