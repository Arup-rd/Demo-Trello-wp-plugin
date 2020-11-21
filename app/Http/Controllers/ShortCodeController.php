<?php

namespace MyPlugin\Http\Controllers;

use MyPlugin\App;
use MyPlugin\Framework\Support\Arr;

class ShortCodeController extends Controller
{

    public function makeShortCode($atts = [])
    {
        return __("My Plugin shortcode content!", 'my_plugin');
    }

    public function myPluginShortCode()
    {
        add_shortcode('my_plugin', [$this, 'makeShortCode']);
    }

    public function renderView()
    {
      //
    }

    private static function addInlineVars()
    {
       //
    }

    private static function loadAssets()
    {
       //
    }
}
