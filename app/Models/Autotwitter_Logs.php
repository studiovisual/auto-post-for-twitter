<?php
/**
 * Class Logs Auto Twitter
 * View of logs
 * php version 8.1
 *
 * @category Class
 * @package  Autotwitter_Logs
 * @author   Studio Visual <atendimento@studiovisual.com.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.studiovisual.com.br
 */

namespace StudioVisual\Twitter\Models;

use StudioVisual\Twitter\Autotwitter_App;

/**
 * AutoTwitter Logs Class
 *
 * @category Class
 * @package  Autotwitter_Logs
 * @author   Studio Visual <atendimento@studiovisual.com.br>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.studiovisual.com.br
 */
class Autotwitter_Logs
{
    protected $table;

    /**
     * Construct 
     */
    public function __construct()
    {
        global $wpdb;

        // Get slugified name from plugin
        $this->table = $wpdb->prefix . Autotwitter_App::autotwitter_getSlug('logs');
    }

    /**
     * Create Table Logs
     *
     * @return bool
     */
    public function autotwitter_createTable(): bool //phpcs:ignore
    {
        global $wpdb;

        // Check if Table Exists
        $check = $wpdb->query("SHOW TABLES LIKE '" . $this->table . "'");

        // If table not exists create it
        if (!$check) {
            $query = "CREATE TABLE `" . $this->table . "` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `post_id` INT NULL,
                `date` DATETIME NULL,
                `status` VARCHAR(50) NULL,
                `message` VARCHAR(255) NULL,
                PRIMARY KEY (`id`));";

            // Create Table via Query
            $create = $wpdb->query($query);

            if (!$create) {
                return false;
            }

            // Add version
            add_option(
                Autotwitter_App::autotwitter_getSlug('dbversion'), 
                STUDIO_TWITTER_VERSION
            );
        }

        return true;
    }

    /**
     * Drop Table
     *
     * @return bool
     */
    public function autotwitter_drop(): bool //phpcs:ignore
    {
        global $wpdb;

        return $wpdb->query('DROP TABLE IF EXISTS ' . $this->table);
    }

    /**
     * Add Logs
     *
     * @param int    $post_id post_id
     * @param string $status  status
     * @param string $message message
     * 
     * @return bool
     */
    public function autotwitter_add(int $post_id, string $status, string $message): bool //phpcs:ignore	
    {
        global $wpdb;

        if (!$post_id || !$message || !$status) {
            return false;
        }

        $data = [
            'post_id' => $post_id,
            'date'    => current_time('mysql'),
            'status'  => $status,
            'message' => $message,
        ];

        $check = $wpdb->insert($this->table, $data);

        return !empty($check) ? true : false;
    }

    /**
     * Truncate logs
     *
     * @return void
     */
    public function autotwitter_truncate() //phpcs:ignore
    {
        global $wpdb;

        $query = "TRUNCATE TABLE " . $this->table;
        $truncate = $wpdb->query($query);
    }

    /**
     * Get logs
     *
     * @param int $limit limite de logs
     * 
     * @return array
     */
    public function autotwitter_get(int $limit = 30): array //phpcs:ignore
    {
        global $wpdb;

        $query = "
            SELECT * FROM " . $this->table . " 
            ORDER BY id DESC 
            LIMIT " . $limit . " ";
        $logs = $wpdb->get_results($query, ARRAY_A);

        return !empty($logs) ? $logs : [];
    }
}
