<?php

namespace StudioVisual\Twitter;

use StudioVisual\Twitter\Controllers\Admin;

Class App {
    // Plugin name
    static $name    = 'Twitter API';
    static $prefix  = 'sv_';

    public function __construct() {
        // Instance Options Page
        new Admin;
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
    * Get options
    * @return array 
    */
    public function getOptions(): array {
        return !empty($options = get_option('policy-settings')) ? $options : [];
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