<template>
    <textarea v-if="isTextarea"
              :class="[
               hasError ? 'border-red-300 focus:border-red-300 focus:ring-red-200' : 'border-gray-300 focus:border-indigo-300 focus:ring-indigo-200',
               type === 'checkbox' ? 'p-2.5 rounded-md' : '',
           ]"
              :disabled="disabled"
              :maxlength="maxlength"
              :name="name"
              :value="modelValue"
              class="rounded-md shadow-sm focus:ring focus:ring-opacity-50 checked:bg-blue-600 w-full  dark:text-gray-300 dark:bg-gray-800 dark:border-gray-700"
              @input="onInput($event)"></textarea>

    <input v-else
           :class="[
               hasError ? 'border-red-300 focus:border-red-300 focus:ring-red-200' : 'border-gray-300 focus:border-indigo-300 focus:ring-indigo-200',
               type === 'checkbox' ? 'p-2.5 rounded-md' : '',
               disabled ? 'bg-gray-100 dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600' : 'bg-white dark:text-gray-300 dark:bg-gray-800'
           ]"
           :disabled="disabled"
           :maxlength="maxlength"
           :type="type"
           :name="name"
           :value="modelValue"
           class="!rounded-md shadow-sm focus:ring dark:border-gray-700 focus:ring-opacity-50 checked:bg-blue-600"
           @change="onChange($event)"
           @input="onInput($event)"
    />
</template>

<script>
export default {
    name: "Input",
    emits: ['update:modelValue', 'input'],

    props: {
        modelValue: {
            required: false
        },
        errors: {
            type: Object,
            required: false
        },
        item: {
            type: String,
            required: false
        },
        type: {
            type: String,
            required: false,
            default: 'text'
        },
        isTextarea: {
            type: Boolean,
            default: false
        },
        disabled: {
            type: Boolean,
            default: false
        },
        maxlength: {
            type: String,
            required: false
        },
        name: {
            type: String,
            required: false
        }
    },

    computed: {
        hasError() {
            return this.errors && this.errors[this.item];
        }
    },

    methods: {
        onChange(event) {
            if (event.target.type === "checkbox") this.$emit('update:modelValue', event.target.checked)
        },
        onInput(event) {
            this.$emit('update:modelValue', event.target.value);
            this.$emit('input', event);
        }
    }
}
</script>

<style scoped>

</style>
