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

trait WcpPostTypeUtils
{
    /**
     * Automatically clears cache files for a custom post type archive view.
     *
     * @since 1.0.0
     *
     * @param int $post_id A WordPress post ID.
     *
     * @throws \Exception If a clear failure occurs.
     *
     * @return int Total files cleared by this routine (if any).
     *
     * @note Unlike many of the other `auto_` methods, this one is NOT currently
     *    attached to any hooks. However, it is called upon by {@link autoClearPostCache()}.
     */
    public function autoClearCustomPostTypeArchiveCache($post_id)
    {
        $counter = 0; // Initialize.

        if (!($post_id = (integer) $post_id)) {
            return $counter; // Nothing to do.
        }
        if (!is_null($done = &$this->cacheKey('autoClearCustomPostTypeArchiveCache', $post_id))) {
            return $counter; // Already did this.
        }
        $done = true; // Flag as having been done.

        if (!$this->options['enable']) {
            return $counter; // Nothing to do.
        }
        if (!$this->options['cache_clear_custom_post_type_enable']) {
            return $counter; // Nothing to do.
        }
        if (!is_dir($cache_dir = $this->cacheDir())) {
            return $counter; // Nothing to do.
        }
        if (!($post_type = get_post_type($post_id))) {
            return $counter; // Nothing to do.
        }
        if (!($all_custom_post_types = get_post_types(['_builtin' => false]))) {
            return $counter; // No custom post types.
        }
        if (!in_array($post_type, array_keys($all_custom_post_types), true)) {
            return $counter; // This is NOT a custom post type.
        }
        if (!($custom_post_type = get_post_type_object($post_type))) {
            return $counter; // Unable to retrieve post type.
        }
        if (empty($custom_post_type->labels->name) || !($custom_post_type_name = $custom_post_type->labels->name)) {
            $custom_post_type_name = __('Untitled', 'rapid-cache');
        }
        if (!($custom_post_type_archive_link = get_post_type_archive_link($post_type))) {
            return $counter; // Nothing to do; no link to work from in this case.
        }
        $regex = $this->buildHostCachePathRegex($custom_post_type_archive_link);
        $counter += $this->clearFilesFromHostCacheDir($regex);

	    if ($counter && is_admin()) {
            $this->enqueueNotice(sprintf(__('Found %1$s in the cache for Custom Post Type: <code>%2$s</code>; auto-clearing.', 'rapid-cache'), esc_html($this->i18nFiles($counter)), esc_html($custom_post_type_name)), ['combinable' => true]);
        }
        $counter += $this->autoClearXmlFeedsCache('custom-post-type', $post_id);

        return $counter;
    }
}
