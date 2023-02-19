<template>

    <nav class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
        <ul class="flex flex-wrap gap-2 -mb-px">
            <li>
                <router-link :to="{ name: 'plugin-overview'}" exact exact-active-class="active">Overview</router-link>
            </li>
            <li>
                <router-link :to="{ name: 'plugin-updates'}" active-class="active">Updates</router-link>
            </li>
            <li>
                <router-link :to="{ name: 'plugin-versions'}" active-class="active">Versions</router-link>
            </li>
        </ul>
    </nav>


</template>

<script>
export default {
    name: "PluginQuickNavigation"
}
</script>

<style scoped>
    li {
        @apply flex-grow sm:flex-grow-0;
    }
    a {
        @apply inline-block w-full sm:w-fit p-2 sm:p-4 border-b rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300;
    }
    a.active {
        @apply text-primary-600 border-blue-600 dark:text-blue-500 dark:border-blue-500;
    }
</style>
