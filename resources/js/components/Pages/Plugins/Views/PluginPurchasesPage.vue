<template>
    <h2 class="my-4">Purchases</h2>

    <Searchbar
        v-model="purchasesFetchable.query"
        :disabled="!purchasesFetchable.canRequest()"
        placeholder="Search a purchase by email or username..."
        @submit="loadPurchases"
    />

    <table v-if="purchasesFetchable.loading || purchasesResponse.purchases?.length > 0">
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
            <td class="hidden md:table-cell">{{ purchase.payment_amount ? StringService.formatMoney(purchase.payment_amount) : 'Free' }}</td>
        </tr>
        </tbody>
    </table>

    <Pagination v-if="!purchasesFetchable.loading"
                :current-page="purchasesFetchable.page"
                :fetchable="purchasesFetchable"
                :last-page="purchasesResponse.pages"
                :per-page="10"
                :total="purchasesResponse.total"
    />

    <div v-if="!purchasesFetchable.loading && purchasesResponse.pastes?.length <= 0" class="grid place-content-center w-full my-8">
        <div class="flex flex-col items-center gap-3">
            <img src="/assets/img/no-results.svg" alt="no results" class="w-48"/>
            <div class="text-lg font-bold font-poppins">No purchases found!</div>
        </div>
    </div>
</template>

<script>
import Searchbar from "@/components/Common/Form/Searchbar.vue";
import Pagination from "@/components/Common/Pagination/Pagination.vue";
import Fetchable from "@/models/Fetchable";
import DateService from "../../../../services/DateService";
import PluginRepository from "@/services/PluginRepository";
import Plugin from "@/models/rest/Plugin";
import PluginPermissions from "@/models/rest/PluginPermissions";
import StringService from "../../../../services/StringService";

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
    components: {Pagination, Searchbar},

    async created() {
        this.purchasesFetchable.query = this.$route.query.query ?? '';
        this.purchasesFetchable.page = this.$route.query.page ?? 1;

        await this.loadPurchases();
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
            this.purchasesResponse = await PluginRepository.fetchPluginPurchases(this.pluginId, this.purchasesFetchable.page, 15, this.purchasesFetchable.query);
            console.log(this.purchasesResponse)
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
