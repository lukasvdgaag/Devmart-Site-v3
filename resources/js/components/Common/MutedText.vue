<template>
    <div :class="{'mt-2': marginTop}" class="text-base muted">
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
