<template>
    <h1>Your Pastes</h1>
    <hr/>

    <Searchbar
        v-model="pastesFetchable.query"
        :disabled="!pastesFetchable.canRequest()"
        placeholder="Find a paste..."
        @submit="loadPastes"
    />

    <table v-if="pastesFetchable.loading || pasteResponse.pastes?.length > 0">
        <thead>
        <tr>
            <th>Title</th>
            <th>Updated at</th>
            <th class="hidden md:table-cell">Expiry date</th>
            <th>Style</th>
            <th></th>
        </tr>
        </thead>
        <tbody class="sale-search-result">

        <template v-if="pastesFetchable.loading">
            <tr class="animate-pulse w-full mt-1" role="status">
                <td colspan="5">
                    <div class="flex gap-2">
                        <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                        <div class="flex gap-1">
                            <div class="h-10 bg-gray-300 rounded dark:bg-gray-600 w-10"></div>
                            <div class="h-10 bg-gray-300 rounded dark:bg-gray-600 w-10"></div>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <div class="h-10 bg-gray-300 rounded dark:bg-gray-600 w-full"></div>
                        <div class="flex gap-1">
                            <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-10"></div>
                            <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-10"></div>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                        <div class="flex gap-1">
                            <div class="h-10 bg-gray-300 rounded dark:bg-gray-600 w-10"></div>
                            <div class="h-10 bg-gray-300 rounded dark:bg-gray-600 w-10"></div>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <div class="h-10 bg-gray-300 rounded dark:bg-gray-600 w-full"></div>
                        <div class="flex gap-1">
                            <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-10"></div>
                            <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-10"></div>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-2">
                        <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                        <div class="flex gap-1">
                            <div class="h-10 bg-gray-300 rounded dark:bg-gray-600 w-10"></div>
                            <div class="h-10 bg-gray-300 rounded dark:bg-gray-600 w-10"></div>
                        </div>
                    </div>
                    <span class="sr-only">Loading...</span>
                </td>
            </tr>
        </template>
        <tr v-else v-for="paste in pasteResponse.pastes" :key="paste.id">
            <td>{{ paste.title }}</td>
            <td>{{ DateService.formatDateRelatively(new Date(paste.updated_at), true) }}</td>
            <td class="hidden md:table-cell">{{ expiryDate(paste) }}</td>
            <td>{{ paste.style ?? 'Automatic' }}</td>
            <td class="flex justify-end gap-1">
                <router-link :to="{ name: 'paste-edit', params: { pasteId: paste.name } }">
                    <button class="secondary px-2.5 py-1">
                        <font-awesome-icon icon="pen"/>
                    </button>
                </router-link>
                <router-link :to="{ name: 'paste-info', params: { pasteId: paste.name } }">
                    <button class="secondary px-2.5 py-1">
                        <font-awesome-icon icon="arrow-up-right-from-square"/>
                    </button>
                </router-link>
            </td>
        </tr>
        </tbody>
    </table>

    <Pagination v-if="!pastesFetchable.loading"
                :current-page="pastesFetchable.page"
                :fetchable="pastesFetchable"
                :last-page="pasteResponse.pages"
                :per-page="10"
                :total="pasteResponse.total"
    />

    <div v-if="!pastesFetchable.loading && pasteResponse.pastes?.length <= 0" class="grid place-content-center w-full my-8">
        <div class="flex flex-col items-center gap-3">
            <img src="/assets/img/no-results.svg" alt="no results" class="w-48"/>
            <div class="text-lg font-bold font-poppins">No pastes found!</div>
        </div>
    </div>
</template>

<script>
import Searchbar from "@/components/Common/Form/Searchbar.vue";
import UserRepository from "@/services/UserRepository.js";
import Pagination from "@/components/Common/Pagination/Pagination.vue";
import Fetchable from "@/models/Fetchable";
import DateService from "../../../../services/DateService";

export default {
    name: "AccountPastes",
    computed: {
        DateService() {
            return DateService
        },
    },
    components: {Pagination, Searchbar},

    async created() {
        this.pastesFetchable.query = this.$route.query.query ?? '';
        this.pastesFetchable.page = this.$route.query.page ?? 1;

        await this.loadPastes();
    },

    methods: {
        async loadPastes() {
            this.loading = true;

            await this.pastesFetchable.fetch(this);
            this.loading = this.pasteResponse != null;
        },
        async fetchPastes() {
            this.$router.replace({
                query: {
                    query: this.pastesFetchable.query?.length === 0 ? undefined : this.pastesFetchable.query,
                    page: this.pastesFetchable.page > 1 ? this.pastesFetchable.page : undefined
                }
            })
            this.pasteResponse = await UserRepository.fetchUserPastesById(this.userId, this.pastesFetchable.query, this.pastesFetchable.page);
        },
        expiryDate(paste) {
            return paste.expire_at ? this.DateService.formatTimeLeft(new Date(paste.expire_at), true) : '-';
        }
    },

    data() {
        return {
            loading: true,
            /**
             * @type {PasteListResponse}
             */
            pasteResponse: null,
            pastesFetchable: new Fetchable(
                this.fetchPastes,
                this.$route.query.query ?? '',
                Number.parseInt(this.$route.query.page ?? '1')
            ),
        }
    },


    props: ['user', 'userId', 'userLoading', 'isAdmin']
}
</script>

<style scoped>

</style>
