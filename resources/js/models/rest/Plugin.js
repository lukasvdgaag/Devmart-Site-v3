import PluginUpdate from "@/models/rest/PluginUpdate";
import Sale from "@/models/rest/Sale";
import DateService from "@/services/DateService";

export default class Plugin {

    /**
     * @type {number}
     */
    id;
    /**
     * @type {string}
     */
    name;
    /**
     * @type {string}
     */
    description;
    /**
     * @type {string}
     */
    title;
    /**
     * @type {string}
     */
    features;
    /**
     * @type {boolean}
     */
    custom;
    /**
     * @type {string|null}
     */
    spigot_link;
    /**
     * @type {string|null}
     */
    github_link;
    /**
     * @type {string}
     */
    minecraft_versions;
    /**
     * @type {string}
     */
    dependencies;
    /**
     * @type {string}
     */
    categories;
    /**
     * @type {string}
     */
    last_updated;
    /**
     * @type {number}
     */
    author;
    /**
     * @type {number}
     */
    price;
    /**
     * @type {string|null}
     */
    logo_url;
    /**
     * @type {string|null}
     */
    banner_url;
    /**
     * @type {string|null}
     */
    donation_url;
    /**
     * @type {Date}
     */
    created_at;
    /**
     * @type {Date}
     */
    updated_at;
    /**
     * @type {string|null}
     */
    author_username;
    /**
     * @type {number}
     */
    downloads;
    /**
     * @type {PluginUpdate}
     */
    latest_update;
    /**
     * @type {Sale|null}
     */
    sale;

    static fromJson(json) {
        if (!json) return null;
        let plugin = new Plugin();
        for (let key in json) {
            if (key === "latest_update") {
                plugin[key] = PluginUpdate.fromJson(json[key]);
                continue;
            } else if (key === "sale") {
                plugin[key] = Sale.fromJson(json[key]);
                continue;
            }
            plugin[key] = json[key];
        }
        return plugin;
    }

    isRecentlyUpdated() {
        return DateService.isAfter(
            this.latest_update?.created_at ?? new Date(this.last_updated),
            DateService.offset(-7)
        );
    }

}
