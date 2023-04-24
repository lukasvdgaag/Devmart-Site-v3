<template>
    <h2 class="my-4">Purchases</h2>

    <div class="flex gap-2">
        <Searchbar
            v-model="purchasesFetchable.query"
            :disabled="!purchasesFetchable.canRequest()"
            placeholder="Search a purchase by email or username..."
            class="w-full"
            filter-button
            @submit="loadPurchases"
        />
    </div>
    <Dropdown id="filter-dropdown" header="Filters">
        <form date-rangepicker class="flex flex-col p-3 gap-1" ref="selectDate">
            <label class="block font-medium text-sm">From</label>
            <div class="relative">
                <div class="absolute flex h-full items-center left-0 flex items-center pointer-events-none pl-3">
                    <font-awesome-icon icon="calendar" class="text-gray-500"/>
                </div>
                <Input name="start" placeholder="Select start date" class="pl-8"/>
            </div>

            <label class="block mt-1 font-medium text-sm">To</label>
            <div class="relative">
                <div class="absolute flex h-full items-center left-0 flex items-center pointer-events-none pl-3">
                    <font-awesome-icon icon="calendar" class="text-gray-500"/>
                </div>
                <Input name="end" placeholder="Select end date" class="pl-8"/>
            </div>
        </form>
    </Dropdown>

    <hr class="mb-3 -mt-2"/>

    <table v-if="purchasesFetchable.loading || purchasesResponse?.purchases?.length > 0">
        <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th class="hidden md:table-cell">Date</th>
            <th>Amount</th>
        </tr>
        </thead>
        <tbody class="sale-search-result">

        <template v-if="purchasesFetchable.loading">
            <tr class="animate-pulse w-full mt-1" role="status">
                <td colspan="5">
                    <div class="h-10 bg-gray-300 rounded dark:bg-gray-600 w-full"></div>
                    <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full mt-2"></div>
                    <div class="h-10 bg-gray-300 rounded dark:bg-gray-600 w-full mt-2"></div>
                    <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full mt-2"></div>
                    <span class="sr-only">Loading...</span>
                </td>
            </tr>
        </template>
        <tr v-else v-for="purchase in purchasesResponse.purchases" :key="purchase.id">
            <td>{{ purchase.username ?? '-' }}</td>
            <td>{{ purchase.email ?? '-' }}</td>
            <td>{{ DateService.formatDateRelatively(purchase.date, true) }}</td>
            <td class="hidden md:table-cell">{{ StringService.formatMoney(purchase.amount) }}</td>
        </tr>
        </tbody>
    </table>

    <Pagination v-if="!purchasesFetchable.loading"
                :current-page="purchasesFetchable.page"
                :fetchable="purchasesFetchable"
                :last-page="purchasesResponse?.pages ?? 1"
                :per-page="10"
                :total="purchasesResponse?.total ?? 1"
    />

    <div v-if="!purchasesFetchable.loading && purchasesResponse?.pastes?.length <= 0" class="grid place-content-center w-full my-8">
        <div class="flex flex-col items-center gap-3">
            <img src="/assets/img/no-results.svg" alt="no results" class="w-48"/>
            <div class="text-lg font-bold font-poppins">No purchases found!</div>
        </div>
    </div>
</template>

<script>
import Searchbar from "@/components/Common/Form/Searchbar.vue";
import Pagination from "@/components/Common/Pagination/Pagination.vue";
import Fetchable from "@/models/fetchable/Fetchable";
import DateService from "../../../../services/DateService";
import PluginRepository from "@/services/PluginRepository";
import Plugin from "@/models/rest/Plugin";
import PluginPermissions from "@/models/rest/PluginPermissions";
import StringService from "../../../../services/StringService";
import DateRangePicker from 'flowbite-datepicker/DateRangePicker';
import Input from "@/components/Common/Form/Input.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {initDropdowns} from "flowbite";
import Dropdown from "@/components/Common/Form/Dropdown.vue";

export default {
    name: "PluginPurchasesPage",
    computed: {
        StringService() {
            return StringService
        },
        DateService() {
            return DateService
        },
    },
    components: {Dropdown, FontAwesomeIcon, Input, Pagination, Searchbar},

    async created() {
        this.purchasesFetchable.query = this.$route.query.query ?? '';
        this.purchasesFetchable.page = this.$route.query.page ?? 1;

        await this.loadPurchases();
    },

    mounted() {
        initDropdowns();
        new DateRangePicker(this.$refs.selectDate, {
            allowOneSidedRange: true,
            title: 'Date Range',
            format: 'mm-dd-yyyy',
        })
    },

    methods: {
        async loadPurchases() {
            this.loading = true;

            await this.purchasesFetchable.fetch(this);
            this.loading = this.purchasesResponse != null;
        },
        async fetchPurchases() {
            this.$router.replace({
                query: {
                    query: this.purchasesFetchable.query?.length === 0 ? undefined : this.purchasesFetchable.query,
                    page: this.purchasesFetchable.page > 1 ? this.purchasesFetchable.page : undefined
                }
            })
            try {
                this.purchasesResponse = await PluginRepository.fetchPluginPurchases(this.pluginId, this.purchasesFetchable.page, 15, this.purchasesFetchable.query);
                console.log(this.purchasesResponse)
            } catch (e) {
                this.$router.push({name: 'plugin-overview', params: {pluginId: this.pluginId}});
            }
        },
        expiryDate(paste) {
            return paste.expire_at ? this.DateService.formatTimeLeft(new Date(paste.expire_at), true) : '-';
        }
    },

    data() {
        return {
            loading: true,
            /**
             * @type {PluginPurchasesResponse}
             */
            purchasesResponse: null,
            purchasesFetchable: new Fetchable(
                this.fetchPurchases,
                this.$route.query.query ?? '',
                Number.parseInt(this.$route.query.page ?? '1')
            ),
        }
    },


    props: {
        plugin: {
            type: Plugin,
            required: true,
        },
        pluginId: {
            type: String,
            required: true,
        },
        permissions: {
            type: [PluginPermissions, null],
            required: true,
        }
    }
}
</script>

<style scoped>

</style>
