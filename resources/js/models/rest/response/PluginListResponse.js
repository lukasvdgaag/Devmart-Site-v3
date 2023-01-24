import PageableRestResponse from "@/models/rest/response/PageableRestResponse";

export default class PluginListResponse extends PageableRestResponse {

    /**
     * @type {Plugin[]}
     */
    plugins;

    /**
     * @param {number} total
     * @param {number} currentPage
     * @param {number} pages
     * @param {Plugin[]} plugins
     */
    constructor(total, currentPage, pages, plugins) {
        super(total, currentPage, pages);
        this.plugins = plugins;
    }

}
