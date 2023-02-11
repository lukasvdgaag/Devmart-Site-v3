<template>
    <button
        v-if="type === 'button'"
        class="navbar-popup-item"
        :class="[background ? 'navbar-popup-item-nobg': 'navbar-popup-item-bg']"
        type="button">
        <font-awesome-icon :icon="icon" class="text-xs min-w-3"/>
        <span>{{label}}</span>
    </button>
    <router-link v-else :to="to" class="navbar-popup-item" :class="[background ? 'navbar-popup-item-nobg': 'navbar-popup-item-bg']">
        <font-awesome-icon :icon="icon" class="text-xs min-w-3"/>
        <span>{{label}}</span>
    </router-link>
</template>

<script>
export default {
    name: "NavPopupItem",

    props: {
        type: {
            type: String,
            default: "button"
        },
        icon: {
            type: String,
            required: true,
        },
        label: {
            type: String,
            required: true,
        },
        background: {
            type: Boolean,
            default: false,
        },
        to: {
            required: false,
        }
    }
}
</script>

<style scoped>
.navbar-popup-item {
    @apply block px-4 py-2 w-full rounded-none text-sm flex flex-row items-center gap-2;
}
.navbar-popup-item-bg {
    @apply text-gray-700 hover:bg-gray-100;
}

.navbar-popup-item-nobg {
    @apply text-gray-200 dark:text-gray-800 dark:hover:text-black dark:hover:bg-gray-100 hover:text-white hover:bg-gray-600;
}
</style>
