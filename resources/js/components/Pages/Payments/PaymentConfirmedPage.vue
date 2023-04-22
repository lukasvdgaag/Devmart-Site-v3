<template>
    <div class="w-full flex flex-col items-center m-0 p-0 relative h-full">
        <Navbar :background="true"/>

        <div class="d-grid mt-12 h-full w-full">
            <div class="col-span-12 flex flex-col items-center">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl bg-gradient-to-r from-primary-500 dark:from-primary-400 to-purple-500 dark:to-purple-400 !text-transparent bg-clip-text">Thanks for your order!</h1>
                    <p class="text-lg md:text-xl mt-2 text-gray-500 dark:text-gray-400">Your order has been confirmed!</p>
                </div>


                <div class="bg-gray-100 dark:bg-gray-800 rounded-lg md:rounded-2xl px-4 md:px-8 py-3 md:py-6 mt-6 w-full max-w-2xl">
                    <h2>Order summary</h2>
                    <hr class="my-2 md:my-4">

                    <div class="flex gap-3">
                        <div class="flex-1">
                            <div class="text-sm font-semibold">Order ID</div>
                            <div class="text-xs dark:text-gray-400">{{ $route.query.orderId ?? 'N/A' }}</div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold">Order Date</div>
                            <div class="text-xs dark:text-gray-400">{{ DateService.formatDate(new Date()) }}</div>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-semibold">Payment Method</div>
                            <div class="text-xs dark:text-gray-400">PayPal</div>
                        </div>
                    </div>

                    <hr class="mt-2 md:mt-4">

                    <div class="flex gap-x-3 gap-y-2 items-center mb-4 md:mb-0">
                        <!-- image -->
                        <div class="flex gap-3 items-center flex-1 flex-grow">
                            <img src="/assets/img/plugins/63cad1071369c.png" alt="Product logo" class="w-8 h-8 md:w-16 md:h-16 rounded-lg">

                            <!-- product info: title + description -->
                            <div class="flex-1 flex-grow">
                                <div class="text-sm md:text-md font-medium leading-5">AdditionsPlus | Custom Commands, Menus, Tablists, Scoreboards, Items & more | 1.8 -
                                    1.19
                                </div>
                                <div class="text-xs dark:text-gray-400">Turn your creativity into reality!</div>
                            </div>
                        </div>

                        <!-- price -->
                        <div class="ml-2 md:ml-4 text-sm md:text-md dark:text-gray-400 break-keep">
                            5.00 EUR
                        </div>
                    </div>

                    <!-- price summary, category on the left and value on the right -->
                </div>

                <div class="flex flex-col bg-gray-100 dark:bg-gray-800 rounded-lg md:rounded-2xl px-4 md:px-8 py-3 md:py-6 m-3 md:mt-6 w-full max-w-2xl gap-2 md:gap-5">
                    <dl>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-800 dark:text-gray-300">Subtotal</dt>
                            <dd class="text-sm text-gray-500 dark:text-gray-400">5.00 EUR</dd>
                        </div>
                    </dl>
                    <dl>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-800 dark:text-gray-300">Tax</dt>
                            <dd class="text-sm text-gray-500 dark:text-gray-400">0.00 EUR</dd>
                        </div>
                    </dl>
                    <dl>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-800 dark:text-gray-300">Discount</dt>
                            <dd class="text-sm font-medium text-green-500 dark:text-green-400">-1.25 EUR</dd>
                        </div>
                    </dl>

                    <hr class="my-1">

                    <dl>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-800 dark:text-gray-300">Total</dt>
                            <dd class="text-sm font-medium text-gray-800 dark:text-gray-300">3.75 EUR</dd>
                        </div>
                    </dl>
                </div>

                <p class="mt-2 md:mt-4 text-sm text-gray-400">Anything wrong? Contact us on <a href="/discord" target="_blank">Discord</a>.</p>

            </div>
        </div>
    </div>
</template>

<script>
import Navbar from "@/components/Common/Navbar.vue";
import DateService from "../../../services/DateService";

export default {
    name: "PaymentConfirmedPage",
    computed: {
        DateService() {
            return DateService
        }
    },
    components: {Navbar}
}
</script>

<style scoped>

</style>
