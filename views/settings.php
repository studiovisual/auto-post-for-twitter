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

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div class="wrap sv-plugin">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <p><?php echo esc_html_e('If you don\'t know how to get your API keys, go to the documentation', 'sv-twitter'); ?> <a href="<?php echo esc_url(admin_url('admin.php?page=' . $docs)); ?>" title="<?php echo esc_html_e('Documentation', 'sv-twitter'); ?>"><?php echo esc_html_e('clicking here', 'sv-twitter'); ?></a>.</p> <?php //phpcs:ignore ?>
    <form method="post" action="options.php">
        <?php
        settings_errors($validation);
        settings_fields($group);
        do_settings_sections($slug);
        submit_button();
        ?>
    </form>
</div>
