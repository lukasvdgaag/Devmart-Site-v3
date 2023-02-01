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

    formatTimeLeft(date) {
        const now = new Date();
        const daysLeft = this.diffInDays(date, now);

        // show in hh:mm:ss format
        const diff = Math.abs(date.getTime() - now.getTime());
        const days = Math.floor(diff / (1000 * 3600 * 24));
        const hours = Math.floor((diff % (1000 * 3600 * 24)) / (1000 * 3600));
        const minutes = Math.floor((diff % (1000 * 3600)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        if (days > 0) {
            return `${days}d ${hours}h ${minutes}m ${seconds}s left`;
        } else if (hours > 0) {
            return `${hours}h ${minutes}m ${seconds}s left`;
        } else if (minutes > 0) {
            return `${minutes}m ${seconds}s left`;
        } else {
            return `${seconds}s left`;
        }
    },

    formatTimeAgo(date) {
        const now = new Date();

        const diff = Math.abs(now.getTime() - date.getTime());
        const minutes = Math.floor((diff % (1000 * 3600)) / (1000 * 60));
        const hours = Math.floor((diff % (1000 * 3600 * 24)) / (1000 * 3600));
        const days = Math.floor(diff / (1000 * 3600 * 24));
        const weeks = Math.floor(diff / (1000 * 3600 * 24 * 7));
        const months = Math.floor(diff / (1000 * 3600 * 24 * 30));
        const years = Math.floor(diff / (1000 * 3600 * 24 * 365));

        if (minutes <= 2) {
            return 'Just now';
        }
        if (hours <= 1) {
            return `${minutes} minutes ago`;
        }
        if (days <= 1) {
            return `${hours} hour${hours>1 ? 's' : ''} ago`;
        }
        if (weeks <= 1) {
            return `${days} day${days>1 ? 's' : ''} ago`;
        }
        if (months <= 1) {
            return `${weeks} week${weeks>1 ? 's' : ''} ago`;
        }
        if (years <= 1) {
            return `${months} month${months>1 ? 's' : ''} ago`;
        }
        return `${years} year${years>1 ? 's' : ''} ago`;
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
