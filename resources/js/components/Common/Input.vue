<template>
    <input :value="modelValue"
           @change="onChange($event)"
           @input="$emit('update:modelValue', $event.target.value)"
           class="rounded-md shadow-sm focus:ring focus:ring-opacity-50"
           :class="[hasError ? 'border-red-300 focus:border-red-300 focus:ring-red-200' : 'border-gray-300 focus:border-indigo-300 focus:ring-indigo-200']"
    />
</template>

<script>
export default {
    name: "Input",
    emits: ['update:modelValue'],

    props: ['modelValue', 'errors', 'item'],

    computed: {
        hasError() {
            return this.errors && this.errors[this.item];
        }
    },

    methods: {
        onChange(event) {
            if (event.target.type === "checkbox") this.$emit('update:modelValue', event.target.checked)
        }
    }
}
</script>

<style scoped>

</style>
