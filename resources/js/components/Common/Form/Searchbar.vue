<template>
    <div class="mt-2 my-0 mb-5 relative flex flex-row gap-2.5">
        <button v-if="filterButton"
                class="flex gap-3 w-fit break-keep min-h-[48px] h-full items-center justify-center bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition"
                data-dropdown-toggle="filter-dropdown"
                ref="filterButton"
                data-dropdown-placement="bottom-start"
                @click.prevent="$emit('clickFilter')">
            <font-awesome-icon icon="filter" class="text-gray-500"/>
            Filter
        </button>

        <div class="relative w-full">
            <input :placeholder="placeholder"
                   :value="input"
                   class="bg-gray-250 dark:bg-gray-800 rounded-lg border-none px-4 text-base py-3 w-full"
                   type="text"
                   @change="submit"
                   @input="updateInput($event.target.value)"
            >
            <div :class="[inputEmpty ? 'cursor-not-allowed' : 'cursor-pointer']"
                 class="absolute top-1 right-1 h-10 w-10 p-2 bg-gray-250 dark:bg-gray-800" role="button"
                 @click="clearInput">
                <font-awesome-icon :class="[!inputEmpty ? 'text-black dark:text-gray-200' : 'text-gray-400']" class="h-full w-full transition"
                                   icon="circle-xmark"/>
            </div>
        </div>
        <div :class="{'bg-opacity-50': disabled, 'cursor-not-allowed': inputEmpty || disabled}"
             class="bg-primary h-full min-h-[48px] aspect-square transition rounded-lg cursor-pointer flex items-center justify-center"
             @click="submit">
            <font-awesome-icon class="text-white text-2xl" icon="magnifying-glass"/>
        </div>
    </div>
</template>

<script>
export default {
    name: "Searchbar",
    emits: ['update:modelValue', 'input', 'submit', 'clickFilter'],

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
        },
        filterButton: {
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
