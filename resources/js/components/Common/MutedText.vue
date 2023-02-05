<template>
    <div class="text-base text-gray-500 dark:text-gray-700" :class="{'mt-2': marginTop}">
        <slot/>
    </div>
</template>

<script>
export default {
    name: "MutedText",

    props: {
        marginTop: {
            type: Boolean,
            default: true,
        }
    }
}
</script>

<style scoped>

</style>
