<template>
    <router-view :pageCount="pageCount"
                 :permissions="permissions"
                 :plugin="plugin"
                 :pluginId="pluginId"
                 :updateCount="updateCount"
                 :updates="updates"
                 :updatesFetchable="updatesFetchable"/>

    <Pagination :current-page="updatesFetchable.page" :fetchable="updatesFetchable" :last-page="pageCount" :per-page="10" :total="updateCount"/>
</template>

<script>
import Plugin from "@/models/rest/Plugin";
import PluginPermissions from "@/models/rest/PluginPermissions";
import Fetchable from "@/models/fetchable/Fetchable";
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
                if (this.updateId) {
                    let update = await PluginRepository.fetchPluginUpdate(this.updateId);
                    this.updates = [update];
                    this.pageCount = 1;
                    this.updateCount = 1;
                } else {
                    const res = await PluginRepository.fetchPluginUpdates(this.pluginId, this.updatesFetchable.page);
                    this.updates = res.updates;
                    this.pageCount = res.pages;
                    this.updateCount = res.total;
                }
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
        },
        updateId: {
            type: String,
            default: undefined,
        }
    }
}
</script>

<style scoped>

</style>
