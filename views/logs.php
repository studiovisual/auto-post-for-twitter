<?php
/**
 * Log view file
 * View of logs
 * php version 8.1
 *
 * @category File
 * @package  Logs_Auto_Post_For_Twitter
 * @author   Studio Visual <atendimento@studiovisual.com.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.studiovisual.com.br
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div class="wrap sv-plugin">
    <h1><?php printf( esc_html__('%s', 'sv-twitter'), esc_html(get_admin_page_title())); ?></h1>

    <?php if (! empty($_GET['success']) ) { ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <strong>
        <?php
        echo esc_html_e('Suas opções foram salvas.', 'sv-twitter');
        ?>
                </strong>
            </p>
        </div>
    <?php } ?>

    <p>
        <?php
        echo esc_html_e(
            'Aqui você pode acompanhar os logs das publicações',
            'sv-twitter'
        );
        ?>
    </p>

    <?php if (! empty($logs) ) { ?>
        <form method="post" action="">
            <input type="hidden" name="clear" id="clear" value="clear" />
            <input type="hidden" name="clear_nonce" id="clear_nonce" value="<?php echo esc_attr(wp_create_nonce('clear_logs')); ?>" /> <?php //phpcs:ignore ?>
            <button class="clear-logs button">
        <?php echo esc_html_e('Limpar Logs', 'sv-twitter'); ?>
            </button>
        </form>
    <?php } ?>

    <?php if (! empty($logs) ) { ?>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-post-id">
        <?php echo esc_html_e('Post ID', 'sv-twitter'); ?>
                    </th>
                    <th scope="col" class="manage-column column-status">
        <?php echo esc_html_e('Status', 'sv-twitter'); ?>
                    </th>
                    <th scope="col" class="manage-column column-date">
        <?php echo esc_html_e('Data', 'sv-twitter'); ?>
                    </th>
                    <th scope="col" class="manage-column column-detalhes">
        <?php echo esc_html_e('Detalhes', 'sv-twitter'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ( $logs as $log ) { ?>
            <tr>
                <td scope="col" class="manage-column column-post-id">
                    <a href="<?php echo esc_url(admin_url('post.php?post=' . $log['post_id'] . '&action=edit')); ?>" title="Go to Post"><?php //phpcs:ignore ?>
                    <?php printf(esc_html__('%s', 'sv-twitter'), esc_html($log['post_id'])); ?> <?php //phpcs:ignore ?>
                    </a>
                </td>
                <td scope="col" class="manage-column column-status">
                    <?php printf(esc_html__('%s', 'sv-twitter'), esc_html($log['status'])); ?> <?php //phpcs:ignore ?>
                </td>
                <td scope="col" class="manage-column column-date">
                    <?php printf(esc_html__('%s', 'sv-twitter'), esc_html(date('d/m/Y \a\s H:i:s', strtotime($log['date'])))); ?><?php //phpcs:ignore ?>
                </td>
                <td scope="col" class="manage-column column-detalhes">
                    <?php printf(esc_html__('%s', 'sv-twitter'), esc_html($log['message'])); ?> <?php //phpcs:ignore ?>
                </td>
            </tr>
        <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th scope="col" class="manage-column column-post-id">
        <?php echo esc_html_e('Post ID', 'sv-twitter'); ?>
                    </th>
                    <th scope="col" class="manage-column column-status">
        <?php echo esc_html_e('Status', 'sv-twitter'); ?>
                    </th>
                    <th scope="col" class="manage-column column-date">
        <?php echo esc_html_e('Data', 'sv-twitter'); ?>
                    </th>
                    <th scope="col" class="manage-column column-detalhes">
        <?php echo esc_html_e('Detalhes', 'sv-twitter'); ?>
                    </th>
                </tr>
            </tfoot>
        </table>
    <?php } else { ?>
        <p><?php echo esc_html_e('Nenhum log encontrado', 'sv-twitter'); ?></p>
    <?php } ?>
</div>
