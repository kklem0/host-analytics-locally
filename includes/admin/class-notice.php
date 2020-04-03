<?php
/* * * * * * * * * * * * * * * * * * * *
 *  ██████╗ █████╗  ██████╗ ███████╗
 * ██╔════╝██╔══██╗██╔═══██╗██╔════╝
 * ██║     ███████║██║   ██║███████╗
 * ██║     ██╔══██║██║   ██║╚════██║
 * ╚██████╗██║  ██║╚██████╔╝███████║
 *  ╚═════╝╚═╝  ╚═╝ ╚═════╝ ╚══════╝
 *
 * @author   : Daan van den Bergh
 * @url      : https://daan.dev/wordpress-plugins/caos/
 * @copyright: (c) 2020 Daan van den Bergh
 * @license  : GPL2v2 or later
 * * * * * * * * * * * * * * * * * * * */

defined('ABSPATH') || exit;

class CAOS_Admin_Notice
{
    const CAOS_ADMIN_NOTICE_TRANSIENT  = 'caos_admin_notice';
    const CAOS_ADMIN_NOTICE_EXPIRATION = 86400;

    /** @var array $notices */
    public static $notices = [];

    /**
     * @param        $message
     * @param string $type (info|warning|error|success)
     * @param string $screen_id
     * @param bool   $json
     * @param int    $code
     */
    public static function set_notice($message, $die = true, $type = 'success', $code = 200, $screen_id = 'all')
    {
        self::$notices                    = get_transient(self::CAOS_ADMIN_NOTICE_TRANSIENT);
        self::$notices[$screen_id][$type] = $message;

        set_transient(self::CAOS_ADMIN_NOTICE_TRANSIENT, self::$notices, self::CAOS_ADMIN_NOTICE_EXPIRATION);

        if ($die) {
            switch ($type) {
                case 'error':
                    wp_send_json_error($message, $code);
                    break;
                default:
                    wp_send_json_success($message, $code);
            }
        }
    }

    /**
     * Prints notice (if any)
     */
    public static function print_notice()
    {
        $admin_notices = get_transient(self::CAOS_ADMIN_NOTICE_TRANSIENT);

        if (is_array($admin_notices)) {
            $current_screen = get_current_screen();

            foreach ($admin_notices as $screen => $notice) {
                if ($current_screen->id != $screen && $screen != 'all') {
                    continue;
                }

                foreach ($notice as $type => $message) {
                    ?>
                    <div id="message" class="notice notice-<?php echo $type; ?> is-dismissible">
                        <p><?php _e($message, 'host-analyticjs-local'); ?></p>
                    </div>
                    <?php
                }
            }
        }

        delete_transient(self::CAOS_ADMIN_NOTICE_TRANSIENT);
    }
}

