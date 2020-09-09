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

trait WcpSitemapUtils
{
    /**
     * Automatically clears cache files related to XML sitemaps.
     *
     * @since 1.0.0
     *
     * @throws \Exception If a clear failure occurs.
     *
     * @return int Total files cleared by this routine (if any).
     *
     * @note Unlike many of the other `auto_` methods, this one is NOT currently
     *    attached to any hooks. However, it is called upon by {@link autoClearPostCache()}.
     */
    public function autoClearXmlSitemapsCache()
    {
        $counter = 0; // Initialize.

        if (!is_null($done = &$this->cacheKey('autoClearXmlSitemapsCache'))) {
            return $counter; // Already did this.
        }
        $done = true; // Flag as having been done.

        if (!$this->options['enable']) {
            return $counter; // Nothing to do.
        }
        if (!$this->options['cache_clear_xml_sitemaps_enable']) {
            return $counter; // Nothing to do.
        }
        if (!$this->options['cache_clear_xml_sitemap_patterns']) {
            return $counter; // Nothing to do.
        }
        if (!is_dir($cache_dir = $this->cacheDir())) {
            return $counter; // Nothing to do.
        }
        if (!($regex_frags = $this->buildHostCachePathRegexFragsFromWcUris($this->options['cache_clear_xml_sitemap_patterns'], ''))) {
            return $counter; // There are no patterns to look for.
        }
        $regex = $this->buildHostCachePathRegex('', '\/'.$regex_frags.'\.');
        $counter += $this->clearFilesFromHostCacheDir($regex);

        if ($counter && is_admin() && (!MEGAOPTIM_RAPID_CACHE_IS_PRO || $this->options['change_notifications_enable'])) {
            $this->enqueueNotice(sprintf(__('Found %1$s in the cache for XML sitemaps; auto-clearing.', 'rapid-cache'), esc_html($this->i18nFiles($counter))), ['combinable' => true]);
        }
        return $counter;
    }
}
