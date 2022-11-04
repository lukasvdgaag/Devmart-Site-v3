<template>
    <label class="block font-medium text-sm text-gray-700">
        <template v-if="value != null">{{ value }}</template>
        <slot v-else/>
    </label>
</template>

<script>
export default {
    name: "Label",

    props: {
        value: {
            type: String,
            default: null
        }
    }
}
</script>

<style scoped>

</style>
