<div class="sv-metabox">
    <div class="sv-inputs isActive">
        <label for="<?php echo esc_attr($slug); ?>_active"><?php echo esc_html_e('Publicar no Twitter?', 'sv-twitter'); ?></label>
        <select name="<?php echo esc_attr($slug); ?>_active">
            <option value="yes" <?php selected($autoPost, 'yes'); ?>><?php echo esc_html_e('Sim', 'sv-twitter'); ?></option>
            <option value="no" <?php selected($autoPost, 'no'); ?>><?php echo esc_html_e('Não', 'sv-twitter'); ?></option>
        </select>
    </div>

    <div class="sv-inputs title">
        <label for="<?php echo esc_attr($slug); ?>_title"><?php echo esc_html_e('Título alternativo', 'sv-twitter'); ?></label>
        <input type="text" name="<?php echo esc_attr($slug); ?>_title" id="<?php echo esc_attr($slug); ?>_title" value="<?php echo !empty($title) ? esc_attr($title) : '' ?>" />
    </div>

    <?php wp_nonce_field($slug . '_nonce_action', $slug . '_nonce_field'); ?>
</div>
