<template>
    <component :is="external ? 'a' : 'router-link'"
               :to="!external ? url : ''"
               :href="external ? url : ''"
               :target="external ? '_blank' : ''"
               class="quicklink col-span-1">
        <div>{{title}}</div>
        <font-awesome-icon icon="fa-arrow-right" class="text-lg"/>
    </component>
</template>

<script>
export default {
    name: "WikiQuicklink",

    props: {
        title: {
            type: String,
            required: true
        },
        url: {
            type: [String, Object],
            required: true
        },
        external: {
            type: Boolean,
            default: false
        }
    }
}
</script>

<style scoped>

</style>
