export default class User {

    /**
     * @type {number}
     */
    id;
    /**
     * @type {string}
     */
    username;
    /**
     * @type {string}
     */
    email;
    /**
     * @type {Date}
     */
    email_verified_at;
    /**
     * @type {string}
     */
    discord_id;
    /**
     * @type {boolean}
     */
    discord_verified;
    /**
     * @type {string}
     */
    role;
    /**
     * @type {string}
     */
    theme;
    /**
     * @type {Date}
     */
    username_changed_at;
    /**
     * @type {string}
     */
    spigot;
    /**
     * @type {boolean}
     */
    spigot_verified;
    /**
     * @type {string}
     */
    discord_suggestion_notifications;
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
        let user = new User();
        user.updateFromJson(json);
        return user;
    }

    updateFromJson(json) {
        if (!json) return null;
        for (let key in json) {
            if (key === "id") {
                this[key] = parseInt(json[key]);
                continue;
            } else if (key === "discord_verified" || key === "spigot_verified") {
                this[key] = json[key] === 1;
                continue;
            } else if (key === "email_verified_at" || key === "username_changed_at" || key === "created_at" || key === "updated_at") {
                this[key] = new Date(json[key]);
                continue;
            }
            this[key] = json[key];
        }
        return this;
    }

}
