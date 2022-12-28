import {useRouter} from "vue-router";

export default class SidebarItem {

    link;
    icon;
    label;
    renderRequirements;
    isDefault;
    activeRequirements;

    /**
     *
     * @param link
     * @param icon
     * @param label
     * @param {boolean} renderRequirements
     * @param {boolean} isDefault
     * @param {boolean} activeRequirements
     */
    constructor(link, icon, label, renderRequirements = true, isDefault = false, activeRequirements = undefined) {
        this.link = link;
        this.icon = icon;
        this.label = label;
        this.renderRequirements = renderRequirements;
        this.isDefault = isDefault;
        this.activeRequirements = activeRequirements;
    }

    static isQueryParam(key, value) {
        return useRouter().currentRoute.value.query[key] === value;
    }

}
