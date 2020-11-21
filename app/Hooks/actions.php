<?php

/**
 * All registered action's handlers should be in app\Hooks\Handlers,
 * addAction is similar to add_action and addCustomAction is just a
 * wrapper over add_action which will add a prefix to the hook name
 * using the plugin slug to make it unique in all wordpress plugins,
 * ex: $app->addCustomAction('foo', ['FooHandler', 'handleFoo']) is
 * equivalent to add_action('slug-foo', ['FooHandler', 'handleFoo']).
 */

/**
 * @var $app MyPlugin\Framework\Foundation\Application
 */

$app->addCustomAction('handle_exception', 'Exception@handle');

$app->addAction('admin_menu', 'Menu@add');
$app->addAction('init', 'TinyMce@addChartsToEditor');
$app->addAction('init', 'TinyMce@gutenBlockLoad');

// disabled update-nag
add_action('admin_init', function () {
    $disablePages = [
        'my-plugin',
    ];

    if (isset($_GET['page']) && in_array($_GET['page'], $disablePages)) {
        remove_all_actions('admin_notices');
    }
});

add_action('init', function () {
    (new \MyPlugin\Http\Controllers\ShortCodeController())->myPluginShortCode();
});
