<?php
/**
 * Class Publish Auto Twitter
 * Publish twitter plugin
 * php version 8.1
 *
 * @category Class
 * @package  Autotwitter_Publish
 * @author   Studio Visual <atendimento@studiovisual.com.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.studiovisual.com.br
 */

namespace StudioVisual\Twitter\Controllers;

use StudioVisual\Twitter\Autotwitter_App;
use StudioVisual\Twitter\Models\Autotwitter_Logs;
use StudioVisual\Twitter\Controllers\Autotwitter_Admin;
use StudioVisual\Twitter\Controllers\Autotwitter_ApiTwitter;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class Publish Auto Twitter
 *
 * @category Class
 * @package  Autotwitter_Publish
 * @author   Studio Visual <atendimento@studiovisual.com.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.studiovisual.com.br
 */
class Autotwitter_Publish
{

    protected $logs;
    protected $options;

    /**
     * Construct
     */
    public function __construct()
    {
        // Instance dependences
        $this->logs    = new Autotwitter_Logs();
        $this->twitter = new Autotwitter_ApiTwitter();

        // Add hook only if options is active
        if (Autotwitter_Admin::autotwitter_isActive() ) {
            add_action('future_to_publish', array( $this, 'autotwitter_publishFuture' )); //phpcs:ignore
            add_action('save_post',         [$this, 'autotwitter_publishPost'], 10, 2); //phpcs:ignore
        }
    }

    /**
     * Future to Publish Trigger
     *
     * @param WP_Post $post post
     *
     * @return void
     */
    public function autotwitter_publishFuture(\WP_Post $post): void { //phpcs:ignore
        update_post_meta($post->ID, 'future_post_trigger', date('Y-m-d H:i:s'));
        $this->autotwitter_triggerTweet($post);
    }

    /**
     *  When post pass to status Publish Triggers autopost
     *
     * @param int     $post_id post_id
     * @param WP_Post $post    post
     *
     * @return void
     */
    public function autotwitter_publishPost(int $post_id, \WP_Post $post) { //phpcs:ignore
        // Check if it's not triggered by gutemberg
        if (strpos($_SERVER['REQUEST_URI'], 'post.php') === false
            || ! $_SERVER['REQUEST_METHOD'] === 'POST'
        ) {
            return;
        }

        // Only post status publish
        if ($post->post_status !== 'publish' ) {
            return;
        }

        $this->autotwitter_triggerTweet($post);
    }

    /**
     * Abstract function to trigger Tweet
     *
     * @param WP_Post $post object post
     *
     * @return void
     */
    public function autotwitter_triggerTweet(\WP_Post $post): void { //phpcs:ignore
        // Checks for validations
        if (! $this->autotwitter_canPublish($post->ID, $post->post_type) ) {
            return;
        }

        // Setup variables
        $slug       = Autotwitter_App::autotwitter_getSlug();
        $meta_nonce = sanitize_text_field($_POST['sv_auto_post_for_twitter_nonce_field']); //phpcs:ignore

        // Check if nonce is OK
        if (! wp_verify_nonce($meta_nonce, $slug . '_nonce_action') ) {
            return;
        }

        // Variables
        $active    = $slug . '_active';
        $newTitle  = $slug . '_title';
        $auto_post  = !empty($_POST[$active]) && wp_verify_nonce($meta_nonce, $slug . '_nonce_action') ? sanitize_text_field($_POST[$active]) : get_post_meta($post->ID, $active, true); //phpcs:ignore

        // Check if auto post is ready to publish
        if ($auto_post == 'yes' || $auto_post == '' ) {
            // Format Message
            $title   = !empty($_POST[$newTitle]) && wp_verify_nonce($meta_nonce, $slug . '_nonce_action') ? sanitize_text_field($_POST[$newTitle]) : get_post_meta($post->ID, $newTitle, true); //phpcs:ignore
            $title   = !empty($title) ? $title : strip_tags(get_the_title($post->ID)); //phpcs:ignore
            $link    = get_permalink($post->ID);
            $message = $title . ' ' . $link;

            // Create Tweet
            $publish = $this->twitter->autotwitter_createTweet($post->ID, $message);

            if (! empty($publish) ) {
                // Setup Message Log
                $log = '[' . $post->ID . '] ' . $title . ' | Response API: [' . $publish['code'] . '] - ' . $publish['body']; //phpcs:ignore

                if ($publish['code'] === 201 || $publish['code'] === 200 ) {
                    // Success
                    $this->logs->autotwitter_add($post->ID, __('success', 'sv-twitter'), $log); //phpcs:ignore

                    // update meta field to not publish on twitter again
                    update_post_meta($post->ID, 'twitter_published', true);

                    // Update auto post
                    $check            = update_post_meta($post->ID, $active, 'no');
                    $_POST[ $active ] = 'no';

                    return;
                }

                // Log error on API
                $this->logs->autotwitter_add($post->ID, __('failed', 'sv-twitter'), $log); //phpcs:ignore
            }
        }
    }

    /**
     * Checks any settings blocks for publish on tweet
     *
     * @param int    $post_id   post_id
     * @param string $post_type post_type
     *
     * @return bool
     */
    public function autotwitter_canPublish(int $post_id, string $post_type): bool { //phpcs:ignore

        if (empty($post_id) ) {
            return false;
        }

        // Check if Tweet is already published
        if (! empty(get_post_meta($post_id, 'twitter_published')) ) {
            return false;
        }

        // Check if Has any categories checked on options and stops publish
        $post_categories    = wp_get_post_categories($post_id);
        $twitter_categories = !empty(Autotwitter_Admin::autotwitter_getSettings()['categories']) ? Autotwitter_Admin::autotwitter_getSettings()['categories'] : []; //phpcs:ignore
        $has_categories     = array_intersect($post_categories, $twitter_categories); //phpcs:ignore

        // If there's a blocked categorie skip tweet
        if (! empty($has_categories) ) {
            // Variable Categories
            $cats = array();

            // Looks for category name
            foreach ( $has_categories as $cat ) {
                $cats[] = get_the_category_by_ID($cat);
            }

            $message = __('Not sent cause it\'s in a blocked category | Categories:', 'sv-twitter'); //phpcs:ignore
            $log = '[' . $post_id . '] ' . get_the_title($post_id) . ' | ' . $message . ' ' . implode(", ", $cats); //phpcs:ignore
            $this->logs->autotwitter_add($post_id, __('failed', 'sv-twitter'), $log);
            return false;
        }

        // get post type settings and current post type for post
        $settingsPostTypes = !empty($pt = Autotwitter_Admin::autotwitter_getSettings()['postTypes']) ? $pt : []; //phpcs:ignore

        // Checks if post type not in settings
        if (! in_array($post_type, $settingsPostTypes) ) {
            $message = __('Not sent cause it\'s in a blocked post Type | Post Type:', 'sv-twitter'); //phpcs:ignore
            $log = '[' . $post_id . '] ' . get_the_title($post_id) . ' | ' . $message . ' ' . $post_type; //phpcs:ignore
            $this->logs->autotwitter_add($post_id, __('failed', 'sv-twitter'), $log);
            return false;
        }

        return true;
    }
}
