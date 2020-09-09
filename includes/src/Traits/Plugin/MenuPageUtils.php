<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */
namespace MegaOptim\RapidCache\Traits\Plugin;

use MegaOptim\RapidCache\Classes;

trait MenuPageUtils
{
    /**
     * Adds CSS for administrative menu pages.
     *
     * @since 1.0.0
     *
     * @attaches-to `admin_enqueue_scripts` hook.
     */
    public function enqueueAdminStyles()
    {
        if (empty($_GET['page']) || mb_strpos($_GET['page'], MEGAOPTIM_RAPID_CACHE_GLOBAL_NS) !== 0) {
            return; // NOT a plugin page in the administrative area.
        }
        $deps = []; // Plugin dependencies.

        wp_enqueue_style(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS, $this->url('/assets/css/menu-pages.min.css'), $deps, MEGAOPTIM_RAPID_CACHE_VERSION, 'all');
    }

    /**
     * Adds JS for administrative menu pages.
     *
     * @since 1.0.0
     *
     * @attaches-to `admin_enqueue_scripts` hook.
     */
    public function enqueueAdminScripts()
    {
        if (empty($_GET['page']) || mb_strpos($_GET['page'], MEGAOPTIM_RAPID_CACHE_GLOBAL_NS) !== 0) {
            return; // NOT a plugin page in the administrative area.
        }
        $deps = ['jquery']; // Plugin dependencies.

        if (MEGAOPTIM_RAPID_CACHE_IS_PRO) {
            $deps[] = 'chartjs'; // Add ChartJS dependency.
            wp_enqueue_script('chartjs', set_url_scheme('//cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js'), [], null, true);
        }
        wp_enqueue_script(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS, $this->url('/assets/js/menu-pages.min.js'), $deps, MEGAOPTIM_RAPID_CACHE_VERSION, true);
        wp_localize_script(
            MEGAOPTIM_RAPID_CACHE_GLOBAL_NS,
            MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_menu_page_vars',
            [
                '_wpnonce'                 => wp_create_nonce(),
                'isMultisite'              => is_multisite(), // Network?
                'currentUserHasCap'        => current_user_can($this->cap),
                'currentUserHasNetworkCap' => current_user_can($this->network_cap),
                'htmlCompressorEnabled'    => (bool) $this->options['htmlc_enable'],
                'ajaxURL'                  => site_url('/wp-load.php', is_ssl() ? 'https' : 'http'),
                'emptyStatsCountsImageUrl' => $this->url('/assets/images/stats-fc-empty.png'),
                'emptyStatsFilesImageUrl'  => $this->url('/assets/images/stats-fs-empty.png'),
                'i18n'                     => [
                    'name'                    => MEGAOPTIM_RAPID_CACHE_NAME,
                    'perSymbol'               => __('%', 'rapid-cache'),
                    'file'                    => __('file', 'rapid-cache'),
                    'files'                   => __('files', 'rapid-cache'),
                    'pageCache'               => __('Page Cache', 'rapid-cache'),
                    'htmlCompressor'          => __('HTML Compressor', 'rapid-cache'),
                    'currentTotal'            => __('Current Total', 'rapid-cache'),
                    'currentSite'             => __('Current Site', 'rapid-cache'),
                    'xDayHigh'                => __('%s Day High', 'rapid-cache'),
                    'mobileAdaptiveSaltError' => __('Invalid Mobile-Adaptive Tokens. This field must contain one or more of the listed Tokens (separated by a + sign). Please use Tokens only, NOT string literals.', 'rapid-cache'),
                ],
            ]
        );
    }

    /**
     * Creates network admin menu pages.
     *
     * @since 1.0.0
     *
     * @attaches-to `network_admin_menu` hook.
     */
    public function addNetworkMenuPages()
    {
        if (!is_multisite()) {
            return; // Not applicable.
        }
        $icon = file_get_contents(MEGAOPTIM_RAPID_CACHE_PATH.'assets/images/inline-icon.svg');
        $icon = 'data:image/svg+xml;base64,'.base64_encode($this->colorSvgMenuIcon($icon));

        add_menu_page(MEGAOPTIM_RAPID_CACHE_NAME.(MEGAOPTIM_RAPID_CACHE_IS_PRO ? ' Pro' : ''), MEGAOPTIM_RAPID_CACHE_NAME.(MEGAOPTIM_RAPID_CACHE_IS_PRO ? ' Pro' : ''), $this->network_cap, MEGAOPTIM_RAPID_CACHE_GLOBAL_NS, [$this, 'menuPageOptions'], $icon);
        add_submenu_page(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS, __('Plugin Options', 'rapid-cache'), __('Plugin Options', 'rapid-cache'), $this->network_cap, MEGAOPTIM_RAPID_CACHE_GLOBAL_NS, [$this, 'menuPageOptions']);

        
    }

    /**
     * Creates admin menu pages.
     *
     * @since 1.0.0
     *
     * @attaches-to `admin_menu` hook.
     */
    public function addMenuPages()
    {
        if (is_multisite()) {
            return; // Multisite networks MUST use network admin area.
        }
        $icon = file_get_contents(MEGAOPTIM_RAPID_CACHE_PATH.'assets/images/inline-icon.svg');
        $icon = 'data:image/svg+xml;base64,'.base64_encode($this->colorSvgMenuIcon($icon));

        add_menu_page(MEGAOPTIM_RAPID_CACHE_NAME.(MEGAOPTIM_RAPID_CACHE_IS_PRO ? ' Pro' : ''), MEGAOPTIM_RAPID_CACHE_NAME.(MEGAOPTIM_RAPID_CACHE_IS_PRO ? ' Pro' : ''), $this->cap, MEGAOPTIM_RAPID_CACHE_GLOBAL_NS, [$this, 'menuPageOptions'], $icon);
        add_submenu_page(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS, __('Plugin Options', 'rapid-cache'), __('Plugin Options', 'rapid-cache'), $this->cap, MEGAOPTIM_RAPID_CACHE_GLOBAL_NS, [$this, 'menuPageOptions']);

        
    }

    /**
     * Adds link(s) to Rapid Cache row on the WP plugins page.
     *
     * @since 1.0.0
     *
     * @attaches-to `plugin_action_links_'.plugin_basename(MEGAOPTIM_RAPID_CACHE_PLUGIN_FILE)` filter.
     *
     * @param array $links An array of the existing links provided by WordPress.
     *
     * @return array Revised array of links.
     */
    public function addSettingsLink($links)
    {
        if (is_multisite() && !is_network_admin()) {
            return $links;
        }

        $links[] = '<a href="'.esc_attr(add_query_arg(urlencode_deep(['page' => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS]), self_admin_url('/admin.php'))).'">'.__('Settings', 'rapid-cache').'</a>';

        return $links;
    }

    /**
     * Fills menu page inline SVG icon color.
     *
     * @since 1.0.0
     *
     * @param string $svg Inline SVG icon markup.
     *
     * @return string Inline SVG icon markup.
     */
    public function colorSvgMenuIcon($svg)
    {
        if (!($color = get_user_option('admin_color'))) {
            $color = 'fresh'; // Default color scheme.
        }
        if (empty($this->wp_admin_icon_colors[$color])) {
            return $svg; // Not possible.
        }
        $icon_colors         = $this->wp_admin_icon_colors[$color];
        $use_icon_fill_color = $icon_colors['base']; // Default base.

        $current_pagenow = !empty($GLOBALS['pagenow']) ? $GLOBALS['pagenow'] : '';
        $current_page    = !empty($_REQUEST['page']) ? $_REQUEST['page'] : '';

        if (mb_strpos($current_pagenow, MEGAOPTIM_RAPID_CACHE_GLOBAL_NS) === 0 || mb_strpos($current_page, MEGAOPTIM_RAPID_CACHE_GLOBAL_NS) === 0) {
            $use_icon_fill_color = $icon_colors['current'];
        }
        return str_replace(' fill="currentColor"', ' fill="'.esc_attr($use_icon_fill_color).'"', $svg);
    }

    /**
     * Loads the admin menu page options.
     *
     * @since 1.0.0
     */
    public function menuPageOptions()
    {
        new Classes\MenuPage('options');
    }


    /**
     * WordPress admin icon color schemes.
     *
     * @since 1.0.0
     *
     * @type array WP admin icon colors.
     *
     * @note These must be hard-coded, because they don't become available
     *    in core until `admin_init`; i.e., too late for `admin_menu`.
     */
    public $wp_admin_icon_colors = [
        'fresh'     => ['base' => '#999999', 'focus' => '#2EA2CC', 'current' => '#FFFFFF'],
        'light'     => ['base' => '#999999', 'focus' => '#CCCCCC', 'current' => '#CCCCCC'],
        'blue'      => ['base' => '#E5F8FF', 'focus' => '#FFFFFF', 'current' => '#FFFFFF'],
        'midnight'  => ['base' => '#F1F2F3', 'focus' => '#FFFFFF', 'current' => '#FFFFFF'],
        'sunrise'   => ['base' => '#F3F1F1', 'focus' => '#FFFFFF', 'current' => '#FFFFFF'],
        'ectoplasm' => ['base' => '#ECE6F6', 'focus' => '#FFFFFF', 'current' => '#FFFFFF'],
        'ocean'     => ['base' => '#F2FCFF', 'focus' => '#FFFFFF', 'current' => '#FFFFFF'],
        'coffee'    => ['base' => '#F3F2F1', 'focus' => '#FFFFFF', 'current' => '#FFFFFF'],
    ];

    /**
     * On a specific menu page?
     *
     * @since 1.0.0.
     *
     * @param string $which Which page to check; may contain wildcards.
     *
     * @return bool True if is the menu page.
     */
    public function isMenuPage($which)
    {
        if (!($which = trim((string) $which))) {
            return false; // Empty.
        }
        if (!is_admin()) {
            return false;
        }
        $page = $pagenow = ''; // Initialize.

        if (!empty($_REQUEST['page'])) {
            $page = (string) $_REQUEST['page'];
        }
        if (!empty($GLOBALS['pagenow'])) {
            $pagenow = (string) $GLOBALS['pagenow'];
        }
        if ($page && fnmatch($which, $page, FNM_CASEFOLD)) {
            return true; // Wildcard match.
        }
        if ($pagenow && fnmatch($which, $pagenow, FNM_CASEFOLD)) {
            return true; // Wildcard match.
        }
        return false; // Nope.
    }
}
