import router from "@/router";

export default class Fetchable {

    /**
     * The search query to include in the request.
     * @type {string|null} query
     */
    query;
    /**
     * The previous search query, used to determine if a new request should be made.
     * @type {string|null} lastQuery
     */
    lastQuery;
    /**
     * Whether data is currently being loaded.
     * @type {boolean} loading
     */
    loading;
    /**
     * The current page to include in the request.
     * @type {number|null} page
     */
    page;
    /**
     * The previous page, used to determine if a new request should be made.
     * @type {number|null} lastPage
     */
    lastPage;
    /**
     * The fetcher method that performs the request.
     * @type {function:Promise} fetcher
     */
    fetcher;

    /**
     * @param {function:Promise} fetcher
     * @param {string|null} query
     * @param {number|null} page
     */
    constructor(fetcher, query = "", page = 1) {
        this.fetcher = fetcher;
        this.query = query;
        this.lastQuery = null;
        this.page = page;
        this.lastPage = null;
        this.loading = false;
    }

    /**
     * Whether a new request can be made.
     * @returns {boolean}
     */
    canRequest() {
        return !this.loading && (this.isDifferentQuery() || this.isDifferentPage());
    }

    isDifferentPage() {
        return this.page == null || this.page !== this.lastPage;
    }

    isDifferentQuery() {
        return this.query == null || this.query !== this.lastQuery;
    }

    /**
     * Navigate to a specific page.
     * @param {number} page
     * @param thisArg
     * @returns {Promise<void>}
     */
    async navigateToPage(page, thisArg = undefined) {
        this.page = page;
        if (!this.canRequest()) return;

        await router.isReady();
        await router.replace({
            query: {
                ...router.currentRoute.value.query,
                page
            }
        });

        await this.fetch(thisArg);
    }

    /**
     * Perform the request.
     * @param thisArg
     * @returns {Promise<void>}
     */
    async fetch(thisArg) {
        if (this.start()) {
            await this.fetcher.call(thisArg ?? this);
            this.finish();
        }
    }

    /**
     * Start requesting data.
     *
     * @return {boolean} Whether the querying can start.
     */
    start() {
        if (this.canRequest()) {
            this.loading = true;
            this.lastQuery = this.query;
            this.lastPage = this.page;
            return true;
        }
        return false;
    }

    finish() {
        this.loading = false;
    }

}
