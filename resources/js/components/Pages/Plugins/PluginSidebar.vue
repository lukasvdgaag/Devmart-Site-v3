<template>
    <Sidebar :left-side="false">
        <StickyFooter>
            <router-link v-if="showDownloadLink" :to="downloadLink" class="action-button primary btn-text-full flex gap-0 align-center">
                <span>{{downloadLink}}</span>
                <span class="text-xs">{{downloadInfo}}</span>
            </router-link>
            <router-link :to="`/plugins/${plugin.id}/versions`"
               class="action-button gray-hollow flex align-center"><span>Other Versions</span>
            </router-link>
        </StickyFooter>

        <hr class="lg:hidden">

        <router-link v-if="showDownloadLink" :to="downloadLink" class="action-button primary btn-text-full flex gap-0 align-center hidden lg:block">
            <span>{{downloadLink}}</span>
            <span class="text-xs">{{downloadInfo}}</span>
        </router-link>
        <router-link :to="`/plugins/${plugin.id}/versions`"
                     class="action-button gray-hollow flex align-center  hidden lg:block"><span>Other Versions</span>
        </router-link>

        <template v-if="canModify">
            <hr class="lg:hidden">
            <router-link :to="{name: 'update-plugin', params: {pluginId: plugin.id}}"
                         class="action-button purple flex align-center hidden lg:block"><span>Post Update</span></router-link>
            <router-link :to="{name: 'edit-plugin', params: {pluginId: plugin.id}}"
                         class="action-button purple flex align-center hidden lg:block"><span>Edit Plugin</span></router-link>
        </template>

        <hr class="hidden lg:block"/>

        <h3 class="sidebar-header text-xs uppercase mb-0 mt-2">Author</h3>
        <div>{{plugin.author_username}}</div>

        <template v-if="plugin.price > 0">
            <h3 class="sidebar-header text-xs uppercase mt-2 mb-0">Price</h3>
            <div>{{StringService.formatMoney(plugin.price)}}</div>
        </template>

    </Sidebar>
</template>

<script>

import Sidebar from "@/components/Common/Sidebar.vue";
import StickyFooter from "@/components/Common/StickyFooter.vue";
import {useAuth} from "@/store/authStore";
import StringService from "@/services/StringService";

export default {
    name: "PluginSidebar",
    components: {StickyFooter, Sidebar},

    computed: {
        StringService() {
            return StringService
        },
        showDownloadLink() {
            return this.canDownload || !this.plugin.custom || !this.plugin.price > 0;
        },
        canDownload() {
            return this.permissions?.download;
        },
        canModify() {
            return this.permissions?.modify;
        },
        downloadLabel() {
            if (this.canDownload) return "Download";

            const formattedPrice = StringService.formatMoney(this.plugin.price);
            if (!useAuth().loggedIn) return "Login To Buy for " + formattedPrice;
            return "Buy for " + formattedPrice;
        },
        downloadLink() {
            // when free/logged in and can download, return direct link
            if (this.canDownload) return `/plugins/${this.plugin.id}/download`;

            // when not logged in, return login link with redirect
            if (!useAuth().loggedIn) return {
                name: 'login',
                params: {redirect: this.$route.fullPath}
            }

            // otherwise return buy link
            return "#";
        },
        downloadInfo() {
            return "10 KB";
        }
    },

    props: {
        plugin: {
            type: Object,
            required: true,
        },
        permissions: {
            type: Object,
            required: true,
        }
    }
}
</script>

<style scoped>

</style>
