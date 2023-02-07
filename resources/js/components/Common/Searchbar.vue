<template>
    <div class="mt-2 my-0 mb-5 relative flex flex-row">
        <div class="relative w-full">
            <input class="bg-gray-250 dark:bg-gray-800 rounded-lg border-none px-4 text-base py-3 w-full"
                   type="text"
                   :placeholder="placeholder"
                   :value="input"
                   @change="submit"
                   @input="updateInput($event.target.value)"
            >
            <div class="absolute top-3 right-4 h-6 w-6"
                 :class="[inputEmpty ? 'cursor-not-allowed' : 'cursor-pointer']"
                 @click="clearInput">
                <font-awesome-icon class="h-full w-full transition" :class="[!inputEmpty ? 'text-black dark:text-gray-200' : 'text-gray-400']" icon="circle-xmark"/>
            </div>
        </div>
        <div class="bg-primary h-full min-h-[48px] aspect-square transition rounded-lg ml-2.5 cursor-pointer flex items-center justify-center"
             :class="{'bg-opacity-50': disabled, 'cursor-not-allowed': inputEmpty || disabled}"
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

    computed: {
      inputEmpty() {
          return this.input.length === 0;
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
            if (this.input === "") return;
            this.updateInput('');
            this.submit(true);
        },
        submit(force = false) {
            if (!force && this.disabled) return;
            this.$emit('submit', this.input);
        }
    }
}
</script>
