<template>
    <button class="bg-white dark:bg-gray-800 cursor-pointer text-gray-900 dark:text-gray-400 px-4 py-2 rounded-md flex items-center justify-between border
    border-gray-300 dark:border-gray-600 focus:ring focus:ring-opacity-50 focus:border focus:border-indigo-300 focus:ring-indigo-200"
            :class="this.class"
            role="button"
            :data-dropdown-toggle="id" type="button">
        <span>{{ value?.text ?? placeholder }}</span>
        <font-awesome-icon icon="fa-solid fa-chevron-down" class="ml-3 text-sm"/>
    </button>

    <div :id="id"
         class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600 absolute top-full left-0
         w-[calc(100%-1.5rem)] border border-gray-200 dark:border-gray-600"
         :class="{'md:w-60': !fullWidth}"
    >
        <div v-if="(header && header.length > 0) || (description && description.length > 0)" class="px-4 py-3">
            <div v-if="header && header.length > 0" class="text-md font-medium dark:text-gray-200">{{ header }}</div>
            <p v-if="description && description.length > 0" class="border-none dark:text-gray-400 leading-tight">{{ description }}</p>
        </div>

        <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownRadioHelperButton">
            <DropdownSelectItem
                v-for="(item, i) in items"
                :id="`${id}-${i}`"
                :name="`${id}-input`"
                :item="item"
                @change="handleInput"
                :key="i"
            />
        </ul>

    </div>
</template>

<script>
import DropdownSelectItem from "@/components/Common/Form/DropdownSelectItem.vue";
import DropdownSelectItemModel from "@/models/DropdownSelectItemModel";

export default {
    name: "DropdownSelect",
    components: {DropdownSelectItem},
    emits: ['update:modelValue'],

    data() {
        return {
            value: null,
        }
    },

    methods: {
        handleInput(value) {
            this.value = value;
            this.$emit('update:modelValue', value);
        }
    },

    props: {
        modelValue: {
            type: DropdownSelectItemModel,
            required: false,
        },
        id: {
            type: String,
            required: true
        },
        placeholder: {
            type: String,
            required: false,
            default: 'select'
        },
        items: {
            type: Array,
            required: true,
        },
        header: {
            type: String,
            required: false,
            default: ''
        },
        description: {
            type: String,
            required: false,
            default: '',
        },
        class: {
            type: String,
            required: false,
            default: '',
        },
        fullWidth: {
            type: Boolean,
            required: false,
            default: false,
        }
    }
}
</script>

<style scoped>

</style>
