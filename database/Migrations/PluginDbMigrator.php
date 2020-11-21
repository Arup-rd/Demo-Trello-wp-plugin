<?php

namespace MyPlugin\Database\Migrations;

class PluginDbMigrator
{
    const TABLENAME = 'Board';
    
    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $table = $wpdb->prefix . self::TABLENAME;

        $indexPrefix = $wpdb->prefix . '_index_';

        if ($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) {
            $sql = "CREATE TABLE $table (
                `id` BIGINT(20) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
                `table_id` BIGINT(20) UNSIGNED NULL,
                `table_name` varchar(60) NOT NULL,
                `columnName` varchar(60) NOT NULL,
                `task` varchar(60) NOT NULL,
                `description` varchar(60) NOT NULL,
                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL,
                INDEX `{$indexPrefix}_table_id_idx` (`table_id` ASC)
            ) $charsetCollate;";

            dbDelta($sql);
        } else {
            self::alterTable($table, $indexPrefix);
        }
    }

    public static function alterTable($table, $indexPrefix)
    {
        global $wpdb;
        $sql =  "ALTER TABLE $table
        MODIFY COLUMN chart_type VARCHAR(20) NOT NULL,
        MODIFY COLUMN options TEXT NOT NULL";
        $wpdb->query($sql);
    }
}
