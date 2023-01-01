<template>
    <Sidebar :margin="margin">
        <template v-for="item in links">
            <router-link :to="item.link" :key="item.id" v-if="item.renderRequirements" active-class="" exact-active-class=""
                         class="py-3 px-4 my-2 lg:mr-3 rounded-lg flex flex-row align-items-center plain"
                         :class="{'sidebar-active': this.isActive(item)}">
                <div v-if="item.icon" class="preview-link-icon flex justify-content-center">
                    <font-awesome-icon :icon="item.icon"/>
                </div>
                <div class="preview-link-title">{{ item.label }}</div>
            </router-link>
        </template>
    </Sidebar>
</template>

<script>
import Sidebar from "@/components/Common/Sidebar.vue";

export default {
    name: "SidebarLinks",
    components: {Sidebar},

    methods: {
        isDefault(item) {
            const def = this.links.find((link) => link.isDefault);
            if (!def || def !== item) return false;

            return this.links.filter(l => this.isActive(l,false)).length === 0;
        },
        isActive(item, checkForDefault=true) {
            if (item.activeRequirements) return true;
            if (checkForDefault && this.isDefault(item)) return true;

            if ('name' in item.link && this.$route.matched.filter(i => i.name === item.link.name).length === 0) return false;
            if ('query' in item.link) {
                for (let key in item.link.query) {
                    if (this.$route.query[key] !== item.link.query[key]) return false;
                }
            }
            return true;
        }
    },

    props: {
        margin: {
            type: Boolean,
            default: false,
        },
        links: {
            type: Array,
            default: () => [],
        },
    }
}
</script>

<style scoped>

</style>
