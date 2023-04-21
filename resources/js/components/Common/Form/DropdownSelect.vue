<template>
    <button :class="this.class"
            :data-dropdown-toggle="id"
            class="bg-white dark:bg-gray-800 cursor-pointer text-gray-900 dark:text-gray-400 px-4 py-2 rounded-md flex items-center justify-between border
    border-gray-300 dark:border-gray-600 focus:ring focus:ring-opacity-50 focus:border focus:border-indigo-300 focus:ring-indigo-200"
            role="button" type="button">
        <span>{{ value?.text ?? placeholder }}</span>
        <font-awesome-icon class="ml-3 text-sm" icon="fa-solid fa-chevron-down"/>
    </button>

    <Dropdown :id="id" :full-width="fullWidth" :header="header" :description="description">
        <ul aria-labelledby="dropdownRadioHelperButton" class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200">
            <DropdownSelectItem
                v-for="(item, i) in items"
                :id="`${id}-${i}`"
                :key="i"
                :item="item"
                :name="`${id}-input`"
                :selected-value="value"
                @change="handleInput"
            />
        </ul>
    </Dropdown>
</template>

<script>
import DropdownSelectItem from "@/components/Common/Form/DropdownSelectItem.vue";
import DropdownSelectItemModel from "@/models/DropdownSelectItemModel";
import Dropdown from "@/components/Common/Form/Dropdown.vue";

export default {
    name: "DropdownSelect",
    components: {Dropdown, DropdownSelectItem},
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

    watch: {
        modelValue(val) {
            this.value = val;
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
