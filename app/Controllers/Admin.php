<?php

namespace StudioVisual\Twitter\Controllers;

use StudioVisual\Twitter\Autotwitter_App;
use StudioVisual\Twitter\Models\Autotwitter_Logs;

Class Autotwitter_Admin {
	public function __construct() {
        // Hook to create admin menu
        add_action('admin_menu', [$this, 'autotwitter_optionsPage']);

        // Actions for set fields
        add_action('admin_init', [$this, 'autotwitter_settingFields']);

        // Actions for notices
        add_action('admin_notices', [$this, 'autotwitter_notices']);

        // Enqueue Scripts
        add_action('admin_enqueue_scripts', [$this, 'autotwitter_enqueueAssets'], 100);

        // Add Meta boxes to Posts
        add_action('add_meta_boxes', [$this, 'autotwitter_addMetaBoxes']);

        // Save Meta Boxes
        add_action('save_post', [$this, 'autotwitter_saveMetaBox'], 11);

        // Instance Class
        $this->logs = new Autotwitter_Logs;
    }

    /**
    * Enqueue Scripts/Styles
    *
    * @return void
    */
    function autotwitter_enqueueAssets() {
        wp_enqueue_style('starter-plugin-style', STUDIO_TWITTER_PLUGIN_URL . '/../assets/css/style.css', false, null);
        wp_enqueue_script('starter-plugin-script', STUDIO_TWITTER_PLUGIN_URL . '/assets/js/script.js', array('jquery'));
    }

    /**
    * Create options page
    *
    * @return void
    */
    public function autotwitter_optionsPage(): void {
        add_menu_page(
            Autotwitter_App::$name,
            Autotwitter_App::$name,
            'manage_options',
            Autotwitter_App::autotwitter_getKey(),
            [$this, 'autotwitter_settingsPage'],
            'dashicons-twitter',
            99.59,
        );

        add_submenu_page(
            Autotwitter_App::autotwitter_getKey(),
            __('Documentação', 'sv-twitter'),
            __('Documentação', 'sv-twitter'),
            'manage_options',
            Autotwitter_App::autotwitter_getKey('docs_'),
            [$this, 'auto_twitter_docs'],
            2,
        );

        add_submenu_page(
            Autotwitter_App::autotwitter_getKey(),
            __('Logs', 'sv-twitter'),
            __('Logs', 'sv-twitter'),
            'manage_options',
            Autotwitter_App::autotwitter_getKey('logs_'),
            [$this, 'autotwitter_logs'],
            2,
        );
    }

    /**
    * Settings Page
    * @return void
    */
    public function autotwitter_settingsPage(): void {
        $validation = Autotwitter_App::autotwitter_getSlug('errors');
        $group      = Autotwitter_App::autotwitter_getSlug('settings');
        $slug       = Autotwitter_App::autotwitter_getSlug();
        $docs       = Autotwitter_App::autotwitter_getKey('docs_');

        require_once STUDIO_TWITTER_PLUGIN_DIR . 'views/settings.php';
    }

    /**
    * Docs Page
    * @return void
    */
    public function auto_twitter_docs(): void {
        $settingsPage = Autotwitter_App::autotwitter_getKey();
        require_once STUDIO_TWITTER_PLUGIN_DIR . 'views/docs.php';
    }

    /**
    * Logs Page
    * @return void
    */
    public function autotwitter_logs(): void {
        // Clear the logs
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // clear logs
            $this->logs->truncate();
        }

        // Get last 30 logs
        $logs     = $this->logs->get();
        $current  = Autotwitter_App::autotwitter_getKey('logs_');

        require_once STUDIO_TWITTER_PLUGIN_DIR . 'views/logs.php';
    }

    /**
    * Settings options fields
    * @return void
    */
    function autotwitter_settingFields(): void {

        // Variables for slugs and group
        $group = Autotwitter_App::autotwitter_getSlug('settings');
        $slug = Autotwitter_App::autotwitter_getSlug();

        // Create Section
        add_settings_section(
            Autotwitter_App::autotwitter_getSlug('section_id'),
            '', // title (optional)
            '', // callback (optional)
            $slug,
        );

        // Is Active
        register_setting($group, Autotwitter_App::autotwitter_getSlug('isActive'));
        add_settings_field(
            Autotwitter_App::autotwitter_getSlug('isActive'),
            __('Ativar', 'sv-twitter'),
            [$this, 'autotwitter_fieldBoolean'],
            $slug,
            Autotwitter_App::autotwitter_getSlug('section_id'),
            [
                'label_for' => Autotwitter_App::autotwitter_getSlug('isActive'),
                'name'      => Autotwitter_App::autotwitter_getSlug('isActive'),
                'class'     => 'input-field',
            ]
        );

        // Consumer Key
        $field_name = __('Consumer Key', 'sv-twitter');
        register_setting($group, Autotwitter_App::autotwitter_getSlug('consumerKey'), function ($value) use ($field_name) {
            return $this->autotwitter_validationText($value, $field_name);
        });

        add_settings_field(
            Autotwitter_App::autotwitter_getSlug('consumerKey'),
            __('Consumer Key', 'sv-twitter'),
            [$this, 'autotwitter_fieldText'],
            $slug,
            Autotwitter_App::autotwitter_getSlug('section_id'),
            [
                'label_for' => Autotwitter_App::autotwitter_getSlug('consumerKey'),
                'name'      => Autotwitter_App::autotwitter_getSlug('consumerKey'),
                'class'     => 'input-field',
            ]
        );

        // Consumer Secret
        $field_name = __('Consumer Secret', 'sv-twitter');
        register_setting($group, Autotwitter_App::autotwitter_getSlug('consumerSecret'), function ($value) use ($field_name) {
            return $this->autotwitter_validationText($value, $field_name);
        });

        add_settings_field(
            Autotwitter_App::autotwitter_getSlug('consumerSecret'),
            __('Consumer Secret', 'sv-twitter'),
            [$this, 'autotwitter_fieldText'],
            $slug,
            Autotwitter_App::autotwitter_getSlug('section_id'),
            [
                'label_for' => Autotwitter_App::autotwitter_getSlug('consumerSecret'),
                'name'      => Autotwitter_App::autotwitter_getSlug('consumerSecret'),
                'class'     => 'input-field',
            ]
        );

        // Token Key
        $field_name = __('Token Key', 'sv-twitter');
        register_setting($group, Autotwitter_App::autotwitter_getSlug('tokenKey'), function ($value) use ($field_name) {
            return $this->autotwitter_validationText($value, $field_name);
        });

        add_settings_field(
            Autotwitter_App::autotwitter_getSlug('tokenKey'),
            __('Token Key', 'sv-twitter'),
            [$this, 'autotwitter_fieldText'],
            $slug,
            Autotwitter_App::autotwitter_getSlug('section_id'),
            [
                'label_for' => Autotwitter_App::autotwitter_getSlug('tokenKey'),
                'name'      => Autotwitter_App::autotwitter_getSlug('tokenKey'),
                'class'     => 'input-field',
            ]
        );

        // Token Secret
        $field_name = __('Token Secret', 'sv-twitter');
        register_setting($group, Autotwitter_App::autotwitter_getSlug('tokenSecret'), function ($value) use ($field_name) {
            return $this->autotwitter_validationText($value, $field_name);
        });

        add_settings_field(
            Autotwitter_App::autotwitter_getSlug('tokenSecret'),
            __('Token Secret', 'sv-twitter'),
            [$this, 'autotwitter_fieldText'],
            $slug,
            Autotwitter_App::autotwitter_getSlug('section_id'),
            [
                'label_for' => Autotwitter_App::autotwitter_getSlug('tokenSecret'),
                'name'      => Autotwitter_App::autotwitter_getSlug('tokenSecret'),
                'class'     => 'input-field',
            ]
        );

        // Post Types
        register_setting($group, Autotwitter_App::autotwitter_getSlug('postTypes'), [$this, 'validatePostTypes']);
        add_settings_field(
            Autotwitter_App::autotwitter_getSlug('postTypes'),
            __('Tipos de Post', 'sv-twitter'),
            [$this, 'autotwitter_fieldPostType'],
            $slug,
            Autotwitter_App::autotwitter_getSlug('section_id'),
            [
                'label_for' => Autotwitter_App::autotwitter_getSlug('postTypes'),
                'name'      => Autotwitter_App::autotwitter_getSlug('postTypes'),
                'class'     => 'input-field',
            ]
        );

        // Categories
        register_setting($group, Autotwitter_App::autotwitter_getSlug('Categories'));
        add_settings_field(
            Autotwitter_App::autotwitter_getSlug('Categories'),
            __('Categorias', 'sv-twitter'),
            [$this, 'autotwitter_fieldTaxonomy'],
            $slug,
            Autotwitter_App::autotwitter_getSlug('section_id'),
            [
                'label_for' => Autotwitter_App::autotwitter_getSlug('Categories'),
                'name'      => Autotwitter_App::autotwitter_getSlug('Categories'),
                'class'     => 'input-field',
            ]
        );
    }

    /**
    * Return all post types registered
    * @param array $args
    * @return void
    */
    function autotwitter_fieldPostType(array $args): void {
        $postTypes  = $this->autotwitter_getPostTypes();
        $checked    = !empty($checked = get_option($args['name'])) ? $checked : [];

        require_once STUDIO_TWITTER_PLUGIN_DIR . 'views/options/postTypes.php';
    }

    /**
    * Return Taxonomy Object Field
    * @param array $args
    * @return void
    */
    function autotwitter_fieldTaxonomy(array $args): void {
        $categories = get_categories(['hide_empty' => false]);
        $checked    = !empty($checked = get_option($args['name'])) ? $checked : [];

        require_once STUDIO_TWITTER_PLUGIN_DIR . 'views/options/Taxonomies.php';
    }

    /**
    * Return Text Field
    * @param array $args
    * @return void
    */
    function autotwitter_fieldText(array $args): void {
        printf(
            '<input type="text" id="%s" name="%s" class="%s" value="%s" />',
            $args['name'],
            $args['name'],
            $args['class'],
            get_option($args['name']) ?? '',
        );
    }

    /**
    * Prints checkbox for boolean fields
    * @param array $args
    * @return void
    */
    function autotwitter_fieldBoolean(array $args): void {
        $value = get_option($args['name']);
        ?>
            <input type="checkbox" name="<?php echo $args['name']; ?>" id="<?php echo $args['name']; ?>" value="on" <?php checked($value, 'on') ?> />
        <?php
    }

    /**
    * Check for post types
    * @param array|null $value
    * @return array|null
    */
    public function autotwitter_validatePostTypes($value) {
        // Check if is empty
        if(empty($value)) {
            // add validation error
            add_settings_error(
                Autotwitter_App::autotwitter_getSlug('errors'),
                'post-type-is-needed',
                'Você precisa escolher ao menos um post type',
                'error'
            );

            // Disable active for plugin
            update_option(Autotwitter_App::autotwitter_getSlug('isActive'), false);

            return false;
        }

        return $value;
    }

    /**
    * Validations and Sanitize
    * @param string $value
    * @param string $field_name
    * @return string
    */
    public function autotwitter_validationText(string $value, string $field_name) {
        // Sanitize Text
        $value = sanitize_text_field($value);

        // Check if is empty
        if(empty($value)) {
            // add validation error
            add_settings_error(
                Autotwitter_App::autotwitter_getSlug('errors'),
                'could-not-be-empty',
                __('Você precisa preencher o campo ' . $field_name, 'sv-twitter'),
                'error'
            );

            // Disable active for plugin
            update_option(Autotwitter_App::autotwitter_getSlug('isActive'), false);

            return false;
        }

        return $value;
    }

    /**
    * Show notices on saved options
    * @return void
    */
    public function autotwitter_notices(): void {

        $checkErrors = get_settings_errors(Autotwitter_App::autotwitter_getSlug('errors'));

        // If found any error skip success
        if (!empty($checkErrors)) {
            return;
        }

        if(
            isset( $_GET[ 'page' ] )
            && Autotwitter_App::autotwitter_getKey() == $_GET[ 'page' ]
            && isset( $_GET[ 'settings-updated' ] )
            && true == $_GET[ 'settings-updated' ]
        ) {
            ?>
                <div class="notice notice-success is-dismissible">
                    <p>
                        <strong><?php echo _e('Suas opções foram salvas.', 'sv-twitter'); ?></strong>
                    </p>
                </div>
            <?php
        }

        if(
            isset( $_GET[ 'page' ] )
            && Autotwitter_App::autotwitter_getKey('logs_') == $_GET[ 'page' ]
            && $_SERVER['REQUEST_METHOD'] === 'POST'
            && !empty($_POST['clear'])
        ) {
            ?>
                <div class="notice notice-success is-dismissible">
                    <p>
                        <strong><?php echo _e('Seus logs foram excluídos.', 'sv-twitter'); ?></strong>
                    </p>
                </div>
            <?php
        }
    }

    /**
    * Add Meta Boxes
    * @param string $post_type
    * @return void
    */
    public function autotwitter_addMetaBoxes($post_type): void {

        // Check if plugin is active and post types is checked
        $settings = self::autotwitter_getSettings();

        if(empty($settings['isActive']) || (!empty($settings['isActive']) && !in_array($post_type, $settings['postTypes']))) {
            return;
        }

        add_meta_box(
            Autotwitter_App::autotwitter_getKey(),
            __(Autotwitter_App::$name, Autotwitter_App::autotwitter_getKey()),
            [$this, 'autotwitter_renderMetaBox'],
            $post_type,
            'side',
            'high'
        );
    }

    /**
    * Renders Meta Box
    * @param Wp_post $post
    * @return void
    */
    public function autotwitter_renderMetaBox($post): void {
        $slug      = Autotwitter_App::autotwitter_getSlug();

        // settings to post
        $default   = in_array($post->post_status, ["future", "draft", "auto-draft", "pending"]) ? 'yes' : 'no';
        $autoPost  = get_post_meta($post->ID, $slug . '_active', true);
        $autoPost  = ($autoPost == '' || $autoPost == 'yes') ? $default : 'no';

        // Title settings
        $title = get_post_meta($post->ID, $slug . '_title', true);

        require_once STUDIO_TWITTER_PLUGIN_DIR . 'views/metabox/post.php';
    }

    /**
    * Saves meta box info's
    * @param int post_id
    * @return void|int
    */
    public function autotwitter_saveMetaBox(int $post_id) {
        // Set Slugs
        $slug          = Autotwitter_App::autotwitter_getSlug();
        $nonce_action  = $slug . '_nonce_action';
        $nonce_field   = $slug . '_nonce_field';
        $active        = $slug . '_active';
        $newTitle      = $slug . '_title';

        // Check if our nonce is set.
        if(!isset($_POST[$active])) {
            return $post_id;
        }

        if(empty($_POST[$nonce_field])) {
            return $post_id;
        }

        // Get Nonce from Post
        $nonce = sanitize_text_field($_POST[$nonce_field]);

        // Verify that the nonce is valid.
        if(!wp_verify_nonce($nonce, $nonce_action)) {
            return $post_id;
        }

        // Skip on auto save
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }

        // Sanitize the user input.
        $isActive = sanitize_text_field($_POST[$active]);
        $title    = sanitize_text_field($_POST[$newTitle]);

        // Update the meta field.
        update_post_meta($post_id, $active, $isActive);
        update_post_meta($post_id, $newTitle, $title);
    }

    /**
    * Get All Settings
    * @return array
    */
    public static function autotwitter_getSettings(): array {
        $settings = [];
        $settings['isActive']       = get_option(Autotwitter_App::autotwitter_getSlug('isactive'));
        $settings['consumerKey']    = get_option(Autotwitter_App::autotwitter_getSlug('consumerKey'));
        $settings['consumerSecret'] = get_option(Autotwitter_App::autotwitter_getSlug('consumerSecret'));
        $settings['tokenKey']       = get_option(Autotwitter_App::autotwitter_getSlug('tokenKey'));
        $settings['tokenSecret']    = get_option(Autotwitter_App::autotwitter_getSlug('tokenSecret'));
        $settings['categories']     = get_option(Autotwitter_App::autotwitter_getSlug('categories'));
        $settings['postTypes']      = get_option(Autotwitter_App::autotwitter_getSlug('posttypes'));

        return $settings;
    }

    /**
    * Check's for plugin activation
    */
    public static function autotwitter_isActive() {

        if(
            empty(self::autotwitter_getSettings()['consumerKey']) ||
            empty(self::autotwitter_getSettings()['consumerSecret']) ||
            empty(self::autotwitter_getSettings()['tokenKey']) ||
            empty(self::autotwitter_getSettings()['tokenSecret']) ||
            empty(self::autotwitter_getSettings()['postTypes'])
        ) {
            return false;
        }

        return !empty(self::autotwitter_getSettings()['isActive']) ? true : false;
    }

    /**
    * Get Post Types and exclude defaults
    * @return array
    */
    public function autotwitter_getPostTypes(): array {
        $post_types = get_post_types();

        unset($post_types['revision']);
        unset($post_types['attachment']);
        unset($post_types['nav_menu_item']);
        unset($post_types['custom_css']);
        unset($post_types['customize_changeset']);
        unset($post_types['oembed_cache']);
        unset($post_types['user_request']);
        unset($post_types['wp_block']);
        unset($post_types['wp_template']);
        unset($post_types['wp_template_part']);
        unset($post_types['wp_global_styles']);
        unset($post_types['wp_navigation']);

        return $post_types;
    }
}
