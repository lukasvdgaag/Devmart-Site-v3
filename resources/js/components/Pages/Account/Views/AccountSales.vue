<template>
    <h1>Sales</h1>
    <hr/>

    <div class="grid col-span-12 h-unset mt-2 gap-4">
        <QuickLink icon="fa-sack-dollar" label="Total Sales">
            {{ totalSales }}
        </QuickLink>
        <QuickLink class="md:row-start-2"
                   icon="fa-calendar-days"
                   label="Sales Last 30 Days">
            <div>{{ total30Days }}</div>
            <div class="up text-sm flex flex-row gap-1 items-end mt-1">
                <GraphIcon :percentage="difference30Days"/>
                <div :class="[difference30Days >= 0 ? 'text-green-400' : 'text-red-400']">{{ difference30Days }}%</div>
                <div class=" muted">from last month</div>
            </div>
        </QuickLink>
        <QuickLink class="md:row-span-2 lg:col-span-8 h-full" label="Earnings This Week">
            <div class="flex content-center mt-2 mb-2 font-bold text-2xl flex-row gap-2">
                <div>{{ totalWeek }}</div>
                <div class="mt-1 ml-1 up text-sm flex flex-row gap-1 items-end justify-center">
                    <GraphIcon :percentage="differenceLastWeek"/>
                    <div :class="[differenceLastWeek >= 0 ? 'text-green-400' : 'text-red-400']">{{ differenceLastWeek }}%</div>
                    <div class="muted">from previous week</div>
                </div>
            </div>
            <div class="w-full relative h-full w-full">
                <Vue3Apexcharts ref="chart" :options="chartOptions" :series="chartSeries" class="min-h-[300px] lg:min-h-0" height="100%" type="line"/>
            </div>
        </QuickLink>

        <QuickLink class="!col-span-12" label="Recent Sales">
            <Searchbar v-model="transactionsFetchable.query"
                       :disabled="!transactionsFetchable.canRequest()"
                       placeholder="Search a purchase by email or user..."
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
                    <td class="italic" colspan="5">
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
                    <td class="text-red-500 font-medium" colspan="5">
                        No sales were found for your search query.
                    </td>
                </tr>
                </tbody>
            </table>

            <Pagination
                :current-page="transactionsFetchable.page"
                :fetchable="transactionsFetchable"
                :last-page="recentSalesPages"
                :per-page="10"
                :total="recentSalesTotal"
            />
        </QuickLink>
    </div>
</template>

<script>
import MutedText from "@/components/Common/MutedText.vue";
import QuickLink from "@/components/Common/QuickLink.vue";
import Searchbar from "@/components/Common/Form/Searchbar.vue";
import PluginRepository from "@/services/PluginRepository";
import StringService from "@/services/StringService";
import GraphIcon from "@/components/Common/Icon/GraphIcon.vue";
import Vue3Apexcharts from "vue3-apexcharts/src/vue3-apexcharts";
import DateService from "@/services/DateService";
import Fetchable from "@/models/fetchable/Fetchable";
import Pagination from "@/components/Common/Pagination/Pagination.vue";

export default {
    name: "AccountSales",
    components: {Pagination, GraphIcon, Searchbar, QuickLink, MutedText, Vue3Apexcharts},

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
            transactionsFetchable: new Fetchable(
                this.fetchRecentSales,
                this.$route.query.query ?? '',
                Number.parseInt(this.$route.query.page ?? '1')
            ),
            recentSales: [],
            recentSalesTotal: 0,
            recentSalesPages: 0,
            totalSales: "€ 0",
            total30Days: "€ 0",
            difference30Days: 0,
            totalWeek: "€ 0",
            differenceLastWeek: 0,

            chartSeries: [], // values here
            chartOptions: {
                chart: {
                    background: 'transparent',
                    toolbar: {
                        show: false,
                    },
                },
                theme: {
                    mode: this.isDarkTheme() ? 'dark' : 'light',
                },
                colors: ['#5850EC'],
                stroke: {
                    width: 4,
                    curve: 'smooth'
                },
                xaxis: {
                    categories: new Array(7).fill(null).map((v, i) => DateService.formatDate(DateService.offset(i - 6)))
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
        isDarkTheme() {
            return document.documentElement.classList.contains('dark');
        },
        async loadRecentSales() {
            await this.transactionsFetchable.fetch(this);
        },
        async fetchRecentSales() {
            const response = await PluginRepository.fetchSales(this.userId, this.transactionsFetchable.query, this.transactionsFetchable.page);
            this.recentSales = response.data.transactions;
            this.recentSalesTotal = response.data.total;
            this.recentSalesPages = response.data.totalPages;
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
                let formattedPrevDate = prevDate.toISOString().substring(0, 10);
                if (!obj || obj.date !== formattedPrevDate) {
                    dates.splice(i, 0, {
                        date: formattedPrevDate,
                        amount: 0,
                        count: 0,
                    });
                }
                prevDate = DateService.offset(-1, prevDate);
            }

            let thisWeekDates = dates.slice(0, 7);

            console.log(thisWeekDates)
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
