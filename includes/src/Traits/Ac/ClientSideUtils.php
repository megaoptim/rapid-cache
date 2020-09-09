<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */
namespace MegaOptim\RapidCache\Traits\Ac;

use MegaOptim\RapidCache\Classes;

trait ClientSideUtils
{
    /**
     * Sends no-cache headers.
     *
     * @since 1.0.0
     */
    public function maybeStopBrowserCaching()
    {
        $short_name_lc = mb_strtolower(MEGAOPTIM_RAPID_CACHE_SHORT_NAME); // Needed below.

        switch (defined('RAPID_CACHE_ALLOW_CLIENT_SIDE_CACHE') ? (bool) RAPID_CACHE_ALLOW_CLIENT_SIDE_CACHE : false) {
            case true: // If global config allows; check exclusions.

                if (isset($_GET[$short_name_lc.'ABC'])) {
                    if (!filter_var($_GET[$short_name_lc.'ABC'], FILTER_VALIDATE_BOOLEAN)) {
                        return $this->sendNoCacheHeaders(); // Disallow.
                    } // Else, allow client-side caching because `ABC` is a true-ish value.
                } elseif (RAPID_CACHE_EXCLUDE_CLIENT_SIDE_URIS && (empty($_SERVER['REQUEST_URI']) || preg_match(RAPID_CACHE_EXCLUDE_CLIENT_SIDE_URIS, $_SERVER['REQUEST_URI']))) {
                    return $this->sendNoCacheHeaders(); // Disallow.
                }
                return; // Allow client-side caching; default behavior in this mode.

            case false: // Global config disallows; check inclusions.

                if (isset($_GET[$short_name_lc.'ABC'])) {
                    if (filter_var($_GET[$short_name_lc.'ABC'], FILTER_VALIDATE_BOOLEAN)) {
                        return; // Allow, because `ABC` is a false-ish value.
                    } // Else, disallow client-side caching because `ABC` is a true-ish value.
                }
                return $this->sendNoCacheHeaders(); // Disallow; default behavior in this mode.
        }
    }
}
