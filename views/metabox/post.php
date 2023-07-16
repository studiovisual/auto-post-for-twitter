<div class="sv-metabox">
    <div class="sv-inputs isActive">
        <label for="<?php echo $slug; ?>_active"><?php echo _e('Publicar no Twitter?', 'sv-twitter'); ?></label>
        <select name="<?php echo $slug; ?>_active">
            <option value="yes" <?php selected($autoPost, 'yes'); ?>><?php echo e_('Sim', 'sv-twitter'); ?></option>
            <option value="no" <?php selected($autoPost, 'no'); ?>><?php echo e_('Não', 'sv-twitter'); ?></option>
        </select>
    </div>

    <div class="sv-inputs title">
        <label for="<?php echo $slug; ?>_title"><?php echo _e('Título alternativo', 'sv-twitter'); ?></label>
        <input type="text" name="<?php echo $slug; ?>_title" id="<?php echo $slug; ?>_title" value="<?php echo !empty($title) ? $title : '' ?>" />
    </div>

    <?php wp_nonce_field($slug . '_nonce_action', $slug . '_nonce_field'); ?>
</div>