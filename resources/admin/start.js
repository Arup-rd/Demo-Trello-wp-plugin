import routes from './routes';
import { store } from './store'
const vueRouter = new window.MyPlugin.Router({
    routes: window.MyPlugin.applyFilters('my_plugin_global_routes', routes)
});

window.MyPlugin.Vue.prototype.$rest = window.MyPlugin.$rest;
window.MyPlugin.Vue.prototype.$get = window.MyPlugin.$get;
window.MyPlugin.Vue.prototype.$post = window.MyPlugin.$post;
window.MyPlugin.Vue.prototype.$delete = window.MyPlugin.$del;
window.MyPlugin.Vue.prototype.$put = window.MyPlugin.$put;
window.MyPlugin.Vue.prototype.$patch = window.MyPlugin.$patch;
window.MyPlugin.Vue.prototype.$bus = new window.MyPlugin.Vue();

window.MyPlugin.request = function(method, route, data = {}) {
    const url = `${window.MyPluginAdmin.rest.url}/${route}`;
    const headers = { 'X-WP-Nonce': window.MyPluginAdmin.rest.nonce };

    if (['PUT', 'PATCH', 'DELETE'].indexOf(method.toUpperCase()) !== -1) {
        headers['X-HTTP-Method-Override'] = method;
        method = 'POST';
    }
    data.query_timestamp = Date.now();

    return new Promise((resolve, reject) => {
        window.jQuery
            .ajax({
                url: url,
                type: method,
                data: data,
                headers: headers
            })
            .then((response) => resolve(response))
            .fail((errors) => reject(errors.responseJSON));
    });
};

new window.MyPlugin.Vue({
    el: '#my_plugin_app',
    render: (h) => h(require('./Application').default),
    store,
    router: vueRouter
});
