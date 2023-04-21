export default class PluginPurchase {

    /**
     * @type {number}
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
     * @type {Date}
     */
    date;
    /**
     * @type {number}
     */
    payment_id;

    amount;
    /**
     * @type {string}
     */
    email;
    /**
     * @type {string}
     */
    platform;
    /**
     * @type {string}
     */
    payment_status;
    /**
     * @type {Date}
     */
    payment_date;
    /**
     * @type {string}
     */
    username;

    static fromJson(json) {
        if (!json) return null;
        let pluginUpdate = new PluginPurchase();
        for (let key in json) {
            if (key === "payment_date" || key === "date") {
                pluginUpdate[key] = new Date(json[key]);
                continue;
            } else if (key === "id" || key === "user_id" || key === "plugin_id" || key === "payment_id") {
                pluginUpdate[key] = parseInt(json[key]);
                continue;
            } else if (key === "amount") {
                console.log(json[key]);
                pluginUpdate[key] = parseFloat(json[key]);
                continue;
            }
            pluginUpdate[key] = json[key];
        }
        return pluginUpdate;
    }

}
