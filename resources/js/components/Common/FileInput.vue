<template>
    <Input class="mt-2 text-sm file:bg-black file:text-white file:rounded-2xl hover:file:bg-gray-800 file:font-poppins
                            file:transition cursor-pointer file:px-2 file:py-1 file:mr-2 w-full block"
           type="file"
           :value="modelValue"
           :accept="accept"
           :errors="errors"
           :item="item"
           @input="uploadFile($event)"
    />
</template>

<script>
import Input from "@/components/Common/Input.vue";

export default {
    name: "FileInput",
    components: {Input},

    props: {
        modelValue: {
            type: String,
            default: null,
        },
        accept: {
            type: String,
            default: '*',
        },
        errors: {
            type: Object,
            default: {},
        },
        item: {
            type: String,
            default: null,
        }
    },
    emits: ['update:modelValue', 'upload'],

    methods: {
        uploadFile(event) {
            this.$emit('update:modelValue', event.target.value);
            this.$emit('upload', event.target.files[0] ?? null);
        }
    }
}
</script>

<style scoped>

</style>
