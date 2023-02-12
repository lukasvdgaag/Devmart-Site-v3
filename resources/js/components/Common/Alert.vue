<template>
    <div :class="classes"
         class="flex flex-row items-center gap-4 rounded-lg flex px-4 py-3 text-white">
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
                    return ["bg-green-500"];
                case "error":
                    return ["bg-red-500"];
                case "warning":
                    return ["bg-yellow-500"];
                default:
                    return ["bg-primary"];
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
