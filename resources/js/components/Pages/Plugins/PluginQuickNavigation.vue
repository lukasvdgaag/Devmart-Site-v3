<template>

    <nav class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
        <ul class="flex flex-wrap gap-2 -mb-px">
            <li>
                <router-link :to="{ name: 'plugin-overview'}" exact exact-active-class="active" class="plain">Overview</router-link>
            </li>
            <li>
                <router-link :to="{ name: 'plugin-updates'}" active-class="active" class="plain">Updates</router-link>
            </li>
            <li>
                <router-link :to="{ name: 'plugin-versions'}" active-class="active" class="plain">Versions</router-link>
            </li>
            <li v-if="(permissions?.modify ?? false) && plugin?.canBePurchased()">
                <router-link :to="{ name: 'plugin-purchases'}" active-class="active" class="plain">Purchases</router-link>
            </li>
        </ul>
    </nav>


</template>

<script>
import PluginPermissions from "@/models/rest/PluginPermissions";

export default {
    name: "PluginQuickNavigation",

    props: {
        permissions: {
            type: [PluginPermissions, null],
            required: true,
        },
        plugin: {
            type: Object,
            required: true,
        },
    }
}
</script>

<style scoped>
    li {
        @apply flex-grow sm:flex-grow-0;
    }
    a {
        @apply inline-block w-full sm:w-fit p-2 sm:p-4 border-b dark:border-b-gray-700 rounded-t-lg hover:text-gray-600 hover:border-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-500;
    }
    a.active {
        @apply text-primary-600 border-blue-600 dark:text-blue-500 dark:border-blue-500;
    }
</style>
