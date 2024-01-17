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
?>
<div class="sv-metabox">
    <div class="sv-inputs isActive">
        <label for="<?php echo esc_attr($slug); ?>_active"><?php echo _e('Publicar no Twitter?', 'sv-twitter'); ?></label> <?php //phpcs:ignore ?>
        <select name="<?php echo esc_attr($slug); ?>_active">
            <option value="yes" <?php selected($autoPost, 'yes'); ?>><?php echo _e('Sim', 'sv-twitter'); ?></option> <?php //phpcs:ignore ?>
            <option value="no" <?php selected($autoPost, 'no'); ?>><?php echo _e('Não', 'sv-twitter'); ?></option> <?php //phpcs:ignore ?>
        </select>
    </div>

    <div class="sv-inputs title">
        <label for="<?php echo esc_attr($slug); ?>_title"><?php echo _e('Título alternativo', 'sv-twitter'); ?></label> <?php //phpcs:ignore ?>
        <input type="text" name="<?php echo esc_attr($slug); ?>_title" id="<?php echo esc_attr($slug); ?>_title" value="<?php echo !empty($title) ? esc_attr($title) : '' ?>" /> <?php //phpcs:ignore ?>
    </div>

    <?php wp_nonce_field($slug . '_nonce_action', $slug . '_nonce_field'); ?>
</div>
