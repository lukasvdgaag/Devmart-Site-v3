<template>
    <label class="switch mt-0" :class="{small: small}">
        <input type="checkbox"
               :name="name"
               :id="id"
               @change="this.$emit('update:modelValue', $event.target.checked)"
               :value="modelValue"
               :selected="selected">
        <span class="slider"></span>
    </label>
</template>

<script>
export default {
    name: "SwitchInput",
    props: {
        modelValue: {
            type: Boolean,
            default: false,
        },
        selected: {
            type: Boolean,
            default: false,
        },
        small: {
            type: Boolean,
            default: false,
        },
        name: String,
        id: String,
    },
}
</script>

<style scoped>

</style>
