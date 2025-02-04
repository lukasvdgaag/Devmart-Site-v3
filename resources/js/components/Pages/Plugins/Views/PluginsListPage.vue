<template>
    <div class="flex flex-row">
        <div class="w-full flex flex-col items-center m-0 p-0">
            <Navbar/>

            <HeaderBackground subtitle="Our selection of high quality plugins" title="Plugins"/>

            <div class="d-grid !grid-cols-12 mb-6 mt-4">
                <!-- Carousel -->
                <div class="hidden featured-plugins mb-4 mt-2 col-span-12 text-center">
                    <div class="flex flex-row align-center">
                        <img alt="Banner image"
                             class="plugin-preview-banner"
                             src="https://cdn.discordapp.com/discovery-splashes/536178805828485140/e3cf88323111aa759f8764230c3c440c.jpg?size=2048">
                        <img alt="Banner image"
                             class="plugin-preview-banner"
                             src="https://cdn.discordapp.com/discovery-splashes/536178805828485140/e3cf88323111aa759f8764230c3c440c.jpg?size=2048">
                        <img alt="Banner image"
                             class="plugin-preview-banner"
                             src="https://cdn.discordapp.com/discovery-splashes/536178805828485140/e3cf88323111aa759f8764230c3c440c.jpg?size=2048">
                    </div>
                </div>

                <Sidebar :links="sidebarLinks"/>
                <div class="col-span-12 lg:col-span-9 w-full">
                    <Searchbar v-model="pluginsFetchable.query"
                               :disabled="!pluginsFetchable.canRequest()"
                               placeholder="Find a plugin..."
                               @submit="updateSearch"/>

                    <div class="w-full col-gap-4 mt-2 text-xl font-bold">
                        {{ totalPlugins }} Plugins Found <span v-if="pluginsFetchable.page > 1">- Page {{ pluginsFetchable.page }}</span>
                    </div>

                    <div class="flex gap-y-5 mt-2 flex-col">
                        <PluginPreview v-for="plugin in plugins" :key="plugin.id" :plugin="plugin"/>
                    </div>

                    <Pagination
                        :current-page="pluginsFetchable?.page ?? 1"
                        :fetchable="pluginsFetchable"
                        :last-page="pages"
                        :per-page="6"
                        :total="totalPlugins"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import {useAuth} from "@/store/authStore";
import Sidebar from "@/components/Common/SidebarLinks.vue";
import Searchbar from "@/components/Common/Form/Searchbar.vue";
import SidebarItem from "@/models/SidebarItem";
import PluginRepository from "@/services/PluginRepository";
import PluginPreview from "@/components/Pages/Plugins/PluginPreview.vue";
import PluginsFetchable from "@/models/fetchable/PluginsFetchable";
import Pagination from "@/components/Common/Pagination/Pagination.vue";
import HeaderBackground from "@/components/Common/HeaderBackground.vue";
import SeoBuilder from "@/services/SeoBuilder";

export default {
    name: "PluginsListPage",
    components: {HeaderBackground, Pagination, PluginPreview, Searchbar, Sidebar, Navbar},

    created() {
        Promise.all([
            this.fetchPlugins(),
        ])
    },

    data() {
        return {
            user: useAuth().user,
            pluginsFetchable: new PluginsFetchable(
                this.queryPlugins,
                this.$route.query.query ?? "",
                Number.parseInt(this.$route.query.page ?? '1'),
                this.$route.query.filter ?? 'all',
            ),
            /**
             * @type {Plugin[]}
             */
            plugins: [],
            totalPlugins: 0,
            pages: 0,
        }
    },

    head() {
        return new SeoBuilder(this)
            .title("Plugins")
            .withReturn()
            .build()
    },

    watch: {
        '$route.query.filter'() {
            this.pluginsFetchable.filter = this.$route.query.filter;
            this.fetchPlugins();
        },
        '$route.query.page'() {
            console.log(this.$route.query.page);
            this.pluginsFetchable.page = Number.parseInt(this.$route.query.page ?? '1');
        }
    },

    computed: {
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
                query: this.getQuery(this.pluginsFetchable.filter)
            });

            await this.fetchPlugins();
        },
        async fetchPlugins() {
            await this.pluginsFetchable.fetch(this);
        },
        async queryPlugins() {
            const response = await PluginRepository.fetchPlugins(this.pluginsFetchable.filter, this.pluginsFetchable.query, this.pluginsFetchable.page);
            this.plugins = response.plugins;
            this.totalPlugins = response.total;
            this.pages = response.pages;
        },
        getQuery(filter) {
            return {
                ...this.$route.query,
                filter: filter,
                page: undefined,
                query: this.pluginsFetchable.query
            }
        },
    }
}
</script>

<style scoped>

</style>
