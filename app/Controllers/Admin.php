<?php 

namespace StudioVisual\Twitter\Controllers;

use StudioVisual\Twitter\App;

Class Admin {

	public function __construct() {
        // Hook to create admin menu
        add_action('admin_menu', [$this, 'optionsPage']);

        // Actions for set fields
        add_action('admin_init', [$this, 'settingFields']);

        // Actions for notices
        add_action('admin_notices', [$this, 'notices']);

        // Enqueue Scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueueAssets'), 100);
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

    public function docs() {
        echo 'Docs';
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
        register_setting($group, App::getSlug('isActive'), [$this, 'sanitizeCheckbox']);
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
        register_setting($group, App::getSlug('consumerKey'), [$this, 'validationText']);
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
        register_setting($group, App::getSlug('consumerSecret'), [$this, 'validationText']);
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
        register_setting($group, App::getSlug('tokenKey'), [$this, 'validationText']);
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
        register_setting($group, App::getSlug('tokenSecret'), [$this, 'validationText']);
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
            <input type="checkbox" name="<?php echo $args['name']; ?>" id="<?php echo $args['name']; ?>" <?php checked($value, true) ?> />
        <?php
    }
    
    /**
    * Sanitizes checkbox values
    * @param $value
    * @return bool
    */
    public function sanitizeCheckbox($value): bool {
        return 'on' === $value ? true : false;
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
        }

        return $value;
    }

    /**
    * Validations and Sanitize
    * @param string $value
    * @return string 
    */
    public function validationText(string $value): string {
        // Sanitize Text
        $value = sanitize_text_field($value);

        // Check if is empty
        if(empty($value)) {
            // add validation error
            add_settings_error(
                App::getSlug('errors'),
                'could-not-be-empty',
                'Verifique o preenchimento correto dos campos',
                'error'
            );

            // Disable active for plugin
            update_option(App::getSlug('isActive'), false);
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