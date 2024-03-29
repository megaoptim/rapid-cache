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

trait PostloadUtils
{
    /**
     * Have we caught the main WP loaded being loaded yet?
     *
     * @since 1.0.0
     *
     * @type bool `TRUE` if main query has been loaded; else `FALSE`.
     *
     * @see wpMainQueryPostload()
     */
    public $is_wp_loaded_query = false;

    /**
     * Is the current request a WordPress 404 error?
     *
     * @since 1.0.0
     *
     * @type bool `TRUE` if is a 404 error; else `FALSE`.
     *
     * @see wpMainQueryPostload()
     */
    public $is_404 = false;

    /**
     * Last HTTP status code passed through {@link \status_header}.
     *
     * @since 1.0.0
     *
     * @type int Last HTTP status code (if applicable).
     *
     * @see maybeFilterStatusHeaderPostload()
     */
    public $http_status = 0;

    /**
     * Is the current request a WordPress content type?
     *
     * @since 1.0.0
     *
     * @type bool `TRUE` if is a WP content type.
     *
     * @see wpMainQueryPostload()
     */
    public $is_a_wp_content_type = false;

    /**
     * Current WordPress {@link \content_url()}.
     *
     * @since 1.0.0
     *
     * @type string Current WordPress {@link \content_url()}.
     *
     * @see wpMainQueryPostload()
     */
    public $content_url = '';

    /**
     * Flag for {@link \is_user_loged_in()}.
     *
     * @since 1.0.0
     *
     * @type bool `TRUE` if {@link \is_user_loged_in()} else `FALSE`.
     *
     * @see wpMainQueryPostload()
     */
    public $is_user_logged_in = false;

    /**
     * Flag for {@link \is_maintenance()}.
     *
     * @since 1.0.0
     *
     * @type bool `TRUE` if {@link \is_maintenance()} else `FALSE`.
     *
     * @see wpMainQueryPostload()
     */
    public $is_maintenance = false;

	/**
	 * Calculated user token; applicable w/ user postload enabled.
	 *
	 * @since 1.2.0
	 *
	 * @type string|int An MD5 hash token; or a specific WP user ID.
	 */
	public $user_token = '';

    /**
     * Array of data targeted at the postload phase.
     *
     * @since 1.0.0
     *
     * @type array Data and/or flags that work with various postload handlers.
     */
    public $postload = [
	    'invalidate_when_logged_in' => false,
	    'when_logged_in'            => false,
	    'filter_status_header'      => true,
	    'wp_main_query'             => true,
	    'set_debug_info'            => RAPID_CACHE_DEBUGGING_ENABLE,
    ];

	/**
	 * Sets a flag for possible invalidation upon certain actions in the postload phase.
	 *
	 * @since 1.2.0
	 */
	public function maybePostloadInvalidateWhenLoggedIn()
	{
		if (!defined('RAPID_CACHE_WHEN_LOGGED_IN') || RAPID_CACHE_WHEN_LOGGED_IN !== 'postload') {
			return; // Nothing to do in this case.
		}
		if (is_admin()) {
			return; // No invalidations.
		}
		if (!$this->isLikeUserLoggedIn()) {
			return; // Nothing to do.
		}
		if (!empty($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS])) {
			return; // Plugin action.
		}
		if ($this->isPostPutDeleteRequest() || $this->isUncacheableRequestMethod()) {
			$this->postload['invalidate_when_logged_in'] = true;
		} elseif (!RAPID_CACHE_GET_REQUESTS && $this->requestContainsUncacheableQueryVars()) {
			$this->postload['invalidate_when_logged_in'] = true;
		}
	}

	/**
	 * Invalidates cache files for a user (if applicable).
	 *
	 * @since 1.2.0
	 */
	public function maybeInvalidateWhenLoggedInPostload()
	{
		if (!defined('RAPID_CACHE_WHEN_LOGGED_IN') || RAPID_CACHE_WHEN_LOGGED_IN !== 'postload') {
			return; // Nothing to do in this case.
		}
		if (empty($this->postload['invalidate_when_logged_in'])) {
			return; // Nothing to do in this case.
		}
		if (!($this->user_token = $this->userToken())) {
			return; // Nothing to do in this case.
		}
		if ($this->applyWpFilters(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_invalidate_when_logged_in_postload', true) === false) {
			return; // Nothing to do in this case (disabled via filter).
		}
		$regex = $this->assembleCachePathRegex('', '.*?\.u\/'.preg_quote($this->user_token, '/').'[.\/]');
		$this->wipeFilesFromCacheDir($regex); // Wipe matching files.
	}

	/**
	 * Starts output buffering in the postload phase (i.e. a bit later);
	 *    when/if user caching is enabled; and if applicable.
	 *
	 * @since 1.2.0
	 *
	 * @return void
	 */
	public function maybeStartObWhenLoggedInPostload()
	{
		if (!defined('RAPID_CACHE_WHEN_LOGGED_IN') || RAPID_CACHE_WHEN_LOGGED_IN !== 'postload') {
			return; // Nothing to do in this case.
		}
		if (empty($this->postload['when_logged_in'])) {
			return; // Nothing to do in this case.
		}
		if (!($this->user_token = $this->userToken())) {
			if (!$this->user_login_cookie_expired_or_invalid) {
				return $this->maybeSetDebugInfo(self::NC_DEBUG_NO_USER_TOKEN);
			}
		}
		$this->cache_path = $this->buildCachePath($this->protocol.$this->host_token.$_SERVER['REQUEST_URI'], $this->user_token, $this->version_salt);
		$this->cache_file = RAPID_CACHE_DIR.'/'.$this->cache_path; // Now considering a user token.

		if (is_file($this->cache_file) && ($this->cache_max_age_disabled || filemtime($this->cache_file) >= $this->cache_max_age)) {
			list($headers, $cache) = explode('<!--headers-->', file_get_contents($this->cache_file), 2);

			if (filemtime($this->cache_file) < $this->nonce_cache_max_age && preg_match('/\b(?:_wpnonce|akismet_comment_nonce)\b/u', $cache)) {
				ob_start([$this, 'outputBufferCallbackHandler']); // This ignores `cache_max_age_disabled` in favor of better security.
			} else {
				$headers_list = $this->headersList(); // Headers that are enqueued already.

				foreach (unserialize($headers) as $_header) {
					if (!in_array($_header, $headers_list, true) && mb_stripos($_header, 'last-modified:') !== 0) {
						header($_header); // Only cacheable/safe headers are stored in the cache.
					}
				} // unset($_header); // Just a little housekeeping.

				if (RAPID_CACHE_DEBUGGING_ENABLE && $this->isHtmlXmlDoc($cache)) {
					$total_time = number_format(microtime(true) - $this->timer, 5, '.', '');

					$DebugNotes = new Classes\Notes();

					$DebugNotes->add(__('Loaded via Cache On', 'rapid-cache'), date('M jS, Y @ g:i a T'));
					$DebugNotes->add(__('Loaded via Cache In', 'rapid-cache'), sprintf(__('%1$s seconds', 'rapid-cache'), $total_time));

					$cache .= "\n\n".$DebugNotes->asHtmlComments();
				}
				exit($cache); // Exit with cache contents.
			}
		} else {
			ob_start([$this, 'outputBufferCallbackHandler']);
		}
	}

    /**
     * Filters WP {@link \status_header()} (if applicable).
     *
     * @since 1.0.0
     */
    public function maybeFilterStatusHeaderPostload()
    {
        if (empty($this->postload['filter_status_header'])) {
            return; // Nothing to do in this case.
        }

        add_filter(
            'status_header',
            function ($status_header, $status_code) {
                if ($status_code > 0) {
                    $this->http_status = (int) $status_code;
                }
                return $status_header;
            },
            PHP_INT_MAX,
            2
        );
    }

    /**
     * Hooks `NC_DEBUG_` info into the WordPress `shutdown` phase (if applicable).
     *
     * @since 1.0.0
     */
    public function maybeSetDebugInfoPostload()
    {
        if (!RAPID_CACHE_DEBUGGING_ENABLE) {
            return; // Nothing to do.
        }
        if (empty($this->postload['set_debug_info'])) {
            return; // Nothing to do in this case.
        }
        if (is_admin()) {
            return; // Not applicable.
        }
        if (strcasecmp(PHP_SAPI, 'cli') === 0) {
            return; // Let's not run the risk here.
        }
        add_action('shutdown', [$this, 'maybeEchoNcDebugInfo'], PHP_INT_MAX - 10);
    }

    /**
     * Grab details from WP and the Rapid Cache plugin itself,
     *    after the main query is loaded (if at all possible).
     *
     * This is where we have a chance to grab any values we need from WordPress; or from the CC plugin.
     *    It is EXTREMEMLY important that we NOT attempt to grab any object references here.
     *    Anything acquired in this phase should be stored as a scalar value.
     *    See {@link outputBufferCallbackHandler()} for further details.
     *
     * @since 1.0.0
     *
     * @attaches-to `wp` hook.
     */
    public function wpMainQueryPostload()
    {
        if (empty($this->postload['wp_main_query'])) {
            return; // Nothing to do in this case.
        }
        if ($this->is_wp_loaded_query || is_admin()) {
            return; // Nothing to do.
        }
        if (!is_main_query()) {
            return; // Not main query.
        }
        $this->is_wp_loaded_query = true;
        $this->is_404             = is_404();
        $this->is_user_logged_in  = is_user_logged_in();
        $this->content_url        = rtrim(content_url(), '/');
        $this->is_maintenance     = $this->functionIsPossible('is_maintenance') && is_maintenance();

        add_action(
            'template_redirect',
            function () {
                $this->is_a_wp_content_type = $this->is_404 || $this->is_maintenance
                                               || is_front_page() // See <https://core.trac.wordpress.org/ticket/21602#comment:7>
                                               || is_home() || is_singular() || is_archive() || is_post_type_archive() || is_tax() || is_search() || is_feed();
            },
            11
        );
    }
}
