<template>
    <h1>Sales</h1>
    <hr>

    <div class="grid d-grid-12 h-unset mt-2 gap-4">
        <QuickLink icon="fa-sack-dollar" label="Total Sales">
            {{ totalSales }}
        </QuickLink>
        <QuickLink icon="fa-calendar-days"
                   label="Sales Last 30 Days"
                   class="md:row-start-2">
            <div>{{ total30Days }}</div>
            <div class="up size-14 flex flex-row gap-1 align-items-end mt-1">
                <GraphIcon :percentage="difference30Days"/>
                <div :class="[difference30Days >= 0 ? 'text-green-400' : 'text-red-400']">{{ difference30Days }}%</div>
                <div class=" muted">from last month</div>
            </div>
        </QuickLink>
        <QuickLink label="Earnings This Week" class="md:row-span-2 lg:col-span-8 h-full">
            <div class="flex align-content-center mt-2 mb-2 bold size-24 flex-row gap-2">
                <div>{{ totalWeek }}</div>
                <div class="mt-1 ml-1 up size-14 flex flex-row gap-1 align-items-end justify-content-center">
                    <GraphIcon :percentage="differenceLastWeek"/>
                    <div :class="[differenceLastWeek >= 0 ? 'text-green-400' : 'text-red-400']">{{ differenceLastWeek }}%</div>
                    <div class="muted">from previous week</div>
                </div>
            </div>
            <div class="w-100 relative h-100 w-100">
                <Vue3Apexcharts type="line" height="100%" :options="chartOptions" :series="chartSeries"/>
            </div>
        </QuickLink>

        <QuickLink label="Recent Sales" class="!col-span-12">
            <Searchbar placeholder="Search a purchase by email or user..."
                       v-model="transactionsFetchable.query"
                       :disabled="!transactionsFetchable.canRequest()"
                       @submit="loadRecentSales"/>

            <table>
                <thead>
                <tr>
                    <th>Plugin</th>
                    <th>Email</th>
                    <th>Time</th>
                    <th v-if="recentSalesContainUser">User</th>
                    <th>Amount</th>
                </tr>
                </thead>
                <tbody class="sale-search-result">

                <tr v-if="transactionsFetchable.loading">
                    <td colspan="5" class="italic">
                        Loading search results...
                    </td>
                </tr>

                <tr v-for="sale in recentSales" :key="sale.id">
                    <td>{{ sale.pluginName }}</td>
                    <td>{{ sale.email }}</td>
                    <td>{{ sale.date }}</td>
                    <td v-if="recentSalesContainUser">{{ sale.userId }}</td>
                    <td>{{ StringService.formatMoney(sale.amount) }}</td>
                </tr>
                <tr v-if="!transactionsFetchable.loading && recentSales.length === 0">
                    <td colspan="5" class="text-red-500 font-medium">
                        No sales were found for your search query.
                    </td>
                </tr>
                </tbody>
            </table>
        </QuickLink>
    </div>
</template>

<script>
import MutedText from "@/components/Common/MutedText.vue";
import QuickLink from "@/components/Common/QuickLink.vue";
import Searchbar from "@/components/Common/Searchbar.vue";
import PluginRepository from "@/services/PluginRepository";
import StringService from "../../../services/StringService";
import GraphIcon from "@/components/Common/Icon/GraphIcon.vue";
import Vue3Apexcharts from "vue3-apexcharts/src/vue3-apexcharts";
import DateService from "@/services/DateService";
import Fetchable from "@/models/Fetchable";

export default {
    name: "AccountSales",
    components: {GraphIcon, Searchbar, QuickLink, MutedText, Vue3Apexcharts},

    async created() {
        await Promise.all([
            this.loadRecentSales(),
            this.loadTotalSales(),
            this.loadTotalSales30Days(),
            this.loadTotalSalesWeek(),
        ]);
    },

    data() {
        return {
            transactionsFetchable: new Fetchable(this.fetchRecentSales, this.$route.query.query ?? ''),
            recentSales: [],
            totalSales: "€ 0",
            total30Days: "€ 0",
            difference30Days: 0,
            totalWeek: "€ 0",
            differenceLastWeek: 0,

            chartSeries: [], // values here
            chartOptions: {
                chart: {
                    toolbar: {
                        show: false,
                    },
                },
                colors: ['#5850EC'],
                stroke: {
                    width: 4,
                    curve: 'smooth'
                },
                xaxis: {
                    categories: new Array(7).fill(null).map((v, i) => DateService.formatDate(DateService.offset(i-6)))
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'horizontal',
                        gradientToColors: ['#7E3AF2'],
                    },
                },
                yaxis: {
                    min: 0,
                    tickAmount: 4,
                    forceNiceScale: true,
                    axisTicks: {
                        width: 20,
                    }
                }
            },
        }
    },

    computed: {
        StringService() {
            return StringService
        },
        recentSalesContainUser() {
            return this.recentSales.filter(s => s.userId).length > 0;
        },
    },

    methods: {
        async loadRecentSales() {
            console.log("loading...")
            await this.transactionsFetchable.fetch(this);
        },
        async fetchRecentSales() {
            const response = await PluginRepository.fetchSales(this.userId, this.transactionsFetchable.query);
            this.recentSales = response.data.transactions;
        },
        async loadTotalSales() {
            const response = await PluginRepository.fetchSalesSum(this.userId);
            this.totalSales = StringService.formatMoney(response.data.total);
        },
        async loadTotalSales30Days() {
            const response = await PluginRepository.fetchSalesSum(
                this.userId,
                DateService.offset(-29),
                new Date(),
                DateService.offset(-60),
                DateService.offset(-30)
            );
            this.total30Days = StringService.formatMoney(response.data.total);
            this.difference30Days = this.calculateDifference(response.data.total, response.data.compareTotal);
        },
        async loadTotalSalesWeek() {
            const response = await PluginRepository.fetchDailySales(this.userId, null, DateService.offset(-14), new Date(), 14);

            /**
             * @type {Array}
             */
            const dates = response.data;
            let prevDate = new Date();
            for (let i = 0; i < 14; i++) {
                const obj = dates[i] ?? undefined;
                let formattedPrevDate = `${prevDate.getFullYear()}-${prevDate.getMonth() + 1}-${prevDate.getDate()}`;
                if (obj == null || obj.date !== formattedPrevDate) {
                    dates.splice(i, 0, {
                        date: formattedPrevDate,
                        amount: 0,
                        count: 0,
                    });
                }
                prevDate = DateService.offset(-1, prevDate);
            }

            let thisWeekDates = dates.slice(0, 7);

            this.chartSeries = [{
                name: 'Earnings',
                data: thisWeekDates.map(d => d.amount).reverse(),
            }];

            let total = this.sumTotal(thisWeekDates);
            this.totalWeek = StringService.formatMoney(total);
            this.differenceLastWeek = this.calculateDifference(total, this.sumTotal(dates.slice(7, 14)));
        },
        sumTotal(data) {
            return data.reduce((a, b) => {
                return a + Number.parseFloat(b.amount);
            }, 0);
        },
        calculateDifference(total1, total2) {
            // calculate the percentage difference between two numbers, with two decimals
            return Math.round(((total1 - total2) / total2) * 10000) / 100;
        }
    },

    props: {
        user: {
            type: Object,
            required: true
        },
        isAdmin: {
            type: Boolean,
            required: true
        },
        userLoading: {
            type: Boolean,
            required: true
        },
        userId: {
            type: Number,
            required: true
        },
    }
}
</script>

<style scoped>

</style>
