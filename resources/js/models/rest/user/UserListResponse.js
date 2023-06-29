import PageableRestResponse from "@/models/rest/PageableRestResponse";

export default class UserListResponse extends PageableRestResponse {

    users;

    constructor(users, total, currentPage, pages) {
        super(total, currentPage, pages);
        this.users = users;
    }

}
