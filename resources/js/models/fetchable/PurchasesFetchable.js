import Fetchable from "@/models/fetchable/Fetchable";

export default class PurchasesFetchable extends Fetchable {

    startDate;
    endDate;

    previousStartDate;
    previousEndDate;

    constructor(fetcher, query = "", page = 1, startDate = null, endDate = null) {
        super(fetcher, query, page);
        this.startDate = startDate;
        this.endDate = endDate;
        this.previousStartDate = null;
        this.previousEndDate = null;
    }

    canRequest() {
        return !this.loading && (this.isDifferentQuery() || this.isDifferentPage() || this.isDifferentStartDate() || this.isDifferentEndDate());
    }

    isDifferentStartDate() {
        return this.startDate !== this.previousStartDate;
    }

    isDifferentEndDate() {
        return this.endDate !== this.previousEndDate;
    }

    start() {
        if (super.start()) {
            this.previousStartDate = this.startDate;
            this.previousEndDate = this.endDate;
            return true;
        }
        return false;
    }

}
