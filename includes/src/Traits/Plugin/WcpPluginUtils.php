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

trait WcpPluginUtils
{
    /**
     * Automatically wipes/clears on plugin activation/deactivation.
     *
     * @since 1.0.0
     *
     * @attaches-to `activated_plugin` hook.
     * @attaches-to `deactivated_plugin` hook.
     *
     * @param string $plugin Plugin basename.
     * @param bool True if activating|deactivating network-wide. Defaults to boolean `FALSE` in case parameter is not passed to hook.
     *
     * @return int Total files wiped|cleared by this routine (if any).
     *
     * @note Also wipes the PHP OPCache.
     */
    public function autoClearOnPluginActivationDeactivation($plugin, $network_wide = false)
    {
        if (!$this->applyWpFilters(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_auto_clear_on_plugin_activation_deactivation', true)) {
            return 0; // Nothing to do here.
        }

        add_action('shutdown', [$this, 'wipeOpcacheByForce'], PHP_INT_MAX);

        return $this->{($network_wide ? 'autoWipeCache' : 'autoClearCache')}();
    }
}
