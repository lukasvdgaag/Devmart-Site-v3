window.addEventListener('load', () => {
    document.querySelectorAll("[name='dark_mode']").forEach(e => {
        e.addEventListener('change', (ev) => {
            console.log(ev.target.checked);
            if (ev.target.checked) {
                document.body.classList.add("dark-theme");
            } else {
                document.body.classList.remove("dark-theme");
            }
        });
    });

    document.querySelectorAll(".password-view-toggle").forEach(e => {
        if (e.hasAttribute("for")) {
            const input = document.querySelector(`#${e.getAttribute('for')}`);
            if (input == null) return;

            e.addEventListener('click', () => {
                if (input.type === "text") {
                    e.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512">
                                                <!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                                <path d="M150.7 92.77C195 58.27 251.8 32 320 32C400.8 32 465.5 68.84 512.6 112.6C559.4 156 590.7 207.1 605.5 243.7C608.8 251.6 608.8 260.4 605.5 268.3C592.1 300.6 565.2 346.1 525.6 386.7L630.8 469.1C641.2 477.3 643.1 492.4 634.9 502.8C626.7 513.2 611.6 515.1 601.2 506.9L9.196 42.89C-1.236 34.71-3.065 19.63 5.112 9.196C13.29-1.236 28.37-3.065 38.81 5.112L150.7 92.77zM223.1 149.5L313.4 220.3C317.6 211.8 320 202.2 320 191.1C320 180.5 316.1 169.7 311.6 160.4C314.4 160.1 317.2 159.1 320 159.1C373 159.1 416 202.1 416 255.1C416 269.7 413.1 282.7 407.1 294.5L446.6 324.7C457.7 304.3 464 280.9 464 255.1C464 176.5 399.5 111.1 320 111.1C282.7 111.1 248.6 126.2 223.1 149.5zM320 480C239.2 480 174.5 443.2 127.4 399.4C80.62 355.1 49.34 304 34.46 268.3C31.18 260.4 31.18 251.6 34.46 243.7C44 220.8 60.29 191.2 83.09 161.5L177.4 235.8C176.5 242.4 176 249.1 176 255.1C176 335.5 240.5 400 320 400C338.7 400 356.6 396.4 373 389.9L446.2 447.5C409.9 467.1 367.8 480 320 480H320z"/>
                                            </svg>`;
                    input.type = "password";
                } else {
                    e.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 576 512">
                                                <!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                                                <path d="M279.6 160.4C282.4 160.1 285.2 160 288 160C341 160 384 202.1 384 256C384 309 341 352 288 352C234.1 352 192 309 192 256C192 253.2 192.1 250.4 192.4 247.6C201.7 252.1 212.5 256 224 256C259.3 256 288 227.3 288 192C288 180.5 284.1 169.7 279.6 160.4zM480.6 112.6C527.4 156 558.7 207.1 573.5 243.7C576.8 251.6 576.8 260.4 573.5 268.3C558.7 304 527.4 355.1 480.6 399.4C433.5 443.2 368.8 480 288 480C207.2 480 142.5 443.2 95.42 399.4C48.62 355.1 17.34 304 2.461 268.3C-.8205 260.4-.8205 251.6 2.461 243.7C17.34 207.1 48.62 156 95.42 112.6C142.5 68.84 207.2 32 288 32C368.8 32 433.5 68.84 480.6 112.6V112.6zM288 112C208.5 112 144 176.5 144 256C144 335.5 208.5 400 288 400C367.5 400 432 335.5 432 256C432 176.5 367.5 112 288 112z"/>
                                            </svg>`;
                    input.type = "text";
                }
            });
        }
    });
});

function setURLParam(property, value) {
    const urlParams = new URL(window.location.href);
    urlParams.searchParams.set(property, value);
    window.history.replaceState({additionalInformation: 'Updated the URL'}, null, urlParams.toString());
}

function removeURLParam(property) {
    const urlParams = new URL(window.location.href);
    urlParams.searchParams.delete(property);
    window.history.replaceState({additionalInformation: 'Updated the URL'}, null, urlParams.toString());
}

function getURLParam(property) {
    const urlParams = new URL(window.location.href);
    return urlParams.searchParams.get(property);
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

/**
 * Create a DOM element with classes and optionally an id.
 * @param tagName The DOM element name.
 * @param classes An array of class names.
 * @param id An optional id.
 * @param attributes An optional object of attributes.
 * @param innerHTML The inner HTML of the element.
 */
function createElement(tagName, classes = [], id = null, attributes = {}, innerHTML = "") {
    let element = document.createElement(tagName);
    element.classList.add(...classes);
    element.innerHTML = innerHTML;
    if (id != null) element.id = id;
    for (const [key, value] of Object.entries(attributes)) {
        element.setAttribute(key, value);
    }
    return element;
}

/**
 * Send a GET request to a specific URL.
 * @param href URL to post to.
 * @param body The JSON body of the request.
 * @param headers Any optional headers for the request.
 * @returns {Promise<Response>}
 */
async function get(href, body = {}, headers = {}) {
    return fetch(href, {
        method: 'GET',
        headers: {...headers, 'Content-Type': 'application/json'},
        body: JSON.stringify(body)
    });
}

/**
 * Send a POST request to a specific URL.
 * @param href URL to post to.
 * @param body The JSON body of the request.
 * @param headers Any optional headers for the request.
 * @returns {Promise<Response>}
 */
async function post(href, body = {}, headers = {}) {
    return fetch(href, {
        method: 'POST',
        headers: {...headers, 'Content-Type': 'application/json'},
        body: JSON.stringify(body)
    });
}

/**
 * Format money strings.
 * @param number Number to format.
 * @returns {string}
 */
function formatMoney(number) {
    return "&euro; " + parseFloat(number).toLocaleString('nl-NL', {
        maximumFractionDigits: 2,
        minimumFractionDigits: 2
    });
}

function getDaysBetween(date) {
    const now = new Date().getTime();
    const diff = now - date;

    return Math.round(diff / (60 * 60 * 24));
}


function formatDate(date, withTime = true, shortened = false) {
    if (shortened) {
        let difference = new Date().getTime() - date;

        if (difference < 60) {
            let seconds = Math.round(difference);
            return `${seconds} sec${seconds > 1 ? "s" : ""}`;
        } else if (difference < 60 * 60) {
            let minutes = Math.round(difference / 60);
            return `${minutes} min${minutes > 1 ? "s" : ""}`
        } else if (difference < 60 * 60 * 24) {
            let hours = Math.round(difference / 60 / 60);
            return `${hours} hour${hours > 1 ? "s" : ""}`;
        } else if (difference < 60 * 60 * 24 * 30) {
            let days = Math.round(difference / 60 / 60 / 24);
            return `${days} day${days > 1 ? "s" : ""}`;
        } else if (difference < 60 * 60 * 24 * 365) {
            let months = Math.round(difference / 60 / 60 / 24 / 30);
            return `${months} month${months > 1 ? "s" : ""}`;
        } else {
            let years = Math.round(difference / 60 / 60 / 24 / 356);
            return `${years} year${years > 1 ? "s" : ""}`
        }
    }

    const daysBetween = getDaysBetween(date);

    if (withTime) {
        if (daysBetween === 0) {
            return "Today at " + formatDateFromMillis("h:m A", date);
        } else if (daysBetween === 1) {
            return "Yesterday at " + formatDateFromMillis("h:m A", date);
        } else if (daysBetween < 7) {
            return formatDateFromMillis("l \a\t h:m A", date);
        } else if (daysBetween < 365) {
            return formatDateFromMillis('M j \a\t h:m A', date);
        } else {
            return formatDateFromMillis('M j, Y \a\t h:m A', date);
        }
    } else {
        if (daysBetween === 0) {
            return "Today";
        } else if (daysBetween === 1) {
            return 'Yesterday';
        } else if (daysBetween < 7) {
            return formatDateFromMillis('l', date);
        } else if (daysBetween < 365) {
            return formatDateFromMillis('M dS', date);
        } else {
            return formatDateFromMillis('M dS, Y', date);
        }
    }
}

function formatDateFromMillis(format, millis) {
    const date = new Date(millis);

    let year = new Intl.DateTimeFormat('en-US', { year: 'numeric' }).format(date);
    let dayInWeek = new Intl.DateTimeFormat('en-US', { weekday: 'long' }).format(date);
    let month = new Intl.DateTimeFormat('en-US', { month: 'long' }).format(date);
    let day = new Intl.DateTimeFormat('en-US', { day: 'numeric' }).format(date);
    let hour = new Intl.DateTimeFormat('en-US', { hour: '2-digit', hour12: true }).format(date);
    let min = new Intl.DateTimeFormat('en-US', { minute: '2-digit' }).format(date);

    let st;
    switch (day.substring(day.length - 1)) {
        case "1":
            st = "st";
            break;
        case "2":
            st = "nd";
            break;
        case "3":
            st = "rd";
            break;
        default:
            st = "th";
            break;
    }
    let ampmUpper = hour <= 11 ? "AM" : "PM";

    format = format.replaceAll(/(?<!\\)y/, year);
    format = format.replace(/(?<!\\)M/, month);
    format = format.replace(/(?<!\\)l/, dayInWeek);
    format = format.replace(/(?<!\\)d/, day);
    format = format.replace(/(?<!\\)S/, st);
    format = format.replace(/(?<!\\)h/, hour);
    format = format.replace(/(?<!\\)m/, min);
    format = format.replace(/(?<!\\)A/, ampmUpper);
    format = format.replace(/(?<!\\)a/, ampmUpper.toLowerCase());
    return format;
}
