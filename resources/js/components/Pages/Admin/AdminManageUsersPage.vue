<script>
    import Navbar from "@/components/Common/Navbar.vue";
    import Searchbar from "@/components/Common/Form/Searchbar.vue";
    import Fetchable from "@/models/fetchable/Fetchable";

    export default {
        name: "AdminManageUsersPage",
        components: {Searchbar, Navbar},

        methods: {
            async submitSearch() {
                if (!this.fetcher.canRequest()) {
                    return;
                }

                await this.fetcher.fetch(this);
            },
            async fetchUsers() {

            }
        },

        data() {
            return {
                users: [],
                fetcher: new Fetchable(this.fetchUsers, this.$route.query?.query ?? '', parseInt(this.$route.query?.page ?? '1') ?? 1),
            }
        }
    }
</script>

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
                               placeholder="Find by username, email or id..." />

                    <table>
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>6</td>
                            <td>minifridge</td>
                            <td>lukasvdgaag@gmail.com</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
