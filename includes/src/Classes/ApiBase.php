<?php
namespace MegaOptim\RapidCache\Classes;

/**
 * Class ApiBase
 * @package MegaOptim\RapidCache\Classes
 * @since 1.0.0
 */
class ApiBase
{
    /**
     * Current CC plugin instance.
     * @return mixed
     */
    public static function plugin()
    {
        return $GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS];
    }

    /**
     * Gives you the current version string.
     *
     * @since 1.0.0
     *
     * @return string Current version string.
     */
    public static function version()
    {
        return MEGAOPTIM_RAPID_CACHE_VERSION; // Via constant.
    }

    /**
     * Gives you the current array of configured options.
     *
     * @since 1.0.0
     *
     * @return array Current array of options.
     */
    public static function options()
    {
        return $GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS]->options;
    }

    

    /**
     * Purges expired cache files, leaving all others intact.
     *
     * @since 1.0.0
     *
     * @note This occurs automatically over time via WP Cron;
     *    but this will force an immediate purge if you so desire.
     *
     * @return int Total files purged (if any).
     */
    public static function purge()
    {
        return $GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS]->purgeCache();
    }

    /**
     * This erases the entire cache for the current blog.
     *
     * @since 1.0.0
     *
     * @note In a multisite network this impacts only the current blog,
     *    it does not clear the cache for other child blogs.
     *
     * @return int Total files cleared (if any).
     */
    public static function clear()
    {
        return $GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS]->clearCache();
    }

    /**
     * This erases the cache for a specific post ID.
     *
     * @since 1.0.0
     *
     * @param int $post_id Post ID.
     *
     * @return int Total files cleared (if any).
     */
    public static function clearPost($post_id)
    {
        return $GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS]->autoClearPostCache($post_id);
    }

    /**
     * This clears the cache for a specific URL.
     *
     * @since 1.0.0
     *
     * @param string $url Input URL to clear.
     *
     * @return int Total files cleared (if any).
     */
    public static function clearUrl($url)
    {
        $regex = $GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS]->buildCachePathRegexFromWcUrl($url);

        return $GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS]->deleteFilesFromCacheDir($regex);
    }

    /**
     * This wipes out the entire cache.
     *
     * @since 1.0.0
     *
     * @note On a standard WP installation this is the same as rapid_cache::clear();
     *    but on a multisite installation it impacts the entire network
     *    (i.e. wipes the cache for all blogs in the network).
     *
     * @return int Total files wiped (if any).
     */
    public static function wipe()
    {
        return $GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS]->wipeCache();
    }
}
