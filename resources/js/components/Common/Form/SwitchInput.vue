<template>
    <label :class="{small: small}" class="switch mt-0">
        <input :id="id"
               :checked="checked"
               :name="name"
               :value="modelValue"
               type="checkbox"
               @change="this.$emit('update:modelValue', $event.target.checked)">
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
        checked: {
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
