<template>
    <div v-if="lastPage > 1"
         class="flex items-center mt-3 justify-between border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-3 sm:px-6">
        <div class="flex flex-1 justify-between sm:hidden">
            <div class="flex flex-row justify-content-start w-full">
                <PaginationItem v-if="currentPage-1 >= 1" :page="Math.max(1, currentPage-1)" @update="handleUpdate">
                    Previous
                </PaginationItem>
            </div>
            <div class="flex flex-row justify-end w-full">
                <PaginationItem v-if="currentPage+1 <= lastPage" :page="Math.min(lastPage, currentPage+1)" @update="handleUpdate">
                    Next
                </PaginationItem>
            </div>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:flex-wrap gap-2 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700 dark:text-gray-400">
                    Showing <span class="font-medium">{{ start }}</span> to <span class="font-medium">{{ end }}</span> of <span
                    class="font-medium">{{ total }}</span> results
                </p>
            </div>
            <div class="flex flex-col gap-2">
                <nav aria-label="Pagination" class="group isolate inline-flex -space-x-px rounded-md shadow-sm">
                    <PaginationItem v-if="currentPage-1 >= 1"
                                    :active="false"
                                    :disabled="false"
                                    :page="currentPage-1"
                                    class="rounded-none rounded-l-md px-2"
                                    @update="handleUpdate">
                        <span class="sr-only">Previous</span>
                        <font-awesome-icon aria-hidden="true" class="text-sm w-5" icon="fa-chevron-left"/>
                    </PaginationItem>
                    <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->
                    <PaginationItem
                        v-for="page in pages"
                        :active="page.active"
                        :disabled="page.disabled"
                        :page="page.page"
                        class="first:rounded-l-md last:rounded-r-md"
                        @update="handleUpdate"/>

                    <PaginationItem v-if="currentPage+1 <= lastPage"
                                    :active="false"
                                    :disabled="false"
                                    :page="currentPage+1"
                                    class="rounded-none rounded-r-md px-2"
                                    @update="handleUpdate">
                        <span class="sr-only">Next</span>
                        <font-awesome-icon aria-hidden="true" class="text-sm w-5" icon="fa-chevron-right"/>
                    </PaginationItem>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
import PaginationItem from "@/components/Common/Pagination/PaginationItem.vue";
import Fetchable from "@/models/Fetchable";

export default {
    name: "Pagination",
    components: {PaginationItem},

    data() {
        return {
            firstPagesCount: 5,
            maxNavigationSlots: 7,
        }
    },

    methods: {
        handleUpdate(page) {
            if (this.fetchable) {
                this.fetchable.navigateToPage(page);
                return;
            }

            this.$router.replace({
                query: {
                    ...this.$route.query,
                    page
                }
            });
        }
    },

    computed: {
        start() {
            return (this.currentPage - 1) * this.perPage + 1;
        },
        end() {
            return Math.min(this.total, this.start + this.perPage - 1);
        },
        pages() {
            // for pagination, when we are on the first 5 pages, we want to show 1, 2, 3, 4, 5, ..., 10.
            // when we are on the last 5 pages, we want to show 1, ..., 6, 7, 8, 9, 10.
            // when we are in between, we want to show 1, ..., 4, 5, 6, ..., 10.
            // The page should be disabled if it is a filler such as ...
            // return an array of objects and use the format: [{page: 1, active: true, disabled: false}].
            // use the variable this.start for the first page, this.end for the last page, and this.currentPage for the current page.
            let pages = [];

            if (this.lastPage <= 7) {
                for (let i = 1; i <= this.lastPage; i++) {
                    pages.push({page: i, active: i === this.currentPage, disabled: false})
                }
                return pages
            }

            // Handle case when current page is in the first 3 pages
            if (this.currentPage <= 3) {
                for (let i = 1; i <= 5; i++) {
                    pages.push({page: i, active: i === this.currentPage, disabled: false})
                }
                pages.push({page: '...', active: false, disabled: true})
                pages.push({page: this.lastPage, active: false, disabled: false})
                return pages
            }

            // Handle case when current page is in the last 5 pages
            if (this.currentPage > this.lastPage - 3) {
                pages.push({page: 1, active: false, disabled: false})
                pages.push({page: '...', active: false, disabled: true})
                for (let i = this.lastPage - 4; i <= this.lastPage; i++) {
                    pages.push({page: i, active: i === this.currentPage, disabled: false})
                }
                return pages
            }

            // Handle case when current page is in between the first and last 5 pages
            pages.push({page: 1, active: false, disabled: false})
            pages.push({page: '...', active: false, disabled: true})
            pages.push({page: this.currentPage - 1, active: false, disabled: false})
            pages.push({page: this.currentPage, active: true, disabled: false})
            pages.push({page: this.currentPage + 1, active: false, disabled: false})
            pages.push({page: '...', active: false, disabled: true})
            pages.push({page: this.lastPage, active: false, disabled: false})

            return pages
        },
    },

    props: {
        total: {
            type: Number,
            required: true
        },
        perPage: {
            type: Number,
            required: true
        },
        currentPage: {
            type: Number,
            required: true
        },
        lastPage: {
            type: Number,
            required: true
        },
        fetchable: {
            type: Fetchable,
            required: false
        },
    }
}
</script>

<style scoped>

</style>
