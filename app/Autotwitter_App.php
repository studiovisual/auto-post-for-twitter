<?php

namespace StudioVisual\Twitter;

use StudioVisual\Twitter\Controllers\Autotwitter_Admin;
use StudioVisual\Twitter\Controllers\Autotwitter_Publish;
use StudioVisual\Twitter\Models\Autotwitter_Logs;

Class Autotwitter_App {
    // Variables
    static $name   = 'Auto Post for Twitter';
    static $prefix = 'sv_';

    public function __construct() {
        // Instance dependences
        new Autotwitter_Admin;
        new Autotwitter_Publish;
    }

    /**
    * Activate plugin
    * @return void
    */
    public static function autotwitter_activate(): void {
        update_option('rewrite_rules', '');

        // Create Table Logs
        $logs = new Autotwitter_Logs;
        $logs->autotwitter_createTable();
    }

    /**
    * Deactivate plugin
    * @return void
    */
    public static function autotwitter_deactivate(): void {
        flush_rewrite_rules();
    }

    /**
    * Uninstall plugin
    * @return void
    */
    public static function autotwitter_uninstall(): void {
        // Remove options
        delete_option(self::autotwitter_getSlug('isactive'));
        delete_option(self::autotwitter_getSlug('consumerkey'));
        delete_option(self::autotwitter_getSlug('consumersecret'));
        delete_option(self::autotwitter_getSlug('tokenkey'));
        delete_option(self::autotwitter_getSlug('tokensecret'));
        delete_option(self::autotwitter_getSlug('posttypes'));
        delete_option(self::autotwitter_getSlug('categories'));
        delete_option(self::autotwitter_getSlug('dbversion'));

        // Drop table
        $logs = new Autotwitter_Logs;
        $logs->autotwitter_drop();
    }

    /**
    * Concat Key with prefix and name
    * @return string
    */
    public static function autotwitter_getKey(string $key = ''): string {
        return str_replace('_', '-', $key . self::$prefix . sanitize_title(self::$name));
    }

    /**
    * Format key to save options
    * @return string
    */
    public static function autotwitter_getSlug(string $key = ''): string {
        return !empty($key) ? strtolower(self::$prefix . str_replace('-', '_', sanitize_title(self::$name)) . '_' . $key) : strtolower(self::$prefix . str_replace('-', '_', sanitize_title(self::$name)));
    }
}
