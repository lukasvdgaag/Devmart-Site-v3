<template>
    <div>
        <template v-for="(route, index) in matched" :key="index">
            <router-link :to="getRouteLink(route)" class="capitalize static">{{ getRouteName(route) }}</router-link>

            <span v-if="index < matched.length - 1" class="mx-2">/</span>
        </template>
    </div>
</template>

<script>
import StringService from "@/services/StringService";

export default {
    name: "WikiCurrentPage",

    computed: {
        matched() {
            const matched = this.$route.matched;
            return matched?.filter((m, i) => matched.findIndex(o => o.path === m.path) === i) ?? [];
        }
    },

    methods: {
        getRouteName(route) {
            return StringService.getWikiSidebarItemName(route);
        },
        getRouteLink(route) {
            if (route.path.startsWith('/wiki')) return route.path;
            return '/wiki' + route.path;
        }
    }
}
</script>

<style scoped>

</style>
