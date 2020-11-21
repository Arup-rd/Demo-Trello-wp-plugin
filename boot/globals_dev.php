<?php

if (!function_exists('myPluginEqL')) {
    function myPluginEqL()
    {
        defined('SAVEQUERIES') || define('SAVEQUERIES', true);
    }
}

if (!function_exists('myPluginGql')) {
    function myPluginGql()
    {
        $result = [];

        foreach ((array)$GLOBALS['wpdb']->queries as $key => $query) {
            $result[++$key] = array_combine([
                'query', 'execution_time'
            ], array_slice($query, 0, 2));
        }

        return $result;
    }
}
