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

trait WcpOpcacheUtils
{
    /**
     * Wipe (i.e., reset) OPCache.
     *
     * @since 1.0.0
     *
     * @param bool  $manually True if wiping is done manually.
     * @param bool  $maybe    Defaults to a true value.
     * @param array $files    Optional; wipe only specific files?
     *
     * @return int Total keys wiped.
     */
    public function wipeOpcache($manually = false, $maybe = true, $files = [])
    {
        $counter = 0; // Initialize counter.
	    if ($maybe && !$this->options['cache_clear_opcache_enable']) {
		    return $counter; // Not enabled at this time.
	    }
        if (!$this->functionIsPossible('opcache_reset')) {
            return $counter; // Not possible.
        }
        if (!($status = $this->sysOpcacheStatus())) {
            return $counter; // Not possible.
        }
        if (empty($status->opcache_enabled)) {
            return $counter; // Not necessary.
        }
        if (empty($status->opcache_statistics->num_cached_keys)) {
            return $counter; // Not possible.
        }
        if ($files) { // Specific files?
            foreach ($files as $_file) {
                $counter += (int) opcache_invalidate($_file, true);
            } // unset($_file); // Housekeeping.
        } elseif (opcache_reset()) { // True if a reset occurs.
            $counter += $status->opcache_statistics->num_cached_keys;
        }
        return $counter;
    }

    /**
     * Clear (i.e., reset) OPCache.
     *
     * @since 1.0.0
     *
     * @param bool $manually True if clearing is done manually.
     * @param bool $maybe    Defaults to a true value.
     *
     * @return int Total keys cleared.
     */
    public function clearOpcache($manually = false, $maybe = true)
    {
        if (!is_multisite() || is_main_site() || current_user_can($this->network_cap)) {
            return $this->wipeOpcache($manually, $maybe);
        }
        return 0; // Not applicable.
    }

    /**
     * Wipe the Opcache (by force).
     *
     * @since 1.0.0
     *
     * @return int Total keys cleared.
     */
    public function wipeOpcacheByForce()
    {
        return $this->wipeOpcache(false, false);
    }

    /**
     * Clear AC class file from Opcache (by force).
     *
     * @since 1.0.0
     *
     * @return int Total keys cleared.
     */
    public function clearAcDropinFromOpcacheByForce()
    {
        return $this->wipeOpcache(false, false, [WP_CONTENT_DIR.'/advanced-cache.php']);
    }
}
