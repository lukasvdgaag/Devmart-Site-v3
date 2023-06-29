import PageableRestResponse from "@/models/rest/PageableRestResponse";

export default class PluginPurchasesResponse extends PageableRestResponse {

    /**
     * @type {PluginPurchase[]}
     */
    purchases;

    /**
     * @param {number} total
     * @param {number} currentPage
     * @param {number} pages
     * @param {PluginPurchase[]} purchases
     */
    constructor(total, currentPage, pages, purchases) {
        super(total, currentPage, pages);
        this.purchases = purchases;
    }

}
