<?php 
/**
 * Settings
 * Settings of plugin
 * php version 8.1
 *
 * @category File
 * @package  Settings_Auto_Post_For_Twitter
 * @author   Studio Visual <atendimento@studiovisual.com.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.studiovisual.com.br
 */
?>
<div class="wrap sv-plugin">
    <h1><?php echo esc_html_e(get_admin_page_title()) ?></h1>
    <p><?php echo _e('Caso não saiba como conseguir as suas chaves de Api, acesse a documentação', 'sv-twitter'); ?> <a href="<?php echo esc_url(admin_url('admin.php?page=' . $docs)); ?>" title="<?php echo _e('Documentação', 'sv-twitter'); ?>"><?php echo _e('clicando aqui', 'sv-twitter'); ?></a>.</p> <?php //phpcs:ignore ?>
    <form method="post" action="options.php">
        <?php
            settings_errors($validation);
            settings_fields($group);
            do_settings_sections($slug);
            submit_button();
        ?>
    </form>
</div>
