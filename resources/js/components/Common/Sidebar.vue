<template>
    <div :class="[margin ? (leftSide ? 'lg:ml-12' : 'lg:mr-12') : '', leftSide ? 'lg:left-0' : 'lg:right-0']"
         class="lg:sticky lg:top-6 h-fit col-span-12 lg:col-span-3 mb-2 dark:text-gray-300">
        <slot/>
    </div>
</template>

<script>
export default {
    name: "Sidebar",
    props: {
        margin: {
            type: Boolean,
            default: false,
        },
        leftSide: {
            type: Boolean,
            default: true,
        },
    }
}
</script>

<style scoped>

</style>
