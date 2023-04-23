export default class Order {

    /**
     * @type {string}
     */
    id;

    /**
     * @type {number}
     */
    user_id;

    /**
     * @type {number}
     */
    plugin_id;

    /**
     * @type {string}
     */
    order_id;

    /**
     * @type {number}
     */
    payment_amount;

    /**
     * @type {string}
     */
    status;

    /**
     * @type {any}
     */
    breakdown;

    /**
     * @type {Date}
     */
    created_at;

    /**
     * @type {Date}
     */
    updated_at;

    static fromJson(json) {
        if (!json) return null;
        let order = new Order();
        for (let key in json) {
            if (key === "created_at" || key === "updated_at") {
                order[key] = new Date(json[key]);
                continue;
            } else if (key === "user_id" || key === "plugin_id") {
                order[key] = parseInt(json[key]);
                continue;
            } else if (key === "payment_amount") {
                order[key] = parseFloat(json[key]);
                continue;
            } else if (key === "breakdown") {
                order[key] = JSON.parse(json[key]);
                continue;
            }
            order[key] = json[key];
        }
        return order;
    }

}
