<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */

namespace MegaOptim\RapidCache\Classes;

/**
 * Conflicts.
 *
 * @since 1.0.0
 */
class Conflicts
{
    /**
     * List of conflicting plugins
     *
     * @return string[]
     */
    public static function conflictingPlugins()
    {
        return array(
            'css-js-booster',
            'force-gzip',
            'wp-super-cache',
            'w3-total-cache',
            'hyper-cache',
            'comet-cache',
            'comet-cache-pro',
            'wp-rocket',
            'wp-fastest-cache',
            'cachify',
            'simple-cache',
            'cache-enabler',
            'plainview-activity-monitor',
        );
    }

    /**
     * Check.
     *
     * @since 1.0.0
     */
    public static function check()
    {
        if (static::doCheck()) {
            static::maybeEnqueueNotice();
        }

        return $GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_conflicting_plugin'];
    }

    /**
     * Perform check.
     *
     * @since 1.0.0
     */
    protected static function doCheck()
    {
        if ( ! empty($GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_conflicting_plugin'])) {
            return $GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_conflicting_plugin'];
        }
        $conflicting_plugin_slugs = self::conflictingPlugins();
        $active_plugins           = (array) get_option('active_plugins', []);
        $active_sitewide_plugins  = is_multisite() ? array_keys((array) get_site_option('active_sitewide_plugins', [])) : [];
        $active_plugins           = array_unique(array_merge($active_plugins, $active_sitewide_plugins));

        foreach ($active_plugins as $_active_plugin_basename) {
            if ( ! ($_active_plugin_slug = strstr($_active_plugin_basename, '/', true))) {
                continue; // Nothing to check in this case.
            }
            if (in_array($_active_plugin_slug, $conflicting_plugin_slugs, true)) {
                return $GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_conflicting_plugin'] = $_active_plugin_slug;
            }
        }

        return $GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_conflicting_plugin'] = ''; // i.e. No conflicting plugins.
    }

    /**
     * Maybe enqueue dashboard notice.
     *
     * @since 1.0.0
     */
    protected static function maybeEnqueueNotice()
    {
        if ( ! empty($GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_uninstalling'])) {
            return; // Not when uninstalling.
        }
        if (empty($GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_conflicting_plugin'])) {
            return; // Not conflicts.
        }
        add_action('all_admin_notices', function () {
            if ( ! empty($GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_conflicting_plugin_lite_pro'])) {
                return; // Already did this in one plugin or the other.
            }
            $construct_name = function ($slug_or_ns) {
                $name = trim(mb_strtolower((string) $slug_or_ns));
                $name = preg_replace('/[_\-]+(?:lite|pro)$/u', '', $name);
                $name = preg_replace('/[^a-z0-9]/u', ' ', $name);
                $name = str_replace('cache', 'Cache', ucwords($name));

                return $name; // e.g., `x-cache` becomes `X Cache`.
            };

            $this_plugin_name        = MEGAOPTIM_RAPID_CACHE_NAME;
            $conflicting_plugin_name = $construct_name($GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_conflicting_plugin']);

            echo '<div class="error">'.// Error notice.
                 '   <p>'.// Running one or more conflicting plugins at the same time.
                 '      '.sprintf(__('<strong>%1$s</strong> is NOT running. A conflicting plugin, <strong>%2$s</strong>, is currently active. Please deactivate the %2$s plugin to clear this message.',
                    'rapid-cache'), esc_html($this_plugin_name), esc_html($conflicting_plugin_name)).
                 '   </p>'.
                 '</div>';
        });
    }
}
