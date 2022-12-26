export class DateService {

    /**
     * @param {Number} numDays
     * @param {Date} date
     * @returns {Date}
     */
    offset(numDays, date = new Date()) {
        const daysAgo = new Date(date.getTime());
        daysAgo.setDate(daysAgo.getDate() + numDays);
        return daysAgo;
    }

    /**
     * @param {Date} toCheck
     * @param {Date} beforeDate
     * @returns {boolean}
     */
    isBefore(toCheck, beforeDate) {
        return toCheck.getTime() <= beforeDate.getTime();
    }

    /**
     * @param {Date} toCheck
     * @param {Date} afterDate
     * @returns {boolean}
     */
    isAfter(toCheck, afterDate) {
        return toCheck.getTime() >= afterDate.getTime();
    }

    /**
     * @param {Date} date1
     * @param {Date} date2
     * @returns {number}
     */
    diffInDays(date1, date2) {
        const diff = Math.abs(date1.getTime() - date2.getTime());
        return Math.ceil(diff / (1000 * 3600 * 24));
    }

    /**
     * @param {Date} date
     * @returns {string}
     */
    formatDay(date) {
        // format the date in format Month Day, e.g. January 1
        return new Intl.DateTimeFormat('en-US', { month: 'short', day: 'numeric' }).format(date);
    }

}
