<?php
/**
 * Taxonomies
 * Taxonomies of plugin
 * php version 8.1
 *
 * @category File
 * @package  Taxonomies_Auto_Post_For_Twitter
 * @author   Studio Visual <atendimento@studiovisual.com.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.studiovisual.com.br
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
 
?>
<p class="helper"><?php echo _e('Selecione as categorias que você *não* quer que sejam publicadas automaticamente no twitter', 'sv-twitter'); ?></p> <?php //phpcs:ignore ?> 
<?php if (! empty($categories) ) { ?>
    <div class="categories">
        <ul>
    <?php foreach ( $categories as $category ) { ?>
        <li>
            <input type="checkbox" name="<?php echo esc_attr($args['name']); ?>[]" id="<?php echo esc_attr($args['name'] . '_' . $category->term_id); ?>" value="<?php echo esc_attr($category->term_id); ?>" <?php //phpcs:ignore ?>
            <?php checked(in_array($category->term_id, $checked), 1); ?> />
            <label for="<?php echo esc_attr($args['name']) . $category->term_id; ?>"><?php printf(esc_html__('%s', 'sv-twitter'), esc_html($category->name)) ; ?></label> <?php //phpcs:ignore ?>
        </li>
    <?php } ?>
        </ul>
    </div>
<?php } ?>
