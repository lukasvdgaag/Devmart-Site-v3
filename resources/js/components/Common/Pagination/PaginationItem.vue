<template>
    <router-link :to="{query: getQuery()}"
                 @click.prevent="handleClick"
                 class="relative inline-flex items-center border px-4 py-2 text-sm font-medium plain"
                 :class="{
        'focus:z-20': disabled,
        'z-1 border-indigo-500 bg-indigo-50 text-indigo-600': active,
        'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-300': !active,
        'hover:bg-gray-50 hover:dark:bg-gray-700': !disabled && !active,
        'cursor-pointer': !disabled,
        'rounded-md': $slots.default
    }">
        <slot v-if="$slots.default"/>
        <template v-else>
            {{ page }}
        </template>
    </router-link>
</template>

<script>
export default {
    name: "PaginationItem",

    methods: {
        getQuery() {
            const query = this.$route.query;
            if (!this.disabled && typeof this.page === 'number') query.page = this.page;
            return query;
        },
        handleClick() {
            if (!this.disabled) this.$emit('update', this.page);
        }
    },

    props: {
        page: {
            type: [Number, String],
            required: true
        },
        active: {
            type: Boolean,
            required: false,
            default: false
        },
        disabled: {
            type: Boolean,
            required: false,
            default: false
        },
    }
}
</script>

<style scoped>

</style>
