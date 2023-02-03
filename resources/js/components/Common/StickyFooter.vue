<template>
    <div class="download-button-small mt-6 flex flex-col align-center bg-white dark:bg-gray-900"
         :class="{sticky: !fixed, fixed: fixed}">
        <slot/>
    </div>
</template>

<script>
export default {
    name: "StickyFooter",

    props: {
        fixed: {
            type: Boolean,
            default: false
        }
    }
}
</script>

<style scoped>

</style>
