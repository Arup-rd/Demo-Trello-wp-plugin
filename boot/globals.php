<?php

/**
 ***** DO NOT CALL ANY FUNCTIONS DIRECTLY FROM THIS FILE ******
 *
 * This file will be loaded even before the framework is loaded
 * so the $app is not available here, only declare functions here.
 */

is_readable(__DIR__ . '/globals_dev.php') && include 'globals_dev.php';

if (!function_exists('myPlugin')) {
    function myPlugin($module = null)
    {
        return MyPlugin\App::getInstance($module);
    }
}

if (!function_exists('myPluginTimestamp')) {
    function myPluginTimestamp()
    {
        return date('Y-m-d H:i:s');
    }
}

if (!function_exists('myPluginDate')) {
    function myPluginDate()
    {
        return date('Y-m-d');
    }
}

if (!function_exists('myPluginFormatDate')) {
    function myPluginFormatDate($date)
    {
        return date('d M, Y', strtotime($date));
    }
}

if (!function_exists('myPluginGravatar')) {
    /**
     * Get the gravatar from an email.
     *
     * @param string $email
     * @return string
     */
    function myPluginGravatar($email)
    {
        $hash = md5(strtolower(trim($email)));

        return "https://www.gravatar.com/avatar/${hash}?s=128";
    }
}
