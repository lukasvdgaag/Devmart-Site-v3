<template>
    <div class="w-full flex flex-col items-center m-0 p-0 relative">
        <Navbar :background="false"/>

        <HeaderBackground title="Paste" subtitle="Easily share text."/>

        <div class="d-grid mt-6">
            <Sidebar :left-side="true" :margin="false" class="mb-6 lg:mb-2">
                <router-link :to="{name: 'new-paste'}" target="_blank" class="action-button primary btn-text-full flex flex-col gap-0 align-center">
                    Create New Paste
                </router-link>
                <router-link v-if="useAuth().loggedIn" :to="{name: 'paste-list'}" class="action-button gray flex flex-col align-center">
                    Your Pastes
                </router-link>

                <hr>
                <PluginSidebarHeader>Recent Pastes</PluginSidebarHeader>

                <div class="flex flex-col gap-3">
                    <router-link :to="{name: 'paste-info', params: {pasteId: paste.id}}" v-for="paste in recentPastes" :key="paste.id" target="_blank" class="plain">
                        <div class="text-sm font-semibold">{{paste.title}}</div>
                        <div class="text-xs">{{ DateService.formatTimeAgo(paste.updated_at)}} | by {{paste?.creator_username ?? 'anonymous'}}</div>
                    </router-link>
                </div>
            </Sidebar>
            <div class="col-span-12 lg:col-span-9 flex">

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

export default {
    name: "PastePageLayout",
    computed: {
        DateService() {
            return DateService
        }
    },
    components: {PluginSidebarHeader, Sidebar, HeaderBackground, Navbar},

    created() {
        this.fetchRecentPastes();
    },

    data() {
      return {
          /**
           * @type {Paste[]}
           */
          recentPastes: [],
      }
    },

    methods: {
        useAuth,
        async fetchRecentPastes() {
            try {
                const response = await PastesRepository.fetchRecentPastes();
                this.recentPastes = response.pastes;
            } catch (e) {
                console.log(e);
                this.recentPastes = [];
            }
        }
    }
}
</script>

<style scoped>

</style>
