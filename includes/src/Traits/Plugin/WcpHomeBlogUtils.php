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

trait WcpHomeBlogUtils
{
    /**
     * Automatically clears cache files for the home page.
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
    public function autoClearHomePageCache()
    {
        $counter = 0; // Initialize.

        if (!is_null($done = &$this->cacheKey('autoClearHomePageCache'))) {
            return $counter; // Already did this.
        }
        $done = true; // Flag as having been done.

        if (!$this->options['enable']) {
            return $counter; // Nothing to do.
        }
        if (!$this->options['cache_clear_home_page_enable']) {
            return $counter; // Nothing to do.
        }
        if (!is_dir($cache_dir = $this->cacheDir())) {
            return $counter; // Nothing to do.
        }
        $regex = $this->buildHostCachePathRegex(home_url('/'));
        $counter += $this->clearFilesFromHostCacheDir($regex);

	    if ($counter && is_admin()) {
            $this->enqueueNotice(sprintf(__('Found %1$s in the cache for the designated "Home Page"; auto-clearing.', 'rapid-cache'), esc_html($this->i18nFiles($counter))), ['combinable' => true]);
        }
        $counter += $this->autoClearXmlFeedsCache('blog');

        return $counter;
    }

    /**
     * Automatically clears cache files for the posts page.
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
    public function autoClearPostsPageCache()
    {
        $counter = 0; // Initialize.

        if (!is_null($done = &$this->cacheKey('autoClearPostsPageCache'))) {
            return $counter; // Already did this.
        }
        $done = true; // Flag as having been done.

        if (!$this->options['enable']) {
            return $counter; // Nothing to do.
        }
        if (!$this->options['cache_clear_posts_page_enable']) {
            return $counter; // Nothing to do.
        }
        if (!is_dir($cache_dir = $this->cacheDir())) {
            return $counter; // Nothing to do.
        }
        $show_on_front  = get_option('show_on_front');
        $page_for_posts = get_option('page_for_posts');

        if (!in_array($show_on_front, ['posts', 'page'], true)) {
            return $counter; // Nothing we can do in this case.
        }
        if ($show_on_front === 'page' && !$page_for_posts) {
            return $counter; // Nothing we can do.
        }
        if ($show_on_front === 'posts') {
            $posts_page = home_url('/');
        } elseif ($show_on_front === 'page') {
            $posts_page = get_permalink($page_for_posts);
        }
        if (empty($posts_page)) {
            return $counter; // Nothing we can do.
        }
        $regex = $this->buildHostCachePathRegex($posts_page);
        $counter += $this->clearFilesFromHostCacheDir($regex);

	    if ($counter && is_admin()) {
            $this->enqueueNotice(sprintf(__('Found %1$s in the cache for the designated "Posts Page"; auto-clearing.', 'rapid-cache'), esc_html($this->i18nFiles($counter))), ['combinable' => true]);
        }
        $counter += $this->autoClearXmlFeedsCache('blog');

        return $counter;
    }
}
