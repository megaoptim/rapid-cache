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

trait WcpJetpackUtils
{
    /**
     * Automatically clears all cache files for current blog when JetPack Custom CSS is saved.
     *
     * @since 1.0.0
     *
     * @attaches-to `safecss_save_pre` hook.
     *
     * @param array $args Args passed in by hook.
     */
    public function autoClearCacheOnJetpackCustomCss($args)
    {
        $counter = 0; // Initialize.

        if (!is_null($done = &$this->cacheKey('autoClearCacheOnJetpackCustomCss', $args))) {
            return $counter; // Already did this.
        }
        $done = true; // Flag as having been done.

        if (empty($args['is_preview']) && class_exists('\\Jetpack')) {
            $counter += $this->autoClearCache();
        }
    }
}
