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
?>
<p class="helper">
    <?php echo _e('Escolha os post types que deverão ser <strong>inclusos</strong>:', 'sv-twitter'); //phpcs:ignore ?>
</p>
<?php if ( ! empty( $postTypes ) ) { ?>
	<div class="categories">
		<ul>
			<?php foreach ( $postTypes as $postType ) { ?>
				<li>
                    <input type="checkbox" name="<?php echo esc_attr($args['name']); ?>[]" id="<?php echo esc_attr($args['name']) . $postType; ?>" value="<?php echo esc_attr($postType); ?>" <?php //phpcs:ignore ?>
                    <?php checked(in_array($postType, $checked), 1); ?> /> <?php //phpcs:ignore ?>
					<label for="<?php echo esc_attr( $args['name'] ) . $postType; ?>">
						<?php echo esc_html_e( ucwords( $postType ) ); ?>
					</label>
				</li>
			<?php } ?>
		</ul>
	</div>
<?php } ?>
