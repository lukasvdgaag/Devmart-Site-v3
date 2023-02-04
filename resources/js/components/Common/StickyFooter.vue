<template>
    <div class="download-button-small bottom-0 left-0 w-full p-5 border-t border-t-gray-75 dark:border-t-gray-800 mt-6
    flex flex-col align-center bg-white dark:bg-gray-900"
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
