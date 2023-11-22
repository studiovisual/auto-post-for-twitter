<p class="helper"><?php echo _e('Escolha os post types que deverão ser <strong>inclusos</strong>:', 'sv-twitter'); ?></p>
<?php if(!empty($postTypes)) { ?>
    <div class="categories">
        <ul>
            <?php foreach ($postTypes as $postType) { ?>
                <li>
                    <input type="checkbox" name="<?php echo esc_attr($args['name']); ?>[]" id="<?php echo esc_attr($args['name']) . $postType; ?>" value="<?php echo esc_attr($postType); ?>"
                    <?php checked(in_array($postType, $checked), 1 ); ?> />
                    <label for="<?php echo esc_attr($args['name']) . $postType; ?>"><?php echo esc_html_e(ucwords($postType)); ?></label>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>
