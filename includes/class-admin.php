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

class CAOS_Admin
{
    const CAOS_ADMIN_JS_HANDLE  = 'caos-admin-js';
    const CAOS_ADMIN_CSS_HANDLE = 'caos-admin-css';

    /**
     * CAOS_Admin constructor.
     */
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        add_action('admin_notices', [$this, 'add_notice']);

        // Notices
        add_action('update_option_sgal_tracking_id', [$this, 'add_tracking_code_notice'], 10, 2);
        add_action('update_option_sgal_script_position', [$this, 'add_script_position_notice'], 10, 2);
    }

    /**
     * Enqueues the necessary JS and CSS and passes options as a JS object.
     *
     * @param $hook
     */
    public function enqueue_admin_scripts($hook)
    {
        if ($hook == 'settings_page_host_analyticsjs_local') {
            wp_enqueue_script(self::CAOS_ADMIN_JS_HANDLE, plugin_dir_url(CAOS_PLUGIN_FILE) . 'assets/js/caos-admin.js', array('jquery'), CAOS_STATIC_VERSION, true);
            wp_enqueue_style(self::CAOS_ADMIN_CSS_HANDLE, plugin_dir_url(CAOS_PLUGIN_FILE) . 'assets/css/caos-admin.css', array(), CAOS_STATIC_VERSION);
        }
    }

    /**
     * Add notice to admin screen.
     */
    public function add_notice()
    {
        CAOS_Admin_Notice::print_notice();
    }

    /**
     * @param $new_tracking_id
     * @param $old_tracking_id
     *
     * @return mixed
     */
    public function add_tracking_code_notice($old_tracking_id, $new_tracking_id)
    {
        if ($new_tracking_id !== $old_tracking_id && !empty($new_tracking_id)) {
            CAOS_Admin_Notice::set_notice(__("CAOS has connected WordPress to Google Analytics using Tracking ID: $new_tracking_id.", 'host-analyticsjs-local'), false);
        }

        return $new_tracking_id;
    }

    /**
     * @param $new_position
     * @param $old_position
     *
     * @return mixed
     */
    public function add_script_position_notice($old_position, $new_position)
    {
        if ($new_position !== $old_position && !empty($new_position)) {
            switch ($new_position) {
                case 'manual':
                    CAOS_Admin_Notice::set_notice(__('Since you\'ve chosen to add it manually, don\'t forget to add the tracking code to your theme.', 'host-analyticsjs-local'), false, 'info');
                    break;
                default:
                    CAOS_Admin_Notice::set_notice(__("CAOS has added the Google Analytics tracking code to the $new_position of your theme.", 'host-analyticsjs-local'), false, 'success');
                    break;
            }
        }

        return $new_position;
    }
}