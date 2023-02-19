<template>
    <Sidebar :left-side="false" :margin="true" class="mb-6 lg:mb-2">
        <StickyFooter :fixed="true" class="block lg:hidden">
            <router-link v-if="showDownloadLink" :to="downloadLink" class="action-button primary btn-text-full flex flex-col gap-0 align-center !mb-0">
                <span>{{ downloadLabel }}</span>
                <span class="text-[10px] leading-3">{{ downloadInfo }}</span>
            </router-link>
        </StickyFooter>

        <Hr class="lg:hidden"/>

        <router-link v-if="showDownloadLink" :to="downloadLink" class="action-button primary btn-text-full flex-col gap-0 align-center hidden lg:flex"
                     target="_blank">
            <span class="leading-4">{{ downloadLabel }}</span>
            <span class="text-[10px] leading-3">{{ downloadInfo }}</span>
        </router-link>
        <router-link :to="`/plugins/${plugin.id}/updates`"
                     class="action-button gray-hollow flex-col align-center hidden lg:flex"><span>Updates</span>
        </router-link>

        <template v-if="canModify">
            <Hr class="hidden lg:block"/>
            <router-link :to="{name: 'update-plugin', params: {pluginId: plugin.id}}"
                         class="action-button purple flex-col align-center hidden lg:flex"><span>Post Update</span></router-link>
            <router-link :to="{name: 'edit-plugin', params: {pluginId: plugin.id}}"
                         class="action-button purple flex-col align-center hidden lg:flex"><span>Edit Plugin</span></router-link>
        </template>

        <Hr class="hidden lg:block"/>

        <div v-if="plugin?.sale" class="flex mb-4 mt-[-0.5rem] gap-1 flex-wrap">
            <PluginLabel :background="`bg-red-400`" :label="`${plugin?.sale?.percentage.toFixed(0)}% Sale`"/>
        </div>
        <PluginSidebarHeader class="mt-2">Author</PluginSidebarHeader>
        <div>{{ plugin.author_username }}</div>

        <template v-if="plugin?.price > 0">
            <PluginSidebarHeader class="mt-2">Price</PluginSidebarHeader>
            <div>
                {{ StringService.formatMoney(price, false) }}
                <span v-if="plugin?.sale" class="ml-1 text-red-400 line-through">{{ StringService.formatMoney(plugin.price, false) }}</span>
            </div>
        </template>

        <PluginSidebarHeader class="mt-2">Version</PluginSidebarHeader>
        <div>{{ plugin?.latest_update?.version }}</div>

        <PluginSidebarHeader class="mt-2">Last Updated</PluginSidebarHeader>
        <div>{{ DateService.formatDateRelatively(new Date(plugin.last_updated), true) }}</div>

        <template v-if="categories.length > 0">
            <PluginSidebarHeader class="mt-8 mb-2">Categories</PluginSidebarHeader>
            <div class="flex flex-wrap gap-2">
                <PluginCategory v-for="category in categories" :key="category">{{ category }}</PluginCategory>
            </div>
        </template>

        <PluginSidebarHeader class="mt-8 mb-2">Links</PluginSidebarHeader>
        <div class="flex flex-wrap gap-2">
            <a v-if="plugin.spigot_link && plugin.spigot_link.toString().length > 0"
               :href="`https://www.spigotmc.org/resources/${plugin.spigot_link}`"
               target="_blank"
               title="View this resource on SpigotMC">
                <Icon src="/assets/img/spigot.png"/>
            </a>
            <a v-if="plugin.github_link && plugin.github_link.length > 0"
               :href="`https://github.com/${plugin.github_link}`"
               target="_blank"
               title="View the source of this resource on GitHub">
                <Icon src="/assets/img/github.svg"/>
            </a>
            <a :href="plugin.donation_url ?? 'https://www.gcnt.net/donate'"
               target="_blank"
               title="Support the author by donating">
                <Icon src="/assets/img/paypal.png"/>
            </a>
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
import PluginLabel from "@/components/Pages/Plugins/PluginLabel.vue";
import PluginPermissions from "@/models/rest/PluginPermissions";
import Plugin from "@/models/rest/Plugin";
import DateService from "../../../services/DateService";
import Hr from "@/components/Common/Hr.vue";

export default {
    name: "PluginSidebar",
    components: {Hr, PluginLabel, Icon, PluginCategory, PluginSidebarHeader, StickyFooter, Sidebar},

    computed: {
        DateService() {
            return DateService
        },
        StringService() {
            return StringService
        },
        showDownloadLink() {
            return this.canDownload || !this.plugin.custom || !this.price > 0;
        },
        canDownload() {
            return this.permissions?.download;
        },
        canModify() {
            return this.permissions?.modify;
        },
        downloadLabel() {
            if (this.canDownload) return "Download";

            const formattedPrice = StringService.formatMoney(this.price, false);
            if (!useAuth().loggedIn) return "Login To Buy for " + formattedPrice;
            return "Buy for " + formattedPrice;
        },
        downloadLink() {
            // when free/logged in and can download, return direct link
            if (this.canDownload) return `/plugins/${this.plugin.id}/download`;

            // when not logged in, return login link with redirect
            if (!useAuth().loggedIn) return {
                name: 'login',
                query: {redirect: this.$route.fullPath}
            }

            // otherwise return buy link
            return "#";
        },
        downloadInfo() {
            let fileSize = this.plugin?.latest_update?.file_size;
            return fileSize ? StringService.formatFileSize(fileSize) + ` (.${this.plugin.latest_update.file_extension})` : "No File Found";
        },
        categories() {
            return this.plugin?.categories?.split(',') ?? [];
        },
        price() {
            return (this.plugin?.price / 100) * (100 - (this.plugin?.sale?.percentage ?? 0));
        },
    },

    props: {
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
