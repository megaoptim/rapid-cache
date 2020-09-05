<?php
use MegaOptim\RapidCache as Plugin;

/**
 * Postload event handler; overrides core WP function.
 *
 * @since 1.0.0
 *
 * @note See `/wp-settings.php` around line #226.
 */
function wp_cache_postload()
{
    $GLOBAL_NS      = MEGAOPTIM_RAPID_CACHE_GLOBAL_NS;
    $advanced_cache = $GLOBALS[$GLOBAL_NS.'_advanced_cache'];

    if (!$advanced_cache->is_running) {
        return; // Not applicable.
    }
    $advanced_cache->doWpAction('before_'.$GLOBAL_NS.'_'.__FUNCTION__, get_defined_vars());

    if (!empty($advanced_cache->postload['filter_status_header'])) {
        $advanced_cache->maybeFilterStatusHeaderPostload();
    }
    if (!empty($advanced_cache->postload['set_debug_info'])) {
        $advanced_cache->maybeSetDebugInfoPostload();
    }
    if (!empty($advanced_cache->postload['wp_main_query'])) {
        add_action('wp', [$advanced_cache, 'wpMainQueryPostload'], PHP_INT_MAX);
    }
    $advanced_cache->doWpAction('after_'.$GLOBAL_NS.'_'.__FUNCTION__, get_defined_vars());
    $advanced_cache->doWpAction($GLOBAL_NS.'_'.__FUNCTION__.'_complete', get_defined_vars());
}


/**
 * Returns the current version string.
 *
 * @since 1.0.0
 *
 * @return string Current version string.
 */
function rapidcache_get_version() {
    return Plugin\Classes\ApiBase::version();
}

/**
 * Clears the entire cache for the current blog.
 *
 * @since 1.0.0
 *
 * @note In a multisite network this impacts only the current blog, it does not clear the cache for other child blogs.
 *
 * @return int Total files cleared (if any).
 */
function rapidcache_clear_cache() {
    return Plugin\Classes\ApiBase::clear();
}

/**
 * Clears the cache for a specific post ID.
 *
 * @since 1.0.0
 *
 * @param int $post_id Post ID.
 *
 * @return int Total files cleared (if any).
 */
function rapidcache_clear_post_cache($post_id) {
    return Plugin\Classes\ApiBase::clearPost($post_id);
}

/**
 * Clears the cache for a specific URL.
 *
 * @since 1.0.0
 *
 * @param string $url Input URL to clear.
 *
 * @return int Total files cleared (if any).
 */
function rapidcache_clear_url_cache($url) {
    return Plugin\Classes\ApiBase::clearUrl($url);
}

/**
 * This wipes out the entire cache.
 *
 * @since 1.0.0
 *
 * @note On a standard WP installation this is the same as rapidcache_clear_cache();
 *    but on a multisite installation it impacts the entire network
 *    (i.e. wipes the cache for all blogs in the network).
 *
 * @return int Total files wiped (if any).
 */
function rapidcache_wipe_cache() {
    return Plugin\Classes\ApiBase::wipe();
}

/**
 * Purges expired cache files, leaving all others intact.
 *
 * @since 1.0.0
 *
 * @note This occurs automatically over time via WP Cron but this will force an immediate purge if you so desire.
 *
 * @return int Total files purged (if any).
 */
function rapidcache_purge_expired_cache() {
    return Plugin\Classes\ApiBase::purge();
}
