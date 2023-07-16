<?php

namespace StudioVisual\Twitter;

use StudioVisual\Twitter\Controllers\Admin;
use StudioVisual\Twitter\Controllers\Publish;

Class App {
    // Plugin name
    static $name       = 'Twitter API';
    static $prefix     = 'sv_';
    static $textDomain = 'sv-twitter';

    public function __construct() {
        // Instance dependences
        new Admin;
        new Publish;
    }
    
    /**
    * Activate plugin 
    * @return void
    */
    public static function activate(): void {
        update_option('rewrite_rules', '');
    }

    /**
    * Deactivate plugin 
    * @return void
    */
    public static function deactivate(): void {
        flush_rewrite_rules();
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

    /**
    * Load text domain 
    */
    public static function loadTextDomain(string $dir) {
        load_plugin_textdomain(self::$textDomain, false, $dir . '/languages/');
    }
}