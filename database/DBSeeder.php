<?php

namespace MyPlugin\Database;

class DBSeeder
{
    public static function run()
    {
        $config = require(__DIR__ . '/../config/database.php');

        $namespace = $config['namespace'];

        foreach (glob(__DIR__ . '/Seeders/*.php') as $file) {
            $class = $namespace . '\\Seeders\\' . basename($file, '.php');
//            $class::seed();
        }
    }
}
