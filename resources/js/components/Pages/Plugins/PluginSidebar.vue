<template>
    <Sidebar :left-side="false">
        <StickyFooter :fixed="true" class="block lg:hidden">
            <router-link v-if="showDownloadLink" :to="downloadLink" class="action-button primary btn-text-full flex flex-col gap-0 align-center">
                <span>{{ downloadLabel }}</span>
                <span class="text-xs">{{ downloadInfo }}</span>
            </router-link>
            <router-link :to="`/plugins/${plugin.id}/versions`"
                         class="action-button gray-hollow flex flex-col align-center"><span>Other Versions</span>
            </router-link>
        </StickyFooter>

        <hr class="lg:hidden">

        <router-link v-if="showDownloadLink" :to="downloadLink" class="action-button primary btn-text-full flex-col gap-0 align-center hidden lg:flex">
            <span>{{ downloadLabel }}</span>
            <span class="text-[10px]">{{ downloadInfo }}</span>
        </router-link>
        <router-link :to="`/plugins/${plugin.id}/versions`"
                     class="action-button gray-hollow flex-col align-center hidden lg:flex"><span>Other Versions</span>
        </router-link>

        <template v-if="canModify">
            <hr class="hidden md:block">
            <router-link :to="{name: 'update-plugin', params: {pluginId: plugin.id}}"
                         class="action-button purple flex-col align-center hidden lg:flex"><span>Post Update</span></router-link>
            <router-link :to="{name: 'edit-plugin', params: {pluginId: plugin.id}}"
                         class="action-button purple flex-col align-center hidden lg:flex"><span>Edit Plugin</span></router-link>
        </template>

        <hr class="hidden lg:block"/>

        <PluginSidebarHeader class="mt-2">Author</PluginSidebarHeader>
        <div>{{ plugin.author_username }}</div>

        <template v-if="plugin.price > 0">
            <PluginSidebarHeader class="mt-2">Price</PluginSidebarHeader>
            <div>{{ StringService.formatMoney(plugin.price, false) }}</div>
        </template>

        <template v-if="categories.length > 0">
            <PluginSidebarHeader class="mt-8 mb-2">Categories</PluginSidebarHeader>
            <div class="flex flex-wrap gap-2">
                <PluginCategory v-for="category in categories" :key="category">{{ category }}</PluginCategory>
            </div>
        </template>

        <PluginSidebarHeader class="mt-8 mb-2">Links</PluginSidebarHeader>
        <div class="flex flex-wrap gap-2">
            <router-link v-if="plugin.spigot_link && plugin.spigot_link.toString().length > 0"
                         :to="`https://www.spigotmc.org/resources/${plugin.spigot_link}`"
                         target="_blank"
                         title="View this resource on SpigotMC">
                <Icon src="/assets/img/spigot.png"/>
            </router-link>
            <router-link v-if="plugin.github_link && plugin.github_link.length > 0"
                         :to="`https://github.com/${plugin.github_link}`"
                         target="_blank"
                         title="View the source of this resource on GitHub">
                <Icon src="/assets/img/github.svg"/>
            </router-link>
            <router-link :to="plugin.donation_url ?? 'https://www.gcnt.net/donate'"
                         target="_blank"
                         title="Support the author by donating">
                <Icon src="/assets/img/paypal.png"/>
            </router-link>
        </div>

    </Sidebar>
</template>

<script>

import Sidebar from "@/components/Common/Sidebar.vue";
import StickyFooter from "@/components/Common/StickyFooter.vue";
import {useAuth} from "@/store/authStore";
import StringService from "@/services/StringService";
import PluginSidebarHeader from "@/components/Pages/Plugins/PluginSidebarHeader.vue";
import PluginCategory from "@/components/Pages/Plugins/PluginCategory.vue";
import Icon from "@/components/Common/Icon/Icon.vue";

export default {
    name: "PluginSidebar",
    components: {Icon, PluginCategory, PluginSidebarHeader, StickyFooter, Sidebar},

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
            return this.plugin?.latest_update?.file_size ?? "No File Found";
        },
        categories() {
            return this.plugin?.categories?.split(',') ?? [];
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
