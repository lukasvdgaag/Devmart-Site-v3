<template>
    <div class="w-full flex flex-col items-center m-0 p-0 relative h-full">
        <Navbar :background="false"/>

        <HeaderBackground subtitle="Easily share text." title="Paste"/>

        <div class="d-grid !px-4 mt-6 h-full">
            <Sidebar :left-side="true" :margin="false" class="mb-4 lg:mb-2 lg:row-start-1">
                <SidebarItem :target="isCreatePage ? '_blank' : '_self'"
                             :item="new SidebarItemModel('/paste', 'plus', 'Create new paste')"
                             :margin-right="false"
                />
                <SidebarItem v-if="useAuth().loggedIn"
                             :target="isCreatePage ? '_blank' : '_self'"
                             :item="new SidebarItemModel({name: 'accountPastes'}, 'fa-paste', 'Your pastes', true, false, false)"
                             :links="[]"
                             :margin-right="false"
                             highlight
                />
                <hr class="hidden lg:block"/>

                <RecentPastesList class="hidden lg:block" :is-create-page="isCreatePage"/>
            </Sidebar>
            <div class="col-span-12 lg:col-span-9 flex">
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
import PluginSidebarHeader from "@/components/Pages/Plugins/PluginSidebarHeader.vue";
import PasteCreatePage from "@/components/Pages/Paste/PasteCreatePage.vue";
import SidebarItem from "@/components/Common/SidebarItem.vue";
import {default as SidebarItemModel} from "@/models/SidebarItem";
import RecentPastesList from "@/components/Pages/Paste/RecentPastesList.vue";

export default {
    name: "PastePageLayout",
    computed: {
        SidebarItemModel() {
            return SidebarItemModel
        }
    },
    components: {RecentPastesList, SidebarItem, PluginSidebarHeader, Sidebar, HeaderBackground, Navbar},

    created() {
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
    }
}
</script>

<style scoped>

</style>
