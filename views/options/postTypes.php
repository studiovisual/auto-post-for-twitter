<?php
/**
 * Log view file
 * Post Types
 * php version 8.1
 *
 * @category File
 * @package  PostTypes_Auto_Post_For_Twitter
 * @author   Studio Visual <atendimento@studiovisual.com.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.studiovisual.com.br
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<p class="helper">
    <?php echo esc_html_e('Choose the post types that should be *included*:', 'sv-twitter'); //phpcs:ignore ?>
</p>
<?php if (! empty($postTypes) ) { ?>
    <div class="categories">
        <ul>
    <?php foreach ( $postTypes as $postType ) { ?>
        <li>
            <input type="checkbox" name="<?php echo esc_attr($args['name']); ?>[]" id="<?php echo esc_attr($args['name'] . '_' . $postType); ?>" value="<?php echo esc_attr($postType); ?>" <?php //phpcs:ignore ?>
            <?php checked(in_array($postType, $checked), 1); ?> /> <?php //phpcs:ignore ?>
            <label for="<?php echo esc_attr($args['name'] . '_' . $postType); ?>">
            <?php printf(esc_html__('%s', 'sv-twitter'), esc_html(ucwords($postType))); ?>
            </label>
        </li>
    <?php } ?>
        </ul>
    </div>
<?php } ?>
