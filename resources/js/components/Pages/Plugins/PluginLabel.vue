<template>
    <div class="plugin-preview-label flex flex-row align-items-center">
        <font-awesome-icon v-if="icon" :icon="icon" class="preview-label-icon"/>
        <span class="preview-label-text">{{ label}}</span>
    </div>
</template>

<script>
export default {
    name: "PluginLabel",

    props: {
        label: {
            type: String,
            required: true,
        },
        icon: {
            type: String,
            required: false,
        }
    }
}
</script>

<style scoped>

</style>
