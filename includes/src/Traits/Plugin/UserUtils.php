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

trait UserUtils
{
    /**
     * Current user can clear the cache?
     *
     * @since 1.0.0
     *
     * @return bool Current user can clear the cache?
     */
    public function currentUserCanClearCache()
    {
        if (!is_null($can = &$this->cacheKey('currentUserCanClearCache'))) {
            return $can; // Already cached this.
        }
        $is_multisite = is_multisite();

        if (!$is_multisite && current_user_can($this->cap)) {
            return $can = true; // Plugin admin.
        }
        if ($is_multisite && current_user_can($this->network_cap)) {
            return $can = true; // Plugin admin.
        }
        
        return $can = false;
    }

    /**
     * Alias for currentUserCanClearCache().
     *
     * @since 1.0.0
     *
     * @return bool Current user can clear the cache?
     */
    public function currentUserCanWipeCache()
    {
        return call_user_func_array([$this, 'currentUserCanClearCache'], func_get_args());
    }

    /**
     * Current user can clear the opcache?
     *
     * @since 1.0.0
     *
     * @return bool Current user can clear the opcache?
     */
    public function currentUserCanClearOpCache()
    {
        if (!is_null($can = &$this->cacheKey('currentUserCanClearOpCache'))) {
            return $can; // Already cached this.
        }
        $is_multisite = is_multisite();

        if (!$is_multisite && current_user_can($this->cap)) {
            return $can = true; // Plugin admin.
        }
        if ($is_multisite && current_user_can($this->network_cap)) {
            return $can = true; // Plugin admin.
        }
        return $can = false;
    }

    /**
     * Alias for currentUserCanClearOpCache().
     *
     * @since 1.0.0
     *
     * @return bool Current user can clear the opcache?
     */
    public function currentUserCanWipeOpCache()
    {
        return call_user_func_array([$this, 'currentUserCanClearOpCache'], func_get_args());
    }

    /**
     * Current user can clear the CDN cache?
     *
     * @since 1.0.0
     *
     * @return bool Current user can clear the CDN cache?
     */
    public function currentUserCanClearCdnCache()
    {
        if (!is_null($can = &$this->cacheKey('currentUserCanClearCdnCache'))) {
            return $can; // Already cached this.
        }
        $is_multisite = is_multisite();

        if (!$is_multisite && current_user_can($this->cap)) {
            return $can = true; // Plugin admin.
        }
        if ($is_multisite && current_user_can($this->network_cap)) {
            return $can = true; // Plugin admin.
        }
        return $can = false;
    }

    /**
     * Alias for currentUserCanClearCdnCache().
     *
     * @since 1.0.0
     *
     * @return bool Current user can clear the CDN cache?
     */
    public function currentUserCanWipeCdnCache()
    {
        return call_user_func_array([$this, 'currentUserCanClearCdnCache'], func_get_args());
    }

    /**
     * Current user can clear expired transients?
     *
     * @since 1.0.0
     *
     * @return bool Current user can clear expired transients?
     */
    public function currentUserCanClearExpiredTransients()
    {
        if (!is_null($can = &$this->cacheKey('currentUserCanClearExpiredTransients'))) {
            return $can; // Already cached this.
        }
        $is_multisite = is_multisite();

        if (!$is_multisite && current_user_can($this->cap)) {
            return $can = true; // Plugin admin.
        }
        if ($is_multisite && current_user_can($this->network_cap)) {
            return $can = true; // Plugin admin.
        }
        return $can = false;
    }

    /**
     * Alias for currentUserCanClearExpiredTransients().
     *
     * @since 1.0.0
     *
     * @return bool Current user can clear expired transients?
     */
    public function currentUserCanWipeExpiredTransients()
    {
        return call_user_func_array([$this, 'currentUserCanClearExpiredTransients'], func_get_args());
    }

    /**
     * Current user can see stats?
     *
     * @since 1.0.0
     *
     * @return bool Current user can see stats?
     */
    public function currentUserCanSeeStats()
    {
        if (!is_null($can = &$this->cacheKey('currentUserCanSeeStats'))) {
            return $can; // Already cached this.
        }
        $is_multisite = is_multisite();

        if (!$is_multisite && current_user_can($this->cap)) {
            return $can = true; // Plugin admin.
        }
        if ($is_multisite && current_user_can($this->network_cap)) {
            return $can = true; // Plugin admin.
        }
        
        return $can = false;
    }
}
