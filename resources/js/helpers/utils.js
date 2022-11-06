export async function callApi(method = 'GET', url, body = null, options = {}) {
    options.method = method;
    if (body != null) options.body = body;

    return fetch(url, options);
}

export async function post(href, body = {}, headers = {}) {
    return fetch(href, {
        method: 'POST',
        headers: {...headers, 'Content-Type': 'application/json'},
        body: JSON.stringify(body)
    });
}

export async function get(href, body = {}, headers = {}) {
    return fetch(href, {
        method: 'GET',
        headers: {...headers, 'Content-Type': 'application/json'},
        body: JSON.stringify(body)
    });
}
