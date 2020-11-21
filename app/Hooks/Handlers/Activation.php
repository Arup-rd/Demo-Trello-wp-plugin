<?php

namespace MyPlugin\Hooks\Handlers;

use MyPlugin\Database\DBSeeder;
use MyPlugin\Database\DBMigrator;

class Activation
{
    public function handle($network_wide = false)
    {
        DBMigrator::run($network_wide);
        DBSeeder::run();
        update_option('_my_plugin_installed_version', MY_PLUGIN_VERSION, 'no');
    }
}
