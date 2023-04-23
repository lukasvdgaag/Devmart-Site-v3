<template>
    <div class="w-full flex flex-col items-center m-0 p-0 relative h-full">
        <Navbar :background="true"/>

        <div class="d-grid mt-6 md:mt-12 h-full w-full">
            <div class="col-span-12 flex flex-col items-center">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl leading-snug md:!leading-normal"
                        :class="{
                            'bg-gradient-to-r from-primary-500 dark:from-primary-400 to-purple-500 dark:to-purple-400 !text-transparent bg-clip-text': !loading && order,
                            'text-black': loading || !order
                        }">
                        {{ loading ? 'Finding your order...' : order ? 'Thanks for your order!' : 'No order found!' }}
                    </h1>
                    <p class="text-lg md:text-xl mt-1 text-gray-500 dark:text-gray-400">
                        <span v-if="loading || order">
                            {{ loading ? 'Looking for your order...' : 'Your order has been confirmed!' }}
                        </span>
                        <span
                            v-if="!loading && !order">We could not find an order with the provided ID.<br>If you think this is wrong, please contact us.</span>
                    </p>
                </div>

                <PaymentPageBox v-if="loading || order">
                    <h2>Order summary</h2>
                    <hr class="my-2 md:my-4">

                    <div class="flex gap-3">
                        <PaymentInfoItem title="Order ID" :value="$route.query.order ?? 'N/A'"/>
                        <PaymentInfoItem title="Order Date" :value="order ? DateService.formatDateTime(order.updated_at, true) : '...'"/>
                        <PaymentInfoItem title="Payment Method" value="PayPal"/>
                    </div>

                    <hr class="mt-2 md:mt-4">

                    <div class="flex gap-x-3 gap-y-2 items-center mb-4 md:mb-0">
                        <!-- image -->
                        <div class="flex gap-3 items-center flex-1 flex-grow">
                            <template v-if="loading">
                                <div class="animate-pulse w-full flex gap-3 items-center" role="status">
                                    <div class="w-8 h-8 md:w-16 md:h-16 bg-gray-200 rounded-lg dark:bg-gray-700"></div>

                                    <div class="flex-1 flex-grow">
                                        <div class="w-full h-4 bg-gray-200 rounded dark:bg-gray-700"></div>
                                        <div class="w-3/4 h-4 bg-gray-200 rounded dark:bg-gray-700 mt-1"></div>
                                    </div>
                                </div>
                            </template>
                            <template v-else>
                                <img :src="`/assets/img${plugin.logo_url}`" alt="Product logo" class="w-8 h-8 md:w-16 md:h-16 rounded-lg">

                                <!-- product info: title + description -->
                                <div class="flex-1 flex-grow">
                                    <div class="text-sm md:text-md font-medium leading-5">
                                        {{ order.breakdown.items[0].name }}
                                    </div>
                                    <div class="text-xs dark:text-gray-400">{{ order.breakdown.items[0].description }}</div>
                                </div>
                            </template>
                        </div>

                        <!-- price -->
                        <div v-if="loading" class="animate-pulse ml-2 md:ml-4 ">
                            <div class="w-12 h-4 bg-gray-200 rounded dark:bg-gray-700"></div>
                        </div>
                        <div v-else class="ml-2 md:ml-4 text-sm md:text-md dark:text-gray-400 break-keep">
                            {{ StringService.formatMoney(order.breakdown.items[0].unit_amount.value, false) }}
                        </div>
                    </div>

                    <!-- price summary, category on the left and value on the right -->
                </PaymentPageBox>

                <PaymentPageBox v-if="loading || order" class="flex flex-col gap-2 md:gap-5">
                    <PaymentPriceBreakdownItem label="Subtotal" :loading="loading" :value="order?.breakdown?.breakdown?.item_total?.value"/>
                    <PaymentPriceBreakdownItem label="Tax" :loading="loading" :value="0.00"/>
                    <PaymentPriceBreakdownItem label="Discount" :loading="loading" :value="-order?.breakdown?.breakdown?.discount?.value" is-discount/>

                    <hr class="my-1">
                    <PaymentPriceBreakdownItem label="Discount" :loading="loading" :value="order?.payment_amount" is-total/>
                </PaymentPageBox>

                <div v-if="!loading && !order" class="my-6 flex justify-center">
                    <img src="/assets/img/no-results.svg" alt="no results" class="w-48"/>
                </div>

                <p class="mt-2 md:mt-4 text-sm text-gray-400">Anything wrong? Contact us on <a href="/discord" target="_blank">Discord</a>.</p>
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import DateService from "../../../../services/DateService";
import OrderRepository from "@/services/OrderRepository";
import PluginRepository from "@/services/PluginRepository";
import StringService from "../../../../services/StringService";
import PaymentPageBox from "@/components/Pages/Payments/PaymentPageBox.vue";
import PaymentInfoItem from "@/components/Pages/Payments/PaymentInfoItem.vue";
import PaymentPriceBreakdownItem from "@/components/Pages/Payments/PaymentPriceBreakdownItem.vue";

export default {
    name: "PaymentConfirmedPage",

    async created() {
        this.loading = true;
        this.order = await OrderRepository.fetchOrderById(this.$route.query.order);
        if (!this.order) {
            console.log("Order not found")
            this.loading = false;
            return;
        }
        console.log(this.order)

        this.plugin = await PluginRepository.fetchPlugin(
            this.order.plugin_id, false, false, false, false
        );
        console.log(this.plugin)
        this.loading = false;
    },

    data() {
        return {
            /**
             * @type {Order|null}
             */
            order: null,
            /**
             * @type {Plugin|null}
             */
            plugin: null,
            loading: true,
        }
    },

    computed: {
        StringService() {
            return StringService
        },
        DateService() {
            return DateService
        }
    },
    components: {PaymentPriceBreakdownItem, PaymentInfoItem, PaymentPageBox, Navbar}
}
</script>

<style scoped>

</style>
