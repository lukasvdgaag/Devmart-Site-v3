export default class PluginUpdate {

    id;
    plugin;
    version;
    beta_number;
    title;
    changelog;
    created_at;
    downloads;
    file_extension;
    file_size;
    file_name;
    effective_version;

    static fromJson(json) {
        if (!json) return null;
        let pluginUpdate = new PluginUpdate();
        for (let key in json) {
            if (key === "created_at") {
                pluginUpdate[key] = new Date(json[key]);
                continue;
            }
            pluginUpdate[key] = json[key];
        }
        return pluginUpdate;
    }

}
