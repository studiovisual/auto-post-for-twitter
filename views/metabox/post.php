<div class="sv-metabox">
    <div class="sv-inputs isActive">
        <label for="<?php echo $slug; ?>_active">Publicar no Twitter?</label>
        <select name="<?php echo $slug; ?>_active">
            <option value="yes" <?php selected($autoPost, 'yes'); ?>>Sim</option>
            <option value="no" <?php selected($autoPost, 'no'); ?>>Não</option>
        </select>
    </div>

    <div class="sv-inputs title">
        <label for="<?php echo $slug; ?>_title">Título Alternativo</label>
        <input type="text" name="<?php echo $slug; ?>_title" id="<?php echo $slug; ?>_title" value="<?php echo !empty($title) ? $title : '' ?>" />
    </div>

    <?php wp_nonce_field($slug . '_nonce_action', $slug . '_nonce_field'); ?>
</div>