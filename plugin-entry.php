<?php

/**
Plugin Name:    Board
Description:    A Boilerplate Plugin for WordPress
Version:        1.0.0
Author:         Arup-rd
Author URI:     https://www.Arup-rd.com
Plugin URI:     #
License:        GPL-2.0+
Text Domain:    Board
Domain Path:    /language
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('MY_PLUGIN_URL', plugin_dir_url(__FILE__));

$my_plugin_info = get_file_data(__FILE__, array('Version' => 'Version'), false);
defined('MY_PLUGIN_VERSION') or define('MY_PLUGIN_VERSION', $my_plugin_info['Version']);

require __DIR__.'/vendor/autoload.php';

call_user_func(function ($bootstrap) {
    $bootstrap(__FILE__);
}, require(__DIR__.'/boot/app.php'));
