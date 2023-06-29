export default class PluginPermissions {

    /**
     * @type {boolean}
     */
    download;

    /**
     * @type {boolean}
     */
    modify;

    static fromJson(json) {
        if (!json) return null;
        let pluginPermissions = new PluginPermissions();
        for (let key in json) {
            pluginPermissions[key] = json[key];
        }
        return pluginPermissions;
    }

}
