<?php

use MyPlugin\Framework\Foundation\Application;
use MyPlugin\Hooks\Handlers\Activation;
use MyPlugin\Hooks\Handlers\Deactivation;

return function ($file) {
    register_activation_hook($file, function () {
        (new Activation)->handle();
    });

    register_deactivation_hook($file, function () {
        (new Deactivation)->handle();
    });

    add_action('plugins_loaded', function () use ($file) {
        do_action('myPlugin_loaded', new Application($file));
    });
};
