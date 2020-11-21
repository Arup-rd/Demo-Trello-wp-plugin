<?php

namespace MyPlugin\Framework\Foundation;

class App
{
    protected static $instance = null;

    public static function setInstance($app)
    {
        static::$instance = $app;
    }

    public static function getInstance($module = null)
    {
        if ($module) {
            return static::$instance[$module];
        }

        return static::$instance;
    }

    public static function __callStatic($method, $params)
    {
        return static::getInstance($method);
    }
}
