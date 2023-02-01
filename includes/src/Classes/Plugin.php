<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */
namespace MegaOptim\RapidCache\Classes;

use MegaOptim\RapidCache\Traits;

/**
 * Rapid Cache Plugin.
 *
 * @since 1.0.0
 */
class Plugin extends AbsBaseAp
{
    /*[.build.php-auto-generate-use-Traits]*/
    use Traits\Plugin\ActionUtils;
    use Traits\Plugin\AdminBarUtils;
	use Traits\Plugin\AutoCacheUtils;
    use Traits\Plugin\BbPressUtils;
    use Traits\Plugin\CleanupUtils;
    use Traits\Plugin\CondUtils;
    use Traits\Plugin\CronUtils;
    use Traits\Plugin\DbUtils;
    use Traits\Plugin\DirUtils;
    use Traits\Plugin\HtaccessUtils;
    use Traits\Plugin\InstallUtils;
    use Traits\Plugin\MenuPageUtils;
    use Traits\Plugin\NoticeUtils;
    use Traits\Plugin\OptionUtils;
    use Traits\Plugin\PostUtils;
    use Traits\Plugin\UrlUtils;
    use Traits\Plugin\UserUtils;
    use Traits\Plugin\WcpAuthorUtils;
    use Traits\Plugin\WcpCommentUtils;
    use Traits\Plugin\WcpDateArchiveUtils;
    use Traits\Plugin\WcpFeedUtils;
    use Traits\Plugin\WcpHomeBlogUtils;
    use Traits\Plugin\WcpJetpackUtils;
    use Traits\Plugin\WcpOpcacheUtils;
    use Traits\Plugin\WcpPluginUtils;
    use Traits\Plugin\WcpPostTypeUtils;
    use Traits\Plugin\WcpPostUtils;
    use Traits\Plugin\WcpSettingUtils;
    use Traits\Plugin\WcpSitemapUtils;
    use Traits\Plugin\WcpTermUtils;
	use Traits\Plugin\WcpTransientUtils;
    use Traits\Plugin\WcpUpdaterUtils;
	use Traits\Plugin\WcpUrlUtils;
	use Traits\Plugin\WcpUtils;
    use Traits\Plugin\WcpWooCommerceUtils;
    /*[/.build.php-auto-generate-use-Traits]*/

    /**
     * Enable plugin hooks?
     *
     * @since 1.0.0
     *
     * @type bool If `FALSE`, run without hooks.
     */
    public $enable_hooks = true;

    /**
     * Default options.
     *
     * @since 1.0.0
     *
     * @type array Default options.
     */
    public $default_options = [];

    /**
     * Configured options.
     *
     * @since 1.0.0
     *
     * @type array Configured options.
     */
    public $options = [];

    /**
     * WordPress capability.
     *
     * @since 1.0.0
     *
     * @type string WordPress capability.
     */
    public $cap = 'activate_plugins';

    /**
     * WordPress capability.
     *
     * @since 1.0.0
     *
     * @type string WordPress capability.
     */
    public $update_cap = 'update_plugins';

    /**
     * WordPress capability.
     *
     * @since 1.0.0
     *
     * @type string WordPress capability.
     */
    public $network_cap = 'manage_network_plugins';

    /**
     * WordPress capability.
     *
     * @since 1.0.0
     *
     * @type string WordPress capability.
     */
    public $uninstall_cap = 'delete_plugins';

    /**
     * Cache directory.
     *
     * @since 1.0.0
     *
     * @type string Cache directory; relative to the configured base directory.
     */
    public $cache_sub_dir = 'cache';

    /**
     * Plugin constructor.
     *
     * @since 1.0.0
     *
     * @param bool $enable_hooks Defaults to `TRUE`.
     */
    public function __construct($enable_hooks = true)
    {
        parent::__construct();

        /* -------------------------------------------------------------- */
        if (!($this->enable_hooks = (bool) $enable_hooks)) {
            return; // Stop here; construct without hooks.
        }
        /* -------------------------------------------------------------- */

        add_action('plugins_loaded', [$this, 'setup']);
        register_activation_hook(MEGAOPTIM_RAPID_CACHE_PLUGIN_FILE, [$this, 'activate']);
        register_deactivation_hook(MEGAOPTIM_RAPID_CACHE_PLUGIN_FILE, [$this, 'deactivate']);
    }

    /**
     * Plugin Setup.
     *
     * @since 1.0.0
     */
    public function setup()
    {
        if (!is_null($setup = &$this->cacheKey(__FUNCTION__))) {
            return; // Already setup.
        }
        $setup = -1; // Flag as having been setup.

        if ($this->enable_hooks) {
            $this->doWpAction('before_'.MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_'.__FUNCTION__, get_defined_vars());
        }
        /* -------------------------------------------------------------- */

        load_plugin_textdomain(MEGAOPTIM_RAPID_CACHE_SLUG); // Text domain.

        $this->default_options = [
            /* Core/systematic plugin options. */

            'version'  => MEGAOPTIM_RAPID_CACHE_VERSION,
            'welcomed' => '0', // `0|1` welcomed yet?

            'crons_setup'                             => '0', // A timestamp when last set up.
            'crons_setup_on_namespace'                => '', // The namespace on which they were set up.
            'crons_setup_with_cache_cleanup_schedule' => '', // The cleanup schedule selected by site owner during last setup.
            'crons_setup_on_wp_with_schedules'        => '', // A sha1 hash of `wp_get_schedules()`

            /* Primary switch; enable? */

            'enable' => '0', // `0|1`.

            /* Related to debugging. */

            'debugging_enable' => '1',
            // `0|1|2` // 2 indicates greater debugging detail.

            /* Related to cache directory. */

            'base_dir'                                     => 'cache/rapid-cache', // Relative to `WP_CONTENT_DIR`.
            'cache_max_age'                                => '7 days', // `strtotime()` compatible.
            'cache_max_age_disable_if_load_average_is_gte' => '', // Load average; server-specific.
            'cache_cleanup_schedule'                       => 'hourly', // `every15m`, `hourly`, `twicedaily`, `daily`

            /* Related to cache clearing. */

            'cache_clear_opcache_enable'    => '0', // `0|1`.
            'cache_clear_urls'              => '', // Line-delimited list of URLs.
            'cache_clear_transients_enable' => '0', // `0|1`

            'cache_clear_xml_feeds_enable' => '1', // `0|1`.

            'cache_clear_xml_sitemaps_enable'  => '1', // `0|1`.
            'cache_clear_xml_sitemap_patterns' => '/sitemap**.xml',
            // Empty string or line-delimited patterns.

            'cache_clear_home_page_enable'  => '1', // `0|1`.
            'cache_clear_posts_page_enable' => '1', // `0|1`.

            'cache_clear_custom_post_type_enable' => '1', // `0|1`.
            'cache_clear_author_page_enable'      => '1', // `0|1`.

            'cache_clear_term_category_enable' => '1', // `0|1`.
            'cache_clear_term_post_tag_enable' => '1', // `0|1`.
            'cache_clear_term_other_enable'    => '1', // `0|1`.

            'cache_clear_date_archives_enable' => '1', // `0|1|2|3`.
            // 0 = No, don't clear any associated Date archive views.
            // 1 = Yes, if any single Post is cleared/reset, also clear the associated Date archive views.
            // 2 = Yes, but only clear the associated Day and Month Date archive views.
            // 3 = Yes, but only clear the associated Day Date archive view.

            /* Misc. cache behaviors. */

            'allow_client_side_cache'           => '0', // `0|1`.
            'when_logged_in'                    => '0', // `0|1|postload`.
            'get_requests'                      => '0', // `0|1`.
            'ignore_get_request_vars'           => 'utm_*', // Empty string or line-delimited patterns.
            'feeds_enable'                      => '0', // `0|1`.
            'cache_404_requests'                => '0', // `0|1`.
            'cache_nonce_values'                => '0', // `0|1`.
            'cache_nonce_values_when_logged_in' => '1', // `0|1`.

            /* Related to exclusions. */

            'exclude_hosts'            => '', // Empty string or line-delimited patterns.
            'exclude_uris'             => '', // Empty string or line-delimited patterns.
            'exclude_client_side_uris' => '', // Line-delimited list of URIs.
            'exclude_refs'             => '', // Empty string or line-delimited patterns.
            'exclude_agents'           => 'w3c_validator', // Empty string or line-delimited patterns.

            /* Related to version salt. */

            'version_salt' => '', // Any string value as a cache path component.

            // This should be set to a `+` delimited string containing any of these tokens: `os.name + device.type + browser.name + browser.version.major`.
            // There is an additional token (`browser.version`) that contains both the major and minor versions, but this token is not recommended due to many permutations.
            // There is an additional token (`device.is_mobile`) that can be used stand-alone; i.e., to indicate that being mobile is the only factor worth considering.
            'mobile_adaptive_salt'        => 'os.name + device.type + browser.name',
            'mobile_adaptive_salt_enable' => '0', // `0|1` Enable the mobile adaptive salt?
            'ua_info_last_data_update'    => '0', // Timestamp.

            /* Related to auto-cache engine. */

            'auto_cache_enable'          => '0', // `0|1`.
            'auto_cache_max_time'        => '900', // In seconds.
            'auto_cache_delay'           => '500', // In milliseconds.
            'auto_cache_sitemap_url'     => 'sitemap.xml', // Relative to `site_url()`.
            'auto_cache_ms_children_too' => '0', // `0|1`. Try child blogs too?
            'auto_cache_other_urls'      => '', // A line-delimited list of any other URLs.
            'auto_cache_user_agent'      => 'WordPress',

            /* Related to .htaccess tweaks. */

            'htaccess_browser_caching_enable'      => '0', // `0|1`; enable browser caching?
            'htaccess_gzip_enable'                 => '0', // `0|1`; enable GZIP compression?

            /* Related to uninstallation routines. */

            'uninstall_on_deletion' => '0', // `0|1`.
        ];
        $this->default_options = $this->applyWpFilters(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_default_options', $this->default_options);
        $this->options         = $this->getOptions(); // Filters, validates, and returns plugin options.

        $this->cap           = $this->applyWpFilters(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_cap', $this->cap);
        $this->update_cap    = $this->applyWpFilters(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_update_cap', $this->update_cap);
        $this->network_cap   = $this->applyWpFilters(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_network_cap', $this->network_cap);
        $this->uninstall_cap = $this->applyWpFilters(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_uninstall_cap', $this->uninstall_cap);
        
        /* -------------------------------------------------------------- */

        if (!$this->enable_hooks || strcasecmp(PHP_SAPI, 'cli') === 0) {
            return; // Stop here; setup without hooks.
        }
        /* -------------------------------------------------------------- */

        add_action('init', [$this, 'checkVersion']);
        add_action('init', [$this, 'checkAdvancedCache']);
        add_action('init', [$this, 'checkBlogPaths']);
        add_action('init', [$this, 'checkCronSetup'], PHP_INT_MAX);

        add_action('wp_loaded', [$this, 'actions']);

	    add_action('admin_init', [$this, 'autoCacheMaybeClearPrimaryXmlSitemapError']);
	    add_action('admin_init', [$this, 'autoCacheMaybeClearPhpReqsError']);

        add_action('admin_bar_menu', [$this, 'adminBarMenu']);
        add_action('wp_head', [$this, 'adminBarMetaTags'], 0);
        add_action('wp_enqueue_scripts', [$this, 'adminBarStyles']);
        add_action('wp_enqueue_scripts', [$this, 'adminBarScripts']);

        add_action('admin_head', [$this, 'adminBarMetaTags'], 0);
        add_action('admin_enqueue_scripts', [$this, 'adminBarStyles']);
        add_action('admin_enqueue_scripts', [$this, 'adminBarScripts']);

        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminStyles']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminScripts']);

        add_action('admin_menu', [$this, 'addMenuPages']);
        add_action('network_admin_menu', [$this, 'addNetworkMenuPages']);

        add_action('all_admin_notices', [$this, 'allAdminNotices']);

        add_filter('plugin_action_links_'.plugin_basename(MEGAOPTIM_RAPID_CACHE_PLUGIN_FILE), [$this, 'addSettingsLink']);

        add_filter('enable_live_network_counts', [$this, 'updateBlogPaths']);

        add_action('admin_init', [$this, 'autoClearCacheOnSettingChanges']);

        add_action('safecss_save_pre', [$this, 'autoClearCacheOnJetpackCustomCss'], 10, 1);

        add_action('activated_plugin', [$this, 'autoClearOnPluginActivationDeactivation'], 10, 2);
        add_action('deactivated_plugin', [$this, 'autoClearOnPluginActivationDeactivation'], 10, 2);

        add_action('upgrader_process_complete', [$this, 'autoClearOnUpgraderProcessComplete'], 10, 2);
        add_action('upgrader_process_complete', [$this, 'wipeOpcacheByForce'], PHP_INT_MAX);

        add_action('switch_theme', [$this, 'autoClearCache']);
        add_action('wp_create_nav_menu', [$this, 'autoClearCache']);
        add_action('wp_update_nav_menu', [$this, 'autoClearCache']);
        add_action('wp_delete_nav_menu', [$this, 'autoClearCache']);
        add_action('update_option_sidebars_widgets', [$this, 'autoClearCache']);

        add_action('save_post', [$this, 'autoClearPostCache']);
        add_action('delete_post', [$this, 'autoClearPostCache']);
        add_action('clean_post_cache', [$this, 'autoClearPostCache']);
        add_action('post_updated', [$this, 'autoClearAuthorPageCache'], 10, 3);
        add_action('pre_post_update', [$this, 'autoClearPostCacheTransition'], 10, 2);

        add_action('woocommerce_product_set_stock', [$this, 'autoClearPostCacheOnWooCommerceSetStock'], 10, 1);
        add_action('woocommerce_product_set_stock_status', [$this, 'autoClearPostCacheOnWooCommerceSetStockStatus'], 10, 1);
        add_action('update_option_comment_mail_options', [$this, 'autoClearCache']);

        add_action('added_term_relationship', [$this, 'autoClearPostTermsCache'], 10, 1);
        add_action('delete_term_relationships', [$this, 'autoClearPostTermsCache'], 10, 1);

        add_action('trackback_post', [$this, 'autoClearCommentPostCache']);
        add_action('pingback_post', [$this, 'autoClearCommentPostCache']);
        add_action('comment_post', [$this, 'autoClearCommentPostCache']);
        add_action('transition_comment_status', [$this, 'autoClearCommentPostCacheTransition'], 10, 3);

        add_action('create_term', [$this, 'autoClearCache']);
        add_action('edit_terms', [$this, 'autoClearCache']);
        add_action('delete_term', [$this, 'autoClearCache']);

        add_action('add_link', [$this, 'autoClearCache']);
        add_action('edit_link', [$this, 'autoClearCache']);
        add_action('delete_link', [$this, 'autoClearCache']);

        add_action('delete_user', [$this, 'autoClearAuthorPageCacheOnUserDeletion'], 10, 2);
        add_action('remove_user_from_blog', [$this, 'autoClearAuthorPageCacheOnUserDeletion'], 10, 1);

        if ($this->options['enable'] && $this->applyWpFilters(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_disable_akismet_comment_nonce', true)) {
            add_filter('akismet_comment_nonce', function () {
                return 'disabled-by-'.MEGAOPTIM_RAPID_CACHE_SLUG; // MUST return a string literal that is not 'true' or '' (an empty string). See <http://bit.ly/1YItpdE>
            }); // See also why the Akismet nonce should be disabled: <http://jas.xyz/1R23f5c>
        }

        /* -------------------------------------------------------------- */

        if (!is_multisite() || is_main_site()) { // Main site only.
            add_filter('cron_schedules', [$this, 'extendCronSchedules']);
            add_action('_cron_'.MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_cleanup', [$this, 'cleanupCache']);
	        add_action('_cron_'.MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_auto_cache', [$this, 'autoCache']);
        }

        /* -------------------------------------------------------------- */
        $this->doWpAction('after_'.MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_'.__FUNCTION__, get_defined_vars());
        $this->doWpAction(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_'.__FUNCTION__.'_complete', get_defined_vars());
    }
}
