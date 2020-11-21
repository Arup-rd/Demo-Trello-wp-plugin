import Vue from './elements';
import Rest from './Rest';
import Router from 'vue-router';

import {
    applyFilters,
    addFilter,
    addAction,
    doAction,
    removeAllActions
} from '@wordpress/hooks';

window.Event = new Vue();
const moment = require('moment');
require('moment/locale/en-gb');
moment.locale('en-gb');
export default class MyPlugin {
    constructor() {
        this.Router = Router;
        this.doAction = doAction;
        this.addFilter = addFilter;
        this.addAction = addAction;
        this.applyFilters = applyFilters;
        this.removeAllActions = removeAllActions;

        this.$rest = Rest;
        this.appVars = window.MyPluginAdmin;
        this.Vue = this.extendVueConstructor();
    }

    extendVueConstructor() {
        const self = this;

        Vue.mixin({
            data() {
                return {
                    appVars: self.appVars
                };
            },
            methods: {
                addFilter,
                applyFilters,
                doAction,
                addAction,
                removeAllActions,
                dateFormat: self.dateFormat,
                ucFirst: self.ucFirst,
                ucWords: self.ucWords,
                slugify: self.slugify,
                moment: moment,
                $t(str) {
                    return str;
                }
            }
        });

        Vue.filter('dateFormat', self.dateFormat);
        Vue.filter('ucFirst', self.ucFirst);
        Vue.filter('ucWords', self.ucWords);

        Vue.use(this.Router);

        return Vue;
    }

    registerBlock(blockLocation, blockName, block) {
        this.addFilter(blockLocation, this.appVars.slug, function(components) {
            components[blockName] = block;
            return components;
        });
    }

    registerTopMenu(title, route) {
        if (!title || !route.name || !route.path || !route.component) {
            return;
        }

        this.addFilter('my_plugin_top_menus', this.appVars.slug, function(
            menus
        ) {
            menus = menus.filter((m) => m.route !== route.name);
            menus.push({
                route: route.name,
                title: title
            });
            return menus;
        });

        this.addFilter('my_plugin_global_routes', this.appVars.slug, function(
            routes
        ) {
            routes = routes.filter((r) => r.name !== route.name);
            routes.push(route);
            return routes;
        });
    }

    $get(url, options = {}) {
        return window.myPlugin.$rest.get(url, options);
    }

    $post(url, options = {}) {
        return window.myPlugin.$rest.post(url, options);
    }

    $delete(url, options = {}) {
        return window.myPlugin.$rest.delete(url, options);
    }

    $put(url, options = {}) {
        return window.myPlugin.$rest.put(url, options);
    }

    $patch(url, options = {}) {
        return window.myPlugin.$rest.patch(url, options);
    }

    dateFormat(date, format) {
        const dateString = date === undefined ? null : date;
        const dateObj = moment(dateString);
        return dateObj.isValid() ? dateObj.format(format) : null;
    }

    ucFirst(text) {
        return text[0].toUpperCase() + text.slice(1).toLowerCase();
    }

    ucWords(text) {
        return (text + '').replace(/^(.)|\s+(.)/g, function($1) {
            return $1.toUpperCase();
        });
    }

    slugify(text) {
        return text
            .toString()
            .toLowerCase()
            .replace(/\s+/g, '-') // Replace spaces with -
            .replace(/[^\w\\-]+/g, '') // Remove all non-word chars
            .replace(/\\-\\-+/g, '-') // Replace multiple - with single -
            .replace(/^-+/, '') // Trim - from start of text
            .replace(/-+$/, ''); // Trim - from end of text
    }
}
