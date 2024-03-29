<?php
/**
 * MetaBox
 * Metabox of plugin
 * php version 8.1
 *
 * @category File
 * @package  MetaBox_Auto_Post_For_Twitter
 * @author   Studio Visual <atendimento@studiovisual.com.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.studiovisual.com.br
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="sv-metabox">
    <div class="sv-inputs isActive">
        <label for="<?php echo esc_attr($slug); ?>_active"><?php echo esc_html_e('Publish on Twitter?', 'sv-twitter'); ?></label> <?php //phpcs:ignore ?>
        <select name="<?php echo esc_attr($slug); ?>_active">
            <option value="yes" <?php selected($autoPost, 'yes'); ?>><?php echo esc_html_e('Yes', 'sv-twitter'); ?></option> <?php //phpcs:ignore ?>
            <option value="no" <?php selected($autoPost, 'no'); ?>><?php echo esc_html_e('No', 'sv-twitter'); ?></option> <?php //phpcs:ignore ?>
        </select>
    </div>

    <div class="sv-inputs title">
        <label for="<?php echo esc_attr($slug); ?>_title"><?php echo esc_html_e('Alternative title', 'sv-twitter'); ?></label> <?php //phpcs:ignore ?>
        <input type="text" name="<?php echo esc_attr($slug); ?>_title" id="<?php echo esc_attr($slug); ?>_title" value="<?php echo !empty($title) ? esc_attr($title) : '' ?>" /> <?php //phpcs:ignore ?>
    </div>

    <?php wp_nonce_field($slug . '_nonce_action', $slug . '_nonce_field'); ?>
</div>
