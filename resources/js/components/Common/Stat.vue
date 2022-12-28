<template>
    <div class="stat-dot" v-if="dot"></div>
    <div class="font-semibold text-gray-500" :class="{'text-sm mr-2': small, 'text-base mr-4': !small}">
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
