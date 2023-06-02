<template>
    <div class="flex flex-row">
        <div class="w-full flex flex-col items-center m-0 p-0">
            <Navbar :background="true"/>

            <div class="d-grid mb-6 mt-4">
                <div class="col-span-12 lg:col-span-8 lg:col-start-3 pt-2">
                    <div class="flex flex-col gap-2 w-full items-center">
                        <h1 class="text-center">User Management</h1>
                        <p class="text-center">Find the user you want to manage and select them to view/edit their information.</p>
                    </div>
                    <hr>

                    <Searchbar v-model="fetcher.query"
                               :disabled="!fetcher.canRequest()"
                               @submit="submitSearch"
                               placeholder="Find by username, email or id..."/>

                    <table>
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="user in userListResponse?.users ?? []">
                            <td>{{user.id}}</td>
                            <td>{{user.username}}</td>
                            <td>{{user.email}}</td>
                        </tr>
                        </tbody>
                    </table>

                    <Pagination :last-page="userListResponse?.pages ?? 1"
                                :current-page="fetcher.page"
                                :per-page="userListResponse?.perPage ?? 15"
                                :total="userListResponse?.total ?? 0"
                                :fetchable="fetcher"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Navbar from "@/components/Common/Navbar.vue";
import Searchbar from "@/components/Common/Form/Searchbar.vue";
import Fetchable from "@/models/fetchable/Fetchable";
import Pagination from "@/components/Common/Pagination/Pagination.vue";
import UserRepository from "@/services/UserRepository";

export default {
    name: "AdminManageUsersPage",
    components: {Pagination, Searchbar, Navbar},

    methods: {
        async submitSearch() {
            if (!this.fetcher.canRequest()) {
                return;
            }

            await this.fetcher.fetch(this);
        },
        async fetchUsers() {
            this.userListResponse = await UserRepository.findUsersByQuery(this.fetcher.query, this.fetcher.page);
            console.log(this.userListResponse)
        }
    },

    mounted() {
        this.submitSearch();
    },

    data() {
        return {
            users: [],
            fetcher: new Fetchable(this.fetchUsers, this.$route.query?.query ?? '', parseInt(this.$route.query?.page ?? '1') ?? 1),
            userListResponse: null,
        }
    }
}
</script>

<style scoped>

</style>
