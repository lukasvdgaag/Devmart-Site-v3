<template>
    <router-link v-if="item.renderRequirements" :key="item.id" :class="{'sidebar-active': this.isActive(), 'lg:mr-3': marginRight}" :to="item.link" active-class=""
                 class="py-3 px-4 my-2 rounded-lg flex flex-row items-center plain"
                 exact-active-class="">
        <div v-if="item.icon" class="preview-link-icon flex justify-center">
            <font-awesome-icon :icon="item.icon"/>
        </div>
        <div class="preview-link-title">{{ item.label }}</div>
    </router-link>
</template>

<script>
import SidebarItem from "@/models/SidebarItem";

export default {
    name: "SidebarItem",

    methods: {
        isDefault() {
            if (!this.links) return true;
            const def = this.links.find((link) => link.isDefault);
            if (!def || def !== this.item) return false;

            return this.links.filter(l => this.isActive(false)).length === 0;
        },
        isActive(checkForDefault = true) {
            if (this.item.activeRequirements) return true;
            if (checkForDefault && this.isDefault(this.item)) return true;

            if ('name' in this.item.link && this.$route.matched.filter(i => i.name === this.item.link.name).length === 0) return false;
            if ('query' in this.item.link) {
                for (let key in this.item.link.query) {
                    if (this.$route.query[key] !== this.item.link.query[key]) return false;
                }
            }
            return true;
        }
    },

    props: {
        item: {
            type: SidebarItem,
            required: true,
        },
        links: {
            type: Array,
            required: false,
        },
        marginRight: {
            type: Boolean,
            default: true,
        },
    }
}
</script>

<style scoped>

</style>
