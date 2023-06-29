import PageableRestResponse from "@/models/rest/PageableRestResponse";

export default class PluginUpdatesResponse extends PageableRestResponse {

    /**
     * @type {PluginUpdate[]}
     */
    updates;

    /**
     * @param {number} total
     * @param {number} currentPage
     * @param {number} pages
     * @param {PluginUpdate[]} updates
     */
    constructor(total, currentPage, pages, updates) {
        super(total, currentPage, pages);
        this.updates = updates;
    }

}
