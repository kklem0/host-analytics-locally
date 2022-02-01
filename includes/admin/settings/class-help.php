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
 * @url      : https://ffw.press/wordpress/caos/
 * @copyright: (c) 2021 Daan van den Bergh
 * @license  : GPL2v2 or later
 * * * * * * * * * * * * * * * * * * * */

class CAOS_Admin_Settings_Help extends CAOS_Admin_Settings_Builder
{
    /**
     * CAOS_Admin_Settings_Help constructor.
     */
    public function __construct()
    {
        $this->title = __('Help & Documentation', $this->plugin_text_domain);

        // Title
        add_filter('caos_help_content', [$this, 'do_title'], 10);

        // Content
        add_filter('caos_help_content', [$this, 'do_content'], 20);
    }

    public function do_content()
    {
        $utmTags = '?utm_source=caos&utm_medium=plugin&utm_campaign=support_tab';
        $tweetUrl = "https://twitter.com/intent/tweet?text=I+am+using+CAOS+to+speed+up+Google+Analytics+for+@WordPress!+Try+it+for+yourself:&via=Dan0sz&hashtags=GoogleAnalytics,WordPress,Pagespeed,Insights&url=https://wordpress.org/plugins/host-analyticsjs-local/";
?>
        <div class="postbox">
            <div class="content">
                <h2><?= sprintf(__('Thank you for using %s!', $this->plugin_text_domain), apply_filters('caos_settings_page_title', 'CAOS')); ?></h2>
                <p class="about-description">
                    <?= sprintf(__('Need help configuring %s? Please refer to the links below to get you started.', $this->plugin_text_domain), apply_filters('caos_settings_page_title', 'CAOS')); ?>
                </p>
                <div class="column-container">
                    <div class="column">
                        <h3>
                            <?php _e('Need Help?', $this->plugin_text_domain); ?>
                        </h3>
                        <ul>
                            <li><a target="_blank" href="<?= apply_filters('caos_settings_help_quick_start', 'https://docs.ffw.press/article/63-quick-start-caos'); ?>"><i class="dashicons dashicons-controls-play"></i><?= __('Quick Start Guide', $this->plugin_text_domain); ?></a></li>
                            <li><a target="_blank" href="<?= apply_filters('caos_settings_help_user_manual', 'https://docs.ffw.press/category/17-caos'); ?>"><i class="dashicons dashicons-text-page"></i><?= __('User Manual', $this->plugin_text_domain); ?></a></li>
                            <li><a target="_blank" href="<?= apply_filters('caos_settings_help_faq_link', 'https://docs.ffw.press/category/33-caos---troubleshooting'); ?>"><i class="dashicons dashicons-editor-help"></i><?= __('FAQ & Troubleshooting', $this->plugin_text_domain); ?></a></li>
                            <li><a target="_blank" href="<?= apply_filters('caos_settings_help_support_link', 'https://docs.ffw.press/'); ?>"><i class="dashicons dashicons-bell"></i><?= __('Get Support', $this->plugin_text_domain); ?></a></li>
                        </ul>
                    </div>
                    <div class="column">
                        <h3><?= sprintf(__('Support %s & Spread the Word!', $this->plugin_text_domain), apply_filters('caos_settings_page_title', 'CAOS')); ?></h3>
                        <ul>
                            <li><a target="_blank" href="<?= apply_filters('caos_settings_help_review_link', 'https://wordpress.org/support/plugin/host-analyticsjs-local/reviews/?rate=5#new-post'); ?>"><i class="dashicons dashicons-star-filled"></i><?= __('Write a 5-star Review or,', $this->plugin_text_domain); ?></a></li>
                            <li><a target="_blank" href="<?= $tweetUrl; ?>"><i class="dashicons dashicons-twitter"></i><?= __('Tweet about it!', $this->plugin_text_domain); ?></a></li>
                        </ul>
                    </div>
                    <div class="column last">
                        <h3 class="signature"><?= sprintf(__('Coded with %s by', $this->plugin_text_domain), '<i class="dashicons dashicons-heart"></i>'); ?> </h3>
                        <p class="signature">
                            <a target="_blank" title="<?= __('Visit FFW Press', $this->plugin_text_domain); ?>" href="https://ffw.press/wordpress-plugins/"><img class="signature-image" alt="<?= __('Visit FFW Press', $this->plugin_text_domain); ?>" src="<?= plugin_dir_url(OMGF_PLUGIN_FILE) . 'assets/images/logo-color.png'; ?>" /></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        </div>
<?php
    }
}
