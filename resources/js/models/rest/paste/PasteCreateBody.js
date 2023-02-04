export default class PasteCreateBody {

    title;
    style;
    visibility;
    lifetime;
    content;

    constructor(title, style, visibility, lifetime, content) {
        this.title = title;
        this.style = style;
        this.visibility = visibility;
        this.lifetime = lifetime;
        this.content = content;
    }

}
