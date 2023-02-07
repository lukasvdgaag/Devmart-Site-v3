<template>
    <div class="rounded-full w-2 h-2 mr-2 flex-shrink-0 bg-gray-300 dark:bg-gray-700" v-if="dot"></div>
    <div class="font-semibold text-gray-500 dark:text-gray-400" :class="{'text-sm mr-2': small, 'text-base mr-4': !small}">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "Stat",

    props: {
        dot: {
            type: Boolean,
            default: true,
        },
        small: {
            type: Boolean,
            default: false,
        },
    }
}
</script>

<style scoped>

</style>
