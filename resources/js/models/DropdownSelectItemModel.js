export default class DropdownSelectItemModel {

    text;
    helperText;
    value;

    constructor(text, helperText = null, value = null) {
        this.text = text;
        this.helperText = helperText;
        this.value = value;
    }
}
