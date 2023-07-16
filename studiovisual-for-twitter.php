<?php 

/***************************************************************************
Plugin Name:  Studio Visual for Twitter
Plugin URI:   https://github.com/studiovisual/studiovisual-for-twitter
Description:  Publique automaticamente no Twitter ao publicar um novo post no WordPress.
Version:      1.0.0
Author:       Studio visual
Author URI:   https://www.studiovisual.com.br/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  sv-twitter
Domain Path:  /languages
**************************************************************************/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require __DIR__.'/vendor/autoload.php';


if(class_exists('StudioVisual\Twitter\App')) {
    // Define Constants
    define( 'STUDIO_TWITTER_VERSION', '1.0.0' );
    define( 'STUDIO_TWITTER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
    define( 'STUDIO_TWITTER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

    // Register Hooks
    register_activation_hook( __FILE__,   ['StudioVisual\Twitter\App', 'activate'] );
    register_deactivation_hook( __FILE__, ['StudioVisual\Twitter\App', 'deactivate'] );

    // Instance
    $app = new StudioVisual\Twitter\App;

    // Add location support
    add_action('plugins_loaded', function() {
        load_plugin_textdomain( 'sv-twitter', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    });
}