<template>
    <h2 class="my-2">Updates</h2>

    <div class="flex flex-col gap-2">
        <div class="bg-gray-75 rounded-md px-4 py-3 border border-gray-200 mb-2" v-for="update in updates" :key="update.id">
            <div class="font-bold text-lg">
                {{ update.title }}
                <span class="font-normal text-gray-400 ml-1">
                    {{ update.display_name }}
                </span>
            </div>
            <p v-html="parseChangelog(update)"></p>

            <Stats class="!mt-3">
                <Stat :dot="false" :small="true">Updated {{ DateService.formatDateRelatively(new Date(update.created_at), true) }}</Stat>
            </Stats>
        </div>
    </div>
</template>

<script>
import PluginRepository from "@/services/PluginRepository";
import Plugin from "@/models/rest/Plugin";
import PluginPermissions from "@/models/rest/PluginPermissions";
import Fetchable from "@/models/Fetchable";
import Stats from "@/components/Common/Stats.vue";
import Stat from "@/components/Common/Stat.vue";
import DateService from "../../../../services/DateService";

export default {
    name: "PluginUpdatesPage",
    computed: {
        DateService() {
            return DateService
        }
    },
    components: {Stat, Stats},

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
        parseChangelog(update) {
            const changelog = update.changelog.split("<br>");
            for (let i = 0; i < changelog.length; i++) {
                changelog[i] = changelog[i].replace(/^((Fixed)|(Added)|(Removed)|(Improved)|(Upgraded))/gmi, '<span class="font-semibold">$1</span>');
            }
            return changelog.join("<br>");
        }
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
        /**
         * @type {Array<PluginUpdate>}
         */
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
