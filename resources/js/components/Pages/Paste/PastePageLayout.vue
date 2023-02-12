<template>
    <div class="w-full flex flex-col items-center m-0 p-0 relative h-full">
        <Navbar :background="false"/>

        <HeaderBackground subtitle="Easily share text." title="Paste"/>

        <div class="d-grid mt-6 h-full">
            <Sidebar :left-side="true" :margin="false" class="mb-4 lg:mb-2 row-start-2 lg:row-start-1 hidden lg:block">

                <Hr class="lg:hidden"/>

                <router-link :target="isCreatePage ? '_blank' : '_self'"
                             :to="{name: 'paste'}"
                             class="action-button primary btn-text-full flex flex-col gap-0 align-center">
                    Create New Paste
                </router-link>
                <!-- :to="{name: 'paste-list'}" -->
                <router-link v-if="useAuth().loggedIn"
                             :target="isCreatePage ? '_blank' : '_self'"
                             class="action-button gray flex flex-col align-center"
                             to="#">
                    Your Pastes
                </router-link>

                <Hr/>
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
            </Sidebar>
            <div class="col-span-12 lg:col-span-9 flex mb-6 lg:mb-0">
                <router-view/>
            </div>
        </div>

    </div>

</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import HeaderBackground from "@/components/Common/HeaderBackground.vue";
import Sidebar from "@/components/Common/Sidebar.vue";
import {useAuth} from "@/store/authStore";
import PastesRepository from "@/services/PastesRepository";
import PluginSidebarHeader from "@/components/Pages/Plugins/PluginSidebarHeader.vue";
import DateService from "../../../services/DateService";
import Hr from "@/components/Common/Hr.vue";
import PasteCreatePage from "@/components/Pages/Paste/PasteCreatePage.vue";

export default {
    name: "PastePageLayout",
    computed: {
        DateService() {
            return DateService
        }
    },
    components: {Hr, PluginSidebarHeader, Sidebar, HeaderBackground, Navbar},

    created() {
        this.fetchRecentPastes();
        this.isCreatePage = this.$route.matched.some(r => r.components.default === PasteCreatePage);
    },

    data() {
        return {
            /**
             * @type {Paste[]}
             */
            recentPastes: [],
            loading: true,
            isCreatePage: false
        }
    },

    watch: {
        '$route'() {
            this.isCreatePage = this.$route.matched.some(r => r.components.default === PasteCreatePage);
            this.fetchRecentPastes();
        }
    },

    methods: {
        useAuth,
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
}
</script>

<style scoped>

</style>
