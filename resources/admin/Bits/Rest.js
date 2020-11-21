export default {
    get(route, data = {}) {
        return window.MyPlugin.request('GET', route, data);
    },
    post(route, data = {}) {
        return window.MyPlugin.request('POST', route, data);
    },
    delete(route, data = {}) {
        return window.MyPlugin.request('DELETE', route, data);
    },
    put(route, data = {}) {
        return window.MyPlugin.request('PUT', route, data);
    },
    patch(route, data = {}) {
        return window.MyPlugin.request('PATCH', route, data);
    }
};

jQuery(document).ajaxSuccess((event, xhr, settings) => {
    const nonce = xhr.getResponseHeader('X-WP-Nonce');

    if (nonce) {
        window.MyPluginAdmin.rest.nonce = nonce;
    }
});
