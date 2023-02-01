import PluginUpdate from "@/models/rest/PluginUpdate";
import Sale from "@/models/rest/Sale";

export default class Paste {

    /**
     * @type {number}
     */
    id;
    /**
     * @type {number}
     */
    creator;
    /**
     * @type {string}
     */
    creator_username;
    /**
     * @type {string}
     */
    title;
    /**
     * @type {string}
     */
    style;
    /**
     * @type {string}
     */
    visibility;
    /**
     * @type {Date}
     */
    expire_at;
    /**
     * @type {Date}
     */
    created_at;
    /**
     * @type {Date}
     */
    updated_at;
    /**
     * @type {string}
     */
    content;

    static fromJson(json) {
        if (!json) return null;
        let paste = new Paste();
        for (let key in json) {
            if (key === "created_at" || key === "updated_at" || key === "expire_at") {
                paste[key] = new Date(json[key]);
                continue;
            }
            paste[key] = json[key];
        }
        return paste;
    }

}
