<script>
import {defineComponent} from 'vue'
import PluginSidebarHeader from "@/components/Pages/Plugins/PluginSidebarHeader.vue";
import PastesRepository from "@/services/PastesRepository";
import DateService from "../../../services/DateService";

export default defineComponent({
    name: "RecentPastesList",
    computed: {
        DateService() {
            return DateService
        }
    },
    components: {PluginSidebarHeader},
    props: ['isCreatePage'],

    created() {
        this.fetchRecentPastes();
    },

    data() {
        return {
            /**
             * @type {Paste[]}
             */
            recentPastes: [],
            loading: true,
        }
    },

    methods: {
        async fetchRecentPastes() {
            this.loading = true;
            try {
                const response = await PastesRepository.fetchRecentPastes();
                this.recentPastes = response.pastes;
            } catch (e) {
                this.recentPastes = [];
            }
            this.loading = false;
        }
    }
})
</script>

<template>
    <div>
        <PluginSidebarHeader>Recent Pastes</PluginSidebarHeader>

        <div class="flex flex-col gap-3">
            <div v-if="loading" class="animate-pulse w-full mt-1" role="status">
                <template v-for="i in 5">
                    <div class="h-3 bg-gray-300 rounded dark:bg-gray-600 w-32 mb-1"></div>
                    <div class="h-2.5 bg-gray-200 rounded dark:bg-gray-700 w-48 mb-4"></div>
                </template>
                <span class="sr-only">Loading...</span>
            </div>
            <router-link v-for="paste in recentPastes" v-else
                         :key="paste.id"
                         :target="isCreatePage ? '_blank' : '_self'"
                         :to="{name: 'paste-info', params: {pasteId: paste.name}}"
                         class="plain">
                <div class="text-sm font-semibold">{{ paste.title }}</div>
                <div class="text-xs dark:text-gray-400">{{ DateService.formatTimeAgo(paste.updated_at) }} | by
                    {{ paste?.creator_username ?? 'anonymous' }}
                </div>
            </router-link>
        </div>
    </div>
</template>

<style scoped>

</style>
