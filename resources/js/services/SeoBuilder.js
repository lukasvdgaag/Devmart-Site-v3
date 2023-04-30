export default class SeoBuilder {

    #context;
    #title = undefined;
    #description = undefined;
    #tags = [];
    #withReturn = false;

    constructor(context) {
        this.#context = context;
    }

    static createTitle(title, complement = 'Devmart', separator = '-') {
        return {
            inner: title,
            separator: separator,
            complement: complement
        }
    }

    title(title) {
        this.#title = title;
        return this;
    }

    tags(tags) {
        this.#tags = tags;
        return this;
    }

    withReturn(withReturn = true) {
        this.#withReturn = withReturn;
        return this;
    }

    build() {
        if (this.#withReturn) {
            const self = this;

            return {
                title() {
                    return {
                        inner: self.#title ?? '',
                        separator: '|',
                        complement: 'Devmart'
                    }
                }
            }

        } else {
            console.log('wapd')
            if (this.#title) this.#context.title = this.#title;
            if (this.#tags) this.#context.meta = this.#tags;

            if (this.#title || this.#tags) this.#context.$emit('updateHead');
        }
    }


}
