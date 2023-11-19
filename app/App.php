<?php

namespace StudioVisual\Twitter;

use StudioVisual\Twitter\Controllers\Autotwitter_Admin;
use StudioVisual\Twitter\Controllers\Publish;
use StudioVisual\Twitter\Models\Logs;

Class Autotwitter_App {
    // Variables
    static $name   = 'Auto Post for Twitter';
    static $prefix = 'sv_';

    public function __construct() {
        // Instance dependences
        new Autotwitter_Admin;
        new Publish;
    }

    /**
    * Activate plugin
    * @return void
    */
    public static function activate(): void {
        update_option('rewrite_rules', '');

        // Create Table Logs
        $logs = new Logs;
        $logs->createTable();
    }

    /**
    * Deactivate plugin
    * @return void
    */
    public static function deactivate(): void {
        flush_rewrite_rules();
    }

    /**
    * Uninstall plugin
    * @return void
    */
    public static function uninstall(): void {
        // Remove options
        delete_option(self::getSlug('isactive'));
        delete_option(self::getSlug('consumerkey'));
        delete_option(self::getSlug('consumersecret'));
        delete_option(self::getSlug('tokenkey'));
        delete_option(self::getSlug('tokensecret'));
        delete_option(self::getSlug('posttypes'));
        delete_option(self::getSlug('categories'));
        delete_option(self::getSlug('dbversion'));

        // Drop table
        $logs = new Logs;
        $logs->drop();
    }

    /**
    * Concat Key with prefix and name
    * @return string
    */
    public static function getKey(string $key = ''): string {
        return str_replace('_', '-', $key . self::$prefix . sanitize_title(self::$name));
    }

    /**
    * Format key to save options
    * @return string
    */
    public static function getSlug(string $key = ''): string {
        return !empty($key) ? strtolower(self::$prefix . str_replace('-', '_', sanitize_title(self::$name)) . '_' . $key) : strtolower(self::$prefix . str_replace('-', '_', sanitize_title(self::$name)));
    }
}
