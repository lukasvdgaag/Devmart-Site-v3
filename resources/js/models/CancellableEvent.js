export default class CancellableEvent {

    #cancelled;

    constructor(def = false) {
        this.cancelled = def;
    }

    cancel(value = true) {
        this.cancelled = value;
    }

    isCancelled() {
        return this.cancelled;
    }

}
