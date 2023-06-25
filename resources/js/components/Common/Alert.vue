<template>
    <div :class="[classes, {'hidden': closed}]"
         class="flex items-center gap-4 rounded-lg px-4 pr-10 py-3 dark:bg-gray-800 relative">
        <div class="absolute h-full top-0 right-4 flex items-center">
            <font-awesome-icon v-if="closable" icon="xmark" class="cursor-pointer" size="lg"
                               @click="closed = true; $emit('close')"/>
        </div>
        <font-awesome-icon v-if="icon" :icon="icon" size="2xl"/>
        <slot/>
    </div>
</template>

<script>
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

export default {
    name: "Alert",
    components: {FontAwesomeIcon},
    emits: ['close'],

    data() {
        return {
            closed: false
        }
    },

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
        },
        closable: {
            type: Boolean,
            default: true
        }
    }
}
</script>

<style scoped>

</style>
