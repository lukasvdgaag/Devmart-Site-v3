export default {

    /**
     * @param {Number} numDays
     * @param {Date} date
     * @returns {Date}
     */
    offset(numDays, date = new Date()) {
        const daysAgo = new Date(date.getTime());
        daysAgo.setDate(daysAgo.getDate() + numDays);
        return daysAgo;
    },

    /**
     * @param {Date} toCheck
     * @param {Date} beforeDate
     * @returns {boolean}
     */
    isBefore(toCheck, beforeDate) {
        return toCheck.getTime() <= beforeDate.getTime();
    },

    /**
     * @param {Date} toCheck
     * @param {Date} afterDate
     * @returns {boolean}
     */
    isAfter(toCheck, afterDate) {
        return toCheck.getTime() >= afterDate.getTime();
    },

    /**
     * @param {Date} date1
     * @param {Date} date2
     * @returns {number}
     */
    diffInDays(date1, date2) {
        const diff = Math.abs(date1.getTime() - date2.getTime());
        return Math.ceil(diff / (1000 * 3600 * 24));
    },

    /**
     * @param {Date} date
     * @returns {string}
     */
    formatDate(date) {
        // format the date in format Month Day, e.g. January 1
        return new Intl.DateTimeFormat('en-US', {month: 'long', day: 'numeric'}).format(date);
    },

    /**
     * @param {Date} date
     * @param {boolean} withTime
     * @returns {string}
     */
    formatDateRelatively(date, withTime = false) {
        // format the date relatively to today, including the time in 12h format.
        // e.g. Today at 12:00 PM, Yesterday at 12:00 PM, August 24 at 12:00 PM, or when over a year: January 1, 2020 at 12:00 PM
        const today = new Date();
        const diff = this.diffInDays(date, today);
        if (diff < 1) {
            return `Today${withTime ? ' at ' + this.formatTime(date) : ''}`;
        } else if (diff < 2) {
            return `Yesterday${withTime ? ' at ' + this.formatTime(date) : ''}`;
        } else if (diff < 365) {
            return `${this.formatDate(date)}${withTime ? ' at ' + this.formatTime(date) : ''}`;
        } else {
            return `${this.formatDate(date)}, ${date.getFullYear()}${withTime ? ' at ' + this.formatTime(date) : ''}`;
        }
    },

    /**
     * @param {Date} date
     */
    formatTime(date) {
        // format the time in 12h format, e.g. 12:00 PM
        return new Intl.DateTimeFormat('en-US', {hour: 'numeric', minute: 'numeric', hour12: true}).format(date);
    }
}
