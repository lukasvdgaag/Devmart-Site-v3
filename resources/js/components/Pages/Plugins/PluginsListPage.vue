<template>
    <div class="flex flex-row">
        <div class="w-full flex items-center m-0 p-0">
            <Navbar :background="true"/>

            <div class="d-grid !grid-cols-12 mb-6">
                <div class="col-span-12 text-center">
                    <h1 class="page-title">Plugins</h1>
                    <p class="page-subtitle">Here you can find our selection of high quality plugins</p>
                </div>

                <!-- Carousel -->
                <div class="featured-plugins mb-4 col-span-12 text-center">
                    <div class="flex flex-row align-center">
                        <img src="https://cdn.discordapp.com/discovery-splashes/536178805828485140/e3cf88323111aa759f8764230c3c440c.jpg?size=2048"
                             alt="Banner image"
                             class="plugin-preview-banner">
                        <img src="https://cdn.discordapp.com/discovery-splashes/536178805828485140/e3cf88323111aa759f8764230c3c440c.jpg?size=2048"
                             alt="Banner image"
                             class="plugin-preview-banner">
                        <img src="https://cdn.discordapp.com/discovery-splashes/536178805828485140/e3cf88323111aa759f8764230c3c440c.jpg?size=2048"
                             alt="Banner image"
                             class="plugin-preview-banner">
                    </div>
                </div>

                <Sidebar :links="sidebarLinks"/>
                <div class="col-span-12 lg:col-span-9 w-full">
                    <Searchbar placeholder="Find a plugin..." v-model="searchQuery" @submit="updateSearch"/>

                    <div class="w-full col-gap-4 mt-2 text-xl font-bold">
                        {{ totalPlugins }} Plugins Found <span v-if="currentPage > 1">- Page {{ currentPage }}</span>
                    </div>

                    <div class="flex gap-y-5 mt-2 flex-col">
                        <PluginPreview v-for="plugin in plugins" :plugin="plugin" :key="plugin.id"/>
                    </div>

                    <!-- TODO: Add pagination -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import {useAuth} from "@/store/authStore";
import Sidebar from "@/components/Common/Sidebar.vue";
import Searchbar from "@/components/Common/Searchbar.vue";
import SidebarItem from "@/models/SidebarItem";
import PluginRepository from "@/services/PluginRepository";
import PluginPreview from "@/components/Pages/Plugins/PluginPreview.vue";

export default {
    name: "PluginsListPage",
    components: {PluginPreview, Searchbar, Sidebar, Navbar},

    created() {
        this.searchQuery = this.$route.query.query;

        Promise.all([
            this.fetchPlugins()
        ])
    },

    data() {
        return {
            user: useAuth().user,
            searchQuery: '',
            plugins: [],
            totalPlugins: 0,
            pages: 0,
        }
    },

    watch: {
      '$route.query.filter'() {
          this.fetchPlugins();
      }
    },

    computed: {
        selectedFilter() {
            return this.$route.query.filter ?? "all";
        },
        currentPage() {
            return Math.max(1, this.$route.query.page ?? 1);
        },
        sidebarLinks() {
            return [
                new SidebarItem({query: this.getQuery(undefined)}, 'fa-compass', 'All', true, true),
                new SidebarItem({query: this.getQuery('purchased')}, 'fa-cart-shopping', 'Purchased', useAuth().user != null, false, SidebarItem.isQueryParam('filter', 'purchased')),
                new SidebarItem({query: this.getQuery('premium')}, 'fa-gem', 'Premium', true, false, SidebarItem.isQueryParam('filter', 'premium')),
                new SidebarItem({query: this.getQuery('free')}, 'fa-bolt', 'Free', true, false, SidebarItem.isQueryParam('filter', 'free')),
            ];
        }
    },
    methods: {
        async updateSearch() {
            this.$router.replace({
                query: this.getQuery(this.selectedFilter)
            });

            await this.fetchPlugins();
        },
        async fetchPlugins() {
            const response = await PluginRepository.fetchPlugins(this.selectedFilter, this.searchQuery, this.currentPage);
            this.plugins = response.data.plugins;
            this.totalPlugins = response.data.total;
            this.pages = response.data.pages;
        },
        getQuery(filter) {
            return {
                ...this.$route.query,
                filter: filter,
                page: undefined,
                query: this.searchQuery
            }
        },
    }
}
</script>

<style scoped>

</style>
