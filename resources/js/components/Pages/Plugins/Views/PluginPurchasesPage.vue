<template>
    <div class="flex justify-between items-center gap-2">
        <h2 class="my-4">Purchases</h2>

        <button class="primary w-fit h-fit"
                data-modal-target="pl-add-user"
                data-modal-toggle="pl-add-user">
            Add user
        </button>

        <Modal id="pl-add-user" title="Add user" :description="`You are about to give a new user access to <span class='font-bold'>${plugin?.title}</span>. Enter the username below.`">

            <Input name="username" placeholder="Username" class="mt-4 w-full"/>

        </Modal>
    </div>

    <div class="flex flex-col items-end">
        <Searchbar
            v-model="purchasesFetchable.query"
            :disabled="!purchasesFetchable.canRequest()"
            placeholder="Find a purchase by email or username..."
            class="w-full !mb-2"
            filter-button
            ref="searchbar"
            @submit="loadPurchases"
        />
    </div>
    <Dropdown id="filter-dropdown" header="Filters" ref="filterDropdown">
        <form date-rangepicker class="flex flex-col p-3 gap-1" ref="selectDate">
            <label class="block font-medium text-sm">Start date</label>
            <div class="relative">
                <div class="absolute flex h-full items-center left-0 pointer-events-none pl-3">
                    <font-awesome-icon icon="calendar" class="text-gray-500"/>
                </div>
                <Input name="start" placeholder="Select start date" class="pl-8"
                       v-model="purchasesFetchable.startDate"
                />
            </div>

            <label class="block mt-1 font-medium text-sm">End date</label>
            <div class="relative">
                <div class="absolute flex h-full items-center left-0 pointer-events-none pl-3">
                    <font-awesome-icon icon="calendar" class="text-gray-500"/>
                </div>
                <Input name="end" placeholder="Select end date" class="pl-8"
                       v-model="purchasesFetchable.endDate"
                />
            </div>

            <button class="primary mt-3"
                    type="button"
                    :disabled="!purchasesFetchable.canRequest()"
                    @click.prevent="filterDropdown.hide(); loadPurchases()">
                Apply
            </button>
        </form>
    </Dropdown>

    <div class="lg:mt-auto gap-x-2 flex flex-row">
        <PluginLabel v-if="purchasesFetchable.startDate"
                     :label="`<span class='font-medium'>From</span> ${purchasesFetchable.startDate}`"
                     :uppercase="false"
                     :bold="false"
                     class="cursor-pointer"
                     icon="circle-xmark"
                     @click="purchasesFetchable.startDate = null"
        />
        <PluginLabel v-if="purchasesFetchable.endDate"
                     :label="`<span class='font-medium'>Until</span> ${purchasesFetchable.endDate}`"
                     :uppercase="false"
                     :bold="false"
                     icon="circle-xmark"
                     class="cursor-pointer"
                     @click="purchasesFetchable.endDate = null"
        />
    </div>

    <ValidationError item="error" :errors="errors"/>

    <hr class="mb-3 mt-2"/>

    <table v-if="purchasesFetchable.loading || purchasesResponse?.purchases?.length > 0">
        <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Date</th>
            <th class="hidden md:table-cell">Amount</th>
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
            <td>{{ DateService.formatDateRelatively(purchase.date) }} <span class="hidden xl:inline-block">at {{ DateService.formatTime(purchase.date) }}</span>
            </td>
            <td class="hidden md:table-cell">{{ StringService.formatMoney(purchase.amount ?? purchase.payment_amount) }}</td>
        </tr>
        </tbody>
    </table>


    <div v-if="!purchasesFetchable.loading && purchasesResponse?.purchases?.length <= 0" class="grid place-content-center w-full mt-6">
        <div class="flex flex-col items-center gap-3">
            <img src="/assets/img/no-results.svg" alt="no results" class="w-48"/>
            <div class="text-lg font-bold font-poppins">No purchases found!</div>
        </div>
    </div>

    <Pagination v-if="!purchasesFetchable.loading"
                :current-page="purchasesFetchable.page"
                :fetchable="purchasesFetchable"
                :last-page="purchasesResponse?.pages ?? 1"
                :per-page="10"
                :total="purchasesResponse?.total ?? 1"
    />

    <div v-if="!purchasesFetchable.loading && (purchasesResponse?.pastes?.length <= 0 || errors?.error != null)" class="grid place-content-center w-full my-8">
        <div class="flex flex-col items-center gap-3">
            <img src="/assets/img/no-results.svg" alt="no results" class="w-48"/>
            <div class="text-lg font-bold font-poppins">No purchases found!</div>
        </div>
    </div>
</template>

<script>
import Searchbar from "@/components/Common/Form/Searchbar.vue";
import Pagination from "@/components/Common/Pagination/Pagination.vue";
import DateService from "../../../../services/DateService";
import PluginRepository from "@/services/PluginRepository";
import Plugin from "@/models/rest/Plugin";
import PluginPermissions from "@/models/rest/PluginPermissions";
import StringService from "../../../../services/StringService";
import DateRangePicker from 'flowbite-datepicker/DateRangePicker';
import Input from "@/components/Common/Form/Input.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {Dropdown as FlowbiteDropdown, initModals} from "flowbite";
import Dropdown from "@/components/Common/Form/Dropdown.vue";
import PluginLabel from "@/components/Pages/Plugins/PluginLabel.vue";
import PurchasesFetchable from "@/models/fetchable/PurchasesFetchable";
import ValidationError from "@/components/Common/Form/ValidationError.vue";
import Modal from "@/components/Common/Modal/Modal.vue";

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
    components: {Modal, ValidationError, PluginLabel, Dropdown, FontAwesomeIcon, Input, Pagination, Searchbar},

    async created() {
        this.purchasesFetchable.query = this.$route.query.query ?? '';
        this.purchasesFetchable.page = this.$route.query.page ?? 1;
        this.purchasesFetchable.startDate = this.$route.query.from ?? null;
        this.purchasesFetchable.endDate = this.$route.query.to ?? null;

        await this.loadPurchases();
    },

    mounted() {
        initModals();

        this.filterDropdown = new FlowbiteDropdown(this.$refs.filterDropdown.$el, this.$refs.searchbar.$refs.filterButton);
        new DateRangePicker(this.$refs.selectDate, {
            container: '#filter-dropdown',
            allowOneSidedRange: true,
            autohide: true,
            format: 'dd-mm-yyyy',
        });
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
                    page: this.purchasesFetchable.page > 1 ? this.purchasesFetchable.page : undefined,
                    from: this.purchasesFetchable.startDate ?? undefined,
                    to: this.purchasesFetchable.endDate ?? undefined,
                }
            })
            try {
                this.purchasesResponse = await PluginRepository.fetchPluginPurchases(
                    this.pluginId,
                    this.purchasesFetchable.page,
                    15,
                    this.purchasesFetchable.query,
                    this.purchasesFetchable.startDate,
                    this.purchasesFetchable.endDate,
                );
                this.errors = {};
            } catch (e) {
                this.purchasesFetchable.loading = false;
                if (e.response.status === 401) {
                    this.$router.push({name: 'plugin-overview', params: {pluginId: this.pluginId}});
                    return;
                }

                this.errors = {error: [e.response?.data?.message ?? 'An error occurred while fetching the purchases.']};
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
            purchasesFetchable: new PurchasesFetchable(
                this.fetchPurchases,
                this.$route.query.query ?? '',
                Number.parseInt(this.$route.query.page ?? '1'),
                this.$route.query.from ?? '',
                this.$route.query.to ?? '',
            ),
            filterDropdown: null,
            errors: {},
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
