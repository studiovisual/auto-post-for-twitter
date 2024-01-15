<p class="helper"><?php echo _e('Selecione as categorias que você <strong>não</strong> quer que sejam publicadas automaticamente no twitter', 'sv-twitter'); ?></p>
<?php if(!empty($categories)) { ?>
    <div class="categories">
        <ul>
            <?php foreach ($categories as $category) { ?>
                <li>
                    <input type="checkbox" name="<?php echo esc_attr($args['name']); ?>[]" id="<?php echo esc_attr($args['name']) . $category->term_id; ?>" value="<?php echo esc_attr($category->term_id); ?>"
                    <?php checked(in_array($category->term_id, $checked), 1); ?> />
                    <label for="<?php echo esc_attr($args['name']) . $category->term_id; ?>"><?php echo esc_html_e($category->name); ?></label>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>
