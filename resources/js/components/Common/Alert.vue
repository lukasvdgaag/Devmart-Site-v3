<template>
    <div :class="classes"
         class="flex flex-row items-center gap-4 rounded-lg flex px-4 py-3 dark:bg-gray-800">
        <font-awesome-icon v-if="icon" :icon="icon" size="2xl"/>
        <slot/>
    </div>
</template>

<script>
export default {
    name: "Alert",

    computed: {
        classes() {
            switch (this.type) {
                case "success":
                    return ["bg-green-100 dark:text-green-400 text-green-800"];
                case "error":
                    return ["bg-red-100 dark:text-red-400 text-red-800"];
                case "warning":
                    return ["bg-yellow-100 dark:text-yellow-400 text-yellow-800"];
                default:
                    return ["bg-primary-100 dark:text-primary-400 text-primary-800"];
            }
        }
    },

    props: {
        icon: String,
        type: {
            type: String,
            validator(val) {
                return ["error", "success", "info", "warning"].includes(val);
            },
            default: "info"
        }
    }
}
</script>

<style scoped>

</style>
