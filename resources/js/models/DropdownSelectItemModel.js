export default class DropdownSelectItemModel {

    text;
    helperText;
    value;
    display;

    constructor(text, helperText = null, value = null, display = () => true) {
        this.text = text;
        this.helperText = helperText;
        this.value = value;
        this.display = display
    }
}
