export default class SidebarItem {

    link;
    icon;
    label;
    renderRequirements;
    isDefault;

    /**
     *
     * @param link
     * @param icon
     * @param label
     * @param {boolean} renderRequirements
     * @param {boolean} isDefault
     */
    constructor(link, icon, label, renderRequirements = true, isDefault = false) {
        this.link = link;
        this.icon = icon;
        this.label = label;
        this.renderRequirements = renderRequirements;
        this.isDefault = isDefault;
    }

}
