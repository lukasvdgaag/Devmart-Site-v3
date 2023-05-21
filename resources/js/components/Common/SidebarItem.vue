<template>
    <router-link v-if="item.renderRequirements" :key="item.id"
                 :class="{
                    'sidebar-active hover:bg-gray-300 dark:hover:bg-gray-600': this.isActive(this.item),
                    'lg:mr-3': marginRight,
                    'bg-gray-100 dark:bg-gray-800': this.highlight,
                    'hover:bg-gray-100 dark:hover:bg-gray-800': !this.isActive(this.item),
                 }" :to="item.link" active-class=""
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
        isDefault(item) {
            if (!this.links) return true;
            const def = this.links.find((link) => link.isDefault);
            if (!def || def !== item) return false;

            return this.links.filter(l => this.isActive(l, false)).length === 0;
        },
        isActive(item, checkForDefault = true) {
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
        highlight: {
            type: Boolean,
            default: false,
        }
    }
}
</script>

<style scoped>

</style>
