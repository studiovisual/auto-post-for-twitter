<p class="helper">Selecione as categorias que você <strong>não</strong> quer que sejam publicadas automaticamente no twitter</p>
<?php if(!empty($categories)) { ?>
    <div class="categories">
        <ul>
            <?php foreach ($categories as $category) { ?>
                <li>
                    <input type="checkbox" name="<?php echo $args['name']; ?>[]" id="<?php echo $args['name'] . $category->term_id; ?>" value="<?php echo $category->term_id; ?>"
                    <?php checked(in_array($category->term_id, $checked), 1 ); ?> />
                    <label for="<?php echo $args['name'] . $category->term_id; ?>"><?php echo $category->name; ?></label>
                </li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>