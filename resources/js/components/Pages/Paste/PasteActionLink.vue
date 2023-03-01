<template>
    <a :class="[highlighted ? 'bg-gray-500 hover:bg-gray-600 text-white' : 'bg-gray-300 dark:bg-gray-400 dark:text-white hover:bg-gray-400 dark:hover:bg-gray-500']" :href="href"
       class="hover:bg-opacity-75 plain w-full lg:w-fit px-2 py-1 lg:px-4 lg:py-2 text-sm sm:text-base rounded-md flex align-center gap-2">
        <font-awesome-icon :class="{'text-sm': this.smallIcon}" :icon="icon"/>
        {{ text }}
    </a>
</template>

<script>
export default {
    name: "PasteActionLink",

    props: {
        href: {
            type: String,
            required: true
        },
        icon: {
            type: String,
            required: true
        },
        text: {
            type: String,
            required: true
        },
        smallIcon: {
            type: Boolean,
            default: false
        },
        highlighted: {
            type: Boolean,
            default: false
        },
    }
}
</script>

<style scoped>

</style>
