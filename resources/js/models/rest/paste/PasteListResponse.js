import PageableRestResponse from "@/models/rest/PageableRestResponse";

export default class PasteListResponse extends PageableRestResponse {

    pastes;

    constructor(total, currentPage, pages, pastes) {
        super(total, currentPage, pages);
        this.pastes = pastes;
    }


}
