import Fetchable from "@/models/Fetchable";

export default class PluginsFetchable extends Fetchable {

    filter;
    previousFilter;

    constructor(fetcher, query = "", page = 1, filter = "all") {
        super(fetcher, query, page);
        this.filter = filter;
        this.previousFilter = null;
    }

    canRequest() {
        return !this.loading && (this.isDifferentQuery() || this.isDifferentPage() || this.isDifferentFilter());
    }

    isDifferentFilter() {
        return this.filter !== this.previousFilter;
    }

    start() {
        if (super.start()) {
            this.previousFilter = this.filter;
            return true;
        }
        return false;
    }

}
