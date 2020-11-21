<?php

namespace MyPlugin\Modules;

class NinjaTableModule
{
    public function getTableList()
    {
        // your code here
        return apply_filters('my_plugin_ntm_table_lists', $list);
    }
}
