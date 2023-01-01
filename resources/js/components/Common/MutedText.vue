<template>
    <div class="text-base muted" :class="{'mt-2': marginTop}">
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
