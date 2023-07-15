<div class="wrap sv-plugin">
    <h1><?php echo get_admin_page_title() ?></h1>
    <p>Caso não saiba como conseguir as suas chaves de Api, acesse a documentação <a href="<?php echo admin_url('admin.php?page=' . $docs); ?>" title="Documentação">clicando aqui</a>.</p>
    <form method="post" action="options.php">
        <?php
            settings_errors($validation);
            settings_fields($group);
            do_settings_sections($slug);
            submit_button();
        ?>
    </form>
</div>