<?php
/**
 * Main
 * Main file plugin
 * php version 8.1
 *
 * @category File
 * @package  Main_Auto_Post_For_Twitter
 * @author   Studio Visual <atendimento@studiovisual.com.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.studiovisual.com.br
 */

/***************************************************************************
Plugin Name:  Auto Post for Twitter
Plugin URI:   https://github.com/studiovisual/auto-post-for-twitter
Description:  Automatically post on Twitter when publishing a new post on WordPress.
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

require __DIR__ . '/vendor/autoload.php';


if ( class_exists( 'StudioVisual\Twitter\Autotwitter_App' ) ) {
	// Define Constants
	define( 'STUDIO_TWITTER_VERSION', '1.0.0' );
	define( 'STUDIO_TWITTER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	define( 'STUDIO_TWITTER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

	// Register Hooks
    register_activation_hook(__FILE__,   ['StudioVisual\Twitter\Autotwitter_App', 'autotwitter_activate']); //phpcs:ignore
    register_deactivation_hook(__FILE__, ['StudioVisual\Twitter\Autotwitter_App', 'autotwitter_deactivate']); //phpcs:ignore
    register_uninstall_hook(__FILE__,    ['StudioVisual\Twitter\Autotwitter_App', 'autotwitter_uninstall']); //phpcs:ignore

	// Instance
	$app = new StudioVisual\Twitter\Autotwitter_App();

	// Add location support
	add_action(
		'plugins_loaded',
		function () {
            load_plugin_textdomain('sv-twitter', false, dirname(plugin_basename(__FILE__)) . '/languages'); //phpcs:ignore
		}
	);
}
