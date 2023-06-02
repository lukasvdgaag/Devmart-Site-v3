export default class ProductSale {

    /**
     * @type {number}
     */
    percentage;
    /**
     * @type {string}
     */
    start_date;
    /**
     * @type {string}
     */
    end_date;

    static fromJson(json) {
        if (!json) return null;
        let sale = new ProductSale();
        for (let key in json) {
            if (key === "percentage") {
                sale[key] = parseInt(json[key]);
                continue;
            }
            sale[key] = json[key];
        }
        return sale;
    }

    getStartDate() {
        return new Date(this.start_date);
    }

    getEndDate() {
        return new Date(this.end_date);
    }

}
