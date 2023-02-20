<template>
    <div class="flex flex-col align-center h-8 px-4 bg-gray-100 dark:bg-gray-800 rounded capitalize">
        <span class="text-sm text-neutral-600 dark:text-gray-400"><slot/></span>
    </div>
</template>

<script>
export default {
    name: "PluginCategory"
}
</script>
