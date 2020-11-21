<?php

namespace MyPlugin\Hooks\Handlers;

use MyPlugin\App;
use MyPlugin\Hooks\Handlers\Activation;

class Menu
{
    public function add()
    {
        $capability = apply_filters('my_plugin_menu_capability', 'manage_options');
        add_menu_page(
            __('My Plugin', 'my-plugin'),
            'My Plugin',
            $capability,
            'my-plugin',
            array($this, 'renderApp'),
            $this->getIcon(),
            25
        );
        global $submenu;

        $submenu['my-plugin']['my-plugin'] = array(
            __('My Plugin', 'my-plugin'),
            $capability,
            'admin.php?page=my-plugin#/my-plugin',
        );

        $submenu['my-plugin']['help'] = array(
            __('Get help', 'my-plugin'),
            $capability,
            'admin.php?page=my-plugin#/help',
        );
    }

    public function renderApp()
    {
        $app = App::getInstance();
        $this->enqueueAssets();
        $app->view->render('admin.menu');
        $this->checkForDbMigration();
    }

    public function checkForDbMigration()
    {
        if (!get_site_option('_my_plugin_installed_version')) {
            (new Activation)->handle();
        }
    }

    public function enqueueAssets()
    {
        $app = App::getInstance();

        $assets = $app['url.assets'];

        wp_enqueue_script(
            'my_plugin_admin_app_boot',
            $assets . '/admin/js/boot.js',
            array('jquery')
        );

        wp_enqueue_style(
            'my_plugin_admin_app',
            $assets . '/admin/css/plugin-main-admin.css'
        );

        wp_localize_script('my_plugin_admin_app_boot', 'MyPluginAdmin', array(
            'slug'       => $slug = $app->config->get('app.slug'),
            'nonce'      => wp_create_nonce($slug),
            'rest'       => $this->getRestInfo($app),
            'assets_url' => MY_PLUGIN_URL . 'assets/',
        ));

        do_action('my_plugin_loading_app');

        wp_enqueue_script(
            'my_plugin_admin_app',
            $assets . '/admin/js/plugin-main-admin-app.js',
            array('my_plugin_admin_app_boot'),
            '1.0',
            true
        );

        wp_enqueue_script(
            'my_plugin_admin_app_vendor',
            $assets . '/admin/js/vendor.js',
            array('my_plugin_admin_app_boot'),
            '1.0',
            true
        );
    }

    public function getIcon()
    {
        return 'dashicons-heart';
    }

    protected function getRestInfo($app)
    {
        $ns = $app->config->get('app.rest_namespace');
        $v = $app->config->get('app.rest_version');

        return apply_filters('my_plugin_rest_info', [
                'base_url'  => esc_url_raw(rest_url()),
                'url'       => rest_url($ns . '/' . $v),
                'nonce'     => wp_create_nonce('wp_rest'),
                'namespace' => $ns,
                'version'   => $v,
        ]);
    }
}
