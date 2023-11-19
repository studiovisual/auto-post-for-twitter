<?php

namespace StudioVisual\Twitter\Controllers;

use StudioVisual\Twitter\Autotwitter_App;
use StudioVisual\Twitter\Models\Autotwitter_Logs;
use StudioVisual\Twitter\Controllers\Autotwitter_Admin;
use StudioVisual\Twitter\Controllers\ApiTwitter;

Class Autotwitter_Publish {
    protected $logs;
    protected $options;

    public function __construct() {
        // Instance dependences
        $this->logs    = new Autotwitter_Logs;
        $this->twitter = new Autotwitter_ApiTwitter;

        // Add hook only if options is active
        if(Autotwitter_Admin::autotwitter_isActive()) {
            add_action('future_to_publish', [$this, 'publishFuture']);
            add_action('save_post',         [$this, 'publishPost'], 10, 2);
        }
    }

    /**
    * Future to Publish Trigger
    * @param WP_Post $post
    */
    public function publishFuture(\WP_Post $post): void {
        update_post_meta($post->ID, 'future_post_trigger', date('Y-m-d H:i:s'));
        $this->triggerTweet($post);
    }

    /**
    *  When post pass to status Publish Triggers autopost
    * @param int $post
    * @param WP_Post $post
    * @return void
    */
    public function publishPost(int $post_id, \WP_Post $post) {
        // Check if it's not triggered by gutemberg
        if(strpos($_SERVER['REQUEST_URI'], 'post.php') === false || !$_SERVER['REQUEST_METHOD'] === 'POST') {
            return;
        }

        // Only post status publish
        if($post->post_status !== 'publish') {
            return;
        }

        $this->triggerTweet($post);
    }

    /**
    * Abstract function to trigger Tweet
    * @param WP_Post $post
    * @return void
    */
    public function triggerTweet(\WP_Post $post): void {
        // Checks for validations
        if(!$this->canPublish($post->ID, $post->post_type)) {
            return;
        }

        // Setup variables
        $slug      = Autotwitter_App::autotwitter_getSlug();
        $active    = $slug . '_active';
        $newTitle  = $slug . '_title';
        $auto_post = !empty($_POST[$active]) ? $_POST[$active] : get_post_meta($post->ID, $active, true);

        // Check if auto post is ready to publish
        if ($auto_post == 'yes' || $auto_post == '') {
            // Format Message
            $title   = !empty($_POST[$newTitle]) ? $_POST[$newTitle] : get_post_meta($post->ID, $newTitle, true);
            $title   = !empty($title) ? $title : strip_tags(get_the_title($post->ID));
            $link    = get_permalink($post->ID);
            $message = $title . ' ' . $link;

            // Create Tweet
            $publish = $this->twitter->createTweet($post->ID, $message);

            if(!empty($publish)) {
                // Setup Message Log
                $log = '[' . $post->ID . '] ' . $title . ' | Response API: [' . $publish['code'] . '] - ' . $publish['body'];

                if($publish['code'] === 201 || $publish['code'] === 200) {
                    // Success
                    $this->logs->add($post->ID, __('sucesso', 'sv-twitter'), $log);

                    // update meta field to not publish on twitter again
                    update_post_meta($post->ID, 'twitter_published', true);

                    // Update auto post
                    $check = update_post_meta($post->ID, $active, 'no');
                    $_POST[$active] = 'no';

                    return;
                }

                // Log error on API
                $this->logs->add($post->ID, __('falhou', 'sv-twitter'), $log);
            }
        }
    }

    /**
    * Checks any settings blocks for publish on tweet
    * @param int $post_id
    * @param string $post_type
    * @return bool
    */
    public function canPublish(int $post_id, string $post_type): bool {

        if(empty($post_id)) {
            return false;
        }

        // Check if Tweet is already published
        if(!empty(get_post_meta($post_id, 'twitter_published'))) {
            return false;
        }

        // Check if Has any categories checked on options and stops publish on twitter
        $post_categories    = wp_get_post_categories($post_id);
        $twitter_categories = !empty(Autotwitter_Admin::autotwitter_getSettings()['categories']) ? Autotwitter_Admin::autotwitter_getSettings()['categories'] : [];
        $has_categories     = array_intersect($post_categories, $twitter_categories);

        // If there's a blocked categorie skip tweet
        if(!empty($has_categories)) {
            // Variable Categories
            $cats = [];

            // Looks for category name
            foreach($has_categories as $cat) {
                $cats[] = get_the_category_by_ID($cat);
            }

            $message = __('Não enviado por estar em categoria bloqueada | Categoria(s):', 'sv-twitter');
            $log = '[' . $post_id . '] ' . get_the_title($post_id) . ' | ' . $message . ' ' . implode(", ", $cats);
            $this->logs->add($post_id, 'failed', $log);
            return false;
        }

        // get post type settings and current post type for post
        $settingsPostTypes = !empty($pt = Autotwitter_Admin::autotwitter_getSettings()['postTypes']) ? $pt : [];

        // Checks if post type not in settings
        if(!in_array($post_type, $settingsPostTypes)) {
            $message = __('Não enviado por estar em tipo de post bloqueado | Tipo de post:', 'sv-twitter');
            $log = '[' . $post_id . '] ' . get_the_title($post_id) . ' | ' . $message . ' ' . $post_type;
            $this->logs->add($post_id, 'failed', $log);
            return false;
        }

        return true;
    }

}
