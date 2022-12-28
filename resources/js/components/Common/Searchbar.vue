<template>
    <div class="mt-2 my-0 mb-5 relative flex flex-row">
        <div class="relative w-full">
            <input class="bg-gray-250 rounded-lg border-none px-4 text-base py-3 w-full"
                   type="text"
                   :placeholder="placeholder"
                   :value="input"
                   @change="submit"
                   @input="updateInput($event.target.value)"
                   :disabled="disabled"
            >
            <div class="absolute top-3 right-4 h-6 w-6 cursor-pointer"
                 @click="clearInput">
                <font-awesome-icon class="text-black h-full w-full" icon="circle-xmark"/>
            </div>
        </div>
        <div class="bg-primary h-full min-h-[48px] aspect-square rounded-lg ml-2.5 cursor-pointer flex items-center justify-center"
             :class="{'bg-opacity-50': disabled}"
             @click="submit">
            <font-awesome-icon class="text-white text-2xl" icon="magnifying-glass"/>
        </div>
    </div>
</template>

<script>
export default {
    name: "Searchbar",
    emits: ['update:modelValue', 'input', 'submit'],

    data() {
        return {
            input: this.modelValue,
        }
    },

    props: {
        placeholder: {
            type: String,
            required: false,
            default: "Zoeken..."
        },
        modelValue: {
            type: String,
            required: false,
            default: ""
        },
        disabled: {
            type: Boolean,
            default: false,
        }
    },

    methods: {
        updateInput(value) {
            this.input = value;
            this.$emit('update:modelValue', this.input);
        },
        clearInput() {
            this.updateInput('');
            this.submit();
        },
        submit() {
            if (this.disabled) return;
            this.$emit('submit', this.input);
        }
    }
}
</script>
