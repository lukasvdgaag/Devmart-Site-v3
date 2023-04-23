<template>
    <dl>
        <div class="flex justify-between">
            <dt class="text-sm font-medium text-gray-800 dark:text-gray-300">{{label}}</dt>
            <div v-if="loading" class="animate-pulse ml-2 md:ml-4">
                <div class="h-4 bg-gray-200 rounded dark:bg-gray-700" :class="isTotal ? 'w-20' : 'w-12'"></div>
            </div>
            <dd v-else class="text-sm"
                :class="isTotal ? 'font-medium text-gray-800 dark:text-gray-300' :
                    isDiscount ? 'font-medium text-green-500 dark:text-green-400':
                    'text-gray-500 dark:text-gray-400'">
                {{ StringService.formatMoney(value, false) }}
            </dd>
        </div>
    </dl>
</template>

<script>
import StringService from "../../../services/StringService";

export default {
    name: "PaymentPriceBreakdownItem",
    computed: {
        StringService() {
            return StringService
        }
    },

    props: {
        loading: {
            type: Boolean,
            required: true
        },
        label: {
            type: String,
            required: true
        },
        value: {
            type: Number,
            default: 0.00
        },
        isDiscount: {
            type: Boolean,
            default: false
        },
        isTotal: {
            type: Boolean,
            default: false
        }
    },
}
</script>

<style scoped>

</style>
