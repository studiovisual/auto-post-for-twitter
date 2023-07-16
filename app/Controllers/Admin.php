<?php 

namespace StudioVisual\Twitter\Controllers;

use StudioVisual\Twitter\App;
use StudioVisual\Twitter\Models\Logs;

Class Admin {
	public function __construct() {
        // Hook to create admin menu
        add_action('admin_menu', [$this, 'optionsPage']);

        // Actions for set fields
        add_action('admin_init', [$this, 'settingFields']);

        // Actions for notices
        add_action('admin_notices', [$this, 'notices']);

        // Enqueue Scripts
        add_action('admin_enqueue_scripts', [$this, 'enqueueAssets'], 100);

        // Add Meta boxes to Posts
        add_action('add_meta_boxes', [$this, 'addMetaBoxes']);

        // Save Meta Boxes
        add_action('save_post', [$this, 'saveMetaBox'], 11);

        // Instance Class
        $this->logs = new Logs;
    }
    
    /**
    * Enqueue Scripts/Styles
    *
    * @return void
    */
    function enqueueAssets() {            
        wp_enqueue_style('starter-plugin-style', STUDIO_TWITTER_PLUGIN_URL . '/../assets/css/style.css', false, null);
        wp_enqueue_script('starter-plugin-script', STUDIO_TWITTER_PLUGIN_URL . '/assets/js/script.js', array('jquery')); 
    }

    /**
    * Create options page
    *
    * @return void
    */
    public function optionsPage(): void {
        add_menu_page(
            App::$name,
            App::$name,
            'manage_options',
            App::getKey(),
            [$this, 'settingsPage'],
            'dashicons-twitter',
            99.59,
        );

        add_submenu_page(
            App::getKey(),
            'Documentação',
            'Documentação',
            'manage_options',
            App::getKey('docs_'),
            [$this, 'docs'],
            2,
        );

        add_submenu_page(
            App::getKey(),
            'Logs',
            'Logs',
            'manage_options',
            App::getKey('logs_'),
            [$this, 'logs'],
            2,
        );
    }

    /**
    * Settings Page
    * @return void 
    */
    public function settingsPage(): void {
        $validation = App::getSlug('errors');
        $group      = App::getSlug('settings');
        $slug       = App::getSlug();
        $docs       = App::getKey('docs_');

        require_once STUDIO_TWITTER_PLUGIN_DIR . 'views/settings.php';
    }

    /**
    * Docs Page
    * @return void 
    */
    public function docs(): void {
        $settingsPage = App::getKey();
        require_once STUDIO_TWITTER_PLUGIN_DIR . 'views/docs.php';
    }

    /**
    * Logs Page
    * @return void 
    */
    public function logs(): void {
        // Clear the logs
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // clear logs
            $this->logs->truncate();
        }

        // Get last 30 logs
        $logs     = $this->logs->get();
        $current  = App::getKey('logs_');

        require_once STUDIO_TWITTER_PLUGIN_DIR . 'views/logs.php';
    }

    /**
    * Settings options fields
    * @return void
    */
    function settingFields(): void {

        // Variables for slugs and group
        $group = App::getSlug('settings');
        $slug = App::getSlug();        
    
        // Create Section
        add_settings_section(
            App::getSlug('section_id'),
            '', // title (optional)
            '', // callback (optional)
            $slug,
        );
    
        // Is Active
        register_setting($group, App::getSlug('isActive'));
        add_settings_field(
            App::getSlug('isActive'),
            'Ativar',
            [$this, 'fieldBoolean'],
            $slug,
            App::getSlug('section_id'),
            [
                'label_for' => App::getSlug('isActive'),
                'name'      => App::getSlug('isActive'),
                'class'     => 'input-field', 
            ]
        );
        
        // Consumer Key
        $field_name = 'Consumer Key';
        register_setting($group, App::getSlug('consumerKey'), function ($value) use ($field_name) {
            return $this->validationText($value, $field_name); 
        });

        add_settings_field(
            App::getSlug('consumerKey'),
            'Consumer Key',
            [$this, 'fieldText'],
            $slug,
            App::getSlug('section_id'),
            [
                'label_for' => App::getSlug('consumerKey'),
                'name'      => App::getSlug('consumerKey'),
                'class'     => 'input-field',
            ]
        );

        // Consumer Secret
        $field_name = 'Consumer Secret';
        register_setting($group, App::getSlug('consumerSecret'), function ($value) use ($field_name) {
            return $this->validationText($value, $field_name); 
        });

        add_settings_field(
            App::getSlug('consumerSecret'),
            'Consumer Secret',
            [$this, 'fieldText'],
            $slug,
            App::getSlug('section_id'),
            [
                'label_for' => App::getSlug('consumerSecret'),
                'name'      => App::getSlug('consumerSecret'),
                'class'     => 'input-field',
            ]
        );

        // Token Key
        $field_name = 'Token Key';
        register_setting($group, App::getSlug('tokenKey'), function ($value) use ($field_name) {
            return $this->validationText($value, $field_name); 
        });

        add_settings_field(
            App::getSlug('tokenKey'),
            'Token Key',
            [$this, 'fieldText'],
            $slug,
            App::getSlug('section_id'),
            [
                'label_for' => App::getSlug('tokenKey'),
                'name'      => App::getSlug('tokenKey'),
                'class'     => 'input-field',
            ]
        );

        // Token Secret
        $field_name = 'Token Secret';
        register_setting($group, App::getSlug('tokenSecret'), function ($value) use ($field_name) {
            return $this->validationText($value, $field_name); 
        });

        add_settings_field(
            App::getSlug('tokenSecret'),
            'Token Secret',
            [$this, 'fieldText'],
            $slug,
            App::getSlug('section_id'),
            [
                'label_for' => App::getSlug('tokenSecret'),
                'name'      => App::getSlug('tokenSecret'),
                'class'     => 'input-field',
            ]
        );

        // Post Types
        register_setting($group, App::getSlug('postTypes'), [$this, 'validatePostTypes']);
        add_settings_field(
            App::getSlug('postTypes'),
            'Post Types',
            [$this, 'fieldPostType'],
            $slug,
            App::getSlug('section_id'),
            [
                'label_for' => App::getSlug('postTypes'),
                'name'      => App::getSlug('postTypes'),
                'class'     => 'input-field',
            ]
        );

        // Categories
        register_setting($group, App::getSlug('Categories'));
        add_settings_field(
            App::getSlug('Categories'),
            'Categorias',
            [$this, 'fieldTaxonomy'],
            $slug,
            App::getSlug('section_id'),
            [
                'label_for' => App::getSlug('Categories'),
                'name'      => App::getSlug('Categories'),
                'class'     => 'input-field',
            ]
        );
    }
    
    /**
    * Return all post types registered
    * @param array $args 
    * @return void
    */
    function fieldPostType(array $args): void {
        $postTypes  = $this->getPostTypes();
        $checked    = !empty($checked = get_option($args['name'])) ? $checked : []; 

        require_once STUDIO_TWITTER_PLUGIN_DIR . 'views/options/postTypes.php';
    }

    /**
    * Return Taxonomy Object Field
    * @param array $args 
    * @return void
    */
    function fieldTaxonomy(array $args): void {
        $categories = get_categories(['hide_empty' => false]);
        $checked    = !empty($checked = get_option($args['name'])) ? $checked : []; 

        require_once STUDIO_TWITTER_PLUGIN_DIR . 'views/options/Taxonomies.php';
    }

    /**
    * Return Text Field
    * @param array $args 
    * @return void
    */
    function fieldText(array $args): void {
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
    function fieldBoolean(array $args): void {
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
    public function validatePostTypes($value) {
        // Check if is empty
        if(empty($value)) {
            // add validation error
            add_settings_error(
                App::getSlug('errors'),
                'post-type-is-needed',
                'Você precisa escolher ao menos um post type',
                'error'
            );

            // Disable active for plugin
            update_option(App::getSlug('isActive'), false);

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
    public function validationText(string $value, string $field_name) {
        // Sanitize Text
        $value = sanitize_text_field($value);

        // Check if is empty
        if(empty($value)) {
            // add validation error
            add_settings_error(
                App::getSlug('errors'),
                'could-not-be-empty',
                'Você precisa preencher o campo ' . $field_name,
                'error'
            );

            // Disable active for plugin
            update_option(App::getSlug('isActive'), false);

            return false;
        }

        return $value;
    }

    /**
    * Show notices on saved options
    * @return void 
    */
    public function notices(): void {

        $checkErrors = get_settings_errors(App::getSlug('errors'));
        
        // If found any error skip success
        if (!empty($checkErrors)) {
            return;
        }
        
        if(
            isset( $_GET[ 'page' ] ) 
            && App::getKey() == $_GET[ 'page' ]
            && isset( $_GET[ 'settings-updated' ] ) 
            && true == $_GET[ 'settings-updated' ]
        ) {
            ?>
                <div class="notice notice-success is-dismissible">
                    <p>
                        <strong>Suas opções foram salvas.</strong>
                    </p>
                </div>
            <?php
        }

        if(
            isset( $_GET[ 'page' ] ) 
            && App::getKey('logs_') == $_GET[ 'page' ]
            && $_SERVER['REQUEST_METHOD'] === 'POST'
            && !empty($_POST['clear'])
        ) {
            ?>
                <div class="notice notice-success is-dismissible">
                    <p>
                        <strong>Seus logs foram excluídos.</strong>
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
    public function addMetaBoxes($post_type): void {

        // Check if plugin is active and post types is checked
        $settings = self::getSettings();

        if(empty($settings['isActive']) || (!empty($settings['isActive']) && !in_array($post_type, $settings['postTypes']))) {
            return;
        }

        add_meta_box(
            App::getKey(),
            __(App::$name, App::getKey()), 
            [$this, 'renderMetaBox'],
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
    public function renderMetaBox($post): void {
        $slug      = App::getSlug();

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
    public function saveMetaBox(int $post_id) {
        // Set Slugs
        $slug          = App::getSlug();
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
    public static function getSettings(): array {
        $settings = [];
        $settings['isActive']       = get_option('sv_twitter_api_isactive');
        $settings['consumerKey']    = get_option('sv_twitter_api_consumerKey');
        $settings['consumerSecret'] = get_option('sv_twitter_api_consumerSecret');
        $settings['tokenKey']       = get_option('sv_twitter_api_tokenKey');
        $settings['tokenSecret']    = get_option('sv_twitter_api_tokenSecret');
        $settings['categories']     = get_option('sv_twitter_api_categories');
        $settings['postTypes']      = get_option('sv_twitter_api_posttypes');

        return $settings;
    }

    /**
    * Check's for plugin activation 
    */
    public static function isActive() {

        if(
            empty(self::getSettings()['consumerKey']) ||
            empty(self::getSettings()['consumerSecret']) ||
            empty(self::getSettings()['tokenKey']) ||
            empty(self::getSettings()['tokenSecret']) ||
            empty(self::getSettings()['postTypes'])
        ) {
            return false;
        }

        return !empty(self::getSettings()['isActive']) ? true : false;
    }

    /**
    * Get Post Types and exclude defaults
    * @return array 
    */
    public function getPostTypes(): array {
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