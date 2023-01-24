export default class PageableRestResponse {

    /**
     * @type {number}
     */
    total;
    /**
     * @type {number}
     */
    currentPage;
    /**
     * @type {number}
     */
    pages;

    /**
     * @param {number} total
     * @param {number} currentPage
     * @param {number} pages
     */
    constructor(total, currentPage, pages) {
        this.total = total;
        this.currentPage = currentPage;
        this.pages = pages;
    }

}
