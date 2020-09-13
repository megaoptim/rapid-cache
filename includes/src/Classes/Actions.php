<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */

namespace MegaOptim\RapidCache\Classes;

/**
 * Actions.
 *
 * @since 1.0.0
 */
class Actions extends AbsBase
{
    /**
     * Allowed actions.
     *
     * @since 1.0.0
     */
    protected $allowed_actions = [
        'wipeCache',
        'clearCache',
        'ajaxWipeCache',
        'ajaxClearCache',
        'saveOptions',
        'restoreDefaultOptions',
        'dismissNotice',
	    'exportOptions',
	    'migrateFromLegacy'
    ];

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
        if (empty($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS])) {
            return; // Not applicable.
        }
        foreach ((array) $_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS] as $_action => $_args) {
            if (is_string($_action) && method_exists($this, $_action)) {
                if (in_array($_action, $this->allowed_actions, true)) {
                    $this->{$_action}($_args); // Do action!
                }
            }
        }
        unset($_action, $_args); // Housekeeping.
    }

    /**
     * Action handler.
     *
     * @param  mixed  $args
     *
     * @throws \Exception
     * @since 1.0.0
     *
     */
    protected function wipeCache($args)
    {
        if ( ! is_multisite() || ! $this->plugin->currentUserCanWipeCache()) {
            return; // Nothing to do.
        } elseif (empty($_REQUEST['_wpnonce']) || ! wp_verify_nonce($_REQUEST['_wpnonce'])) {
            return; // Unauthenticated POST data.
        }
        $counter     = $this->plugin->wipeCache(true);
        $redirect_to = self_admin_url('/admin.php');
        $query_args  = [
            'page'                                          => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS,
            MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_cache_wiped' => '1'
        ];
        $redirect_to = add_query_arg(urlencode_deep($query_args), $redirect_to);
        wp_redirect($redirect_to);
        exit();
    }

    /**
     * Action handler.
     *
     * @param  mixed  $args
     *
     * @throws \Exception
     * @since 1.0.0
     */
    protected function clearCache($args)
    {
        if ( ! $this->plugin->currentUserCanClearCache()) {
            return; // Not allowed to clear.
        } elseif (empty($_REQUEST['_wpnonce']) || ! wp_verify_nonce($_REQUEST['_wpnonce'])) {
            return; // Unauthenticated POST data.
        }
        $counter = $this->plugin->clearCache(true);

        $redirect_to = self_admin_url('/admin.php'); // Redirect preparations.
        $query_args  = ['page'                                            => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS,
                        MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_cache_cleared' => '1'
        ];
        $redirect_to = add_query_arg(urlencode_deep($query_args), $redirect_to);

        wp_redirect($redirect_to);
        exit();
    }

    /**
     * Action handler.
     *
     * @param  mixed  $args
     *
     * @throws \Exception
     * @since 1.0.0
     */
    protected function ajaxWipeCache($args)
    {
        if ( ! is_multisite() || ! $this->plugin->currentUserCanWipeCache()) {
            return; // Nothing to do.
        } elseif (empty($_REQUEST['_wpnonce']) || ! wp_verify_nonce($_REQUEST['_wpnonce'])) {
            return; // Unauthenticated POST data.
        }
        $counter = $this->plugin->wipeCache(true);


        $response = sprintf(__('<p>Wiped a total of <code>%2$s</code> cache files.</p>', 'rapid-cache'),
            esc_html(MEGAOPTIM_RAPID_CACHE_NAME), esc_html($counter));
        $response .= __('<p>Cache wiped for all sites. Re-creation will occur automatically over time.</p>',
            'rapid-cache');


        exit($response); // JavaScript will take it from here.
    }

    /**
     * Action handler.
     *
     * @param  mixed  $args
     *
     * @throws \Exception
     * @since 1.0.0
     */
    protected function ajaxClearCache($args)
    {
        if ( ! $this->plugin->currentUserCanClearCache()) {
            return; // Not allowed to clear.
        } elseif (empty($_REQUEST['_wpnonce']) || ! wp_verify_nonce($_REQUEST['_wpnonce'])) {
            return; // Unauthenticated POST data.
        }
        $counter = $this->plugin->clearCache(true);

        $response = sprintf(__('<p>Cleared a total of <code>%2$s</code> cache files.</p>', 'rapid-cache'),
            esc_html(MEGAOPTIM_RAPID_CACHE_NAME), esc_html($counter));

        if (is_multisite() && is_main_site()) {
            $response .= __('<p>Cache cleared for main site. Re-creation will occur automatically over time.</p>',
                'rapid-cache');
        } else {
            $response .= __('<p>Cache cleared for this site. Re-creation will occur automatically over time.</p>',
                'rapid-cache');
        }

        exit($response); // JavaScript will take it from here.
    }


    /**
     * Action handler.
     *
     * @param  mixed  $args
     *
     * @since 1.0.0
     *
     */
    protected function saveOptions($args)
    {
        if ( ! current_user_can($this->plugin->cap)) {
            return; // Nothing to do.
        } elseif (empty($_REQUEST['_wpnonce']) || ! wp_verify_nonce($_REQUEST['_wpnonce'])) {
            return; // Unauthenticated POST data.
        }
        if ( ! empty($_FILES[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS]['tmp_name']['import_options'])) {
        	$import_file_contents = file_get_contents($_FILES[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS]['tmp_name']['import_options']);
            unlink($_FILES[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS]['tmp_name']['import_options']); // Deleted uploaded file.

            $args = wp_slash(json_decode($import_file_contents, true));

            // Clean up third-party options before importing
			$args = $this->plugin->cleanUpOptions($args);
        }

        $args = $this->plugin->trimDeep(stripslashes_deep((array) $args));
        $this->plugin->updateOptions($args); // Save/update options.

        $redirect_to = self_admin_url('/admin.php'); // Redirect preparations.
        $query_args  = ['page' => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS, MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_updated' => '1'];

        $this->plugin->refreshAfterOptionsUpdate($query_args);
        $redirect_to = add_query_arg(urlencode_deep($query_args), $redirect_to);

        wp_redirect($redirect_to);
        exit();
    }

    /**
     * Action handler.
     *
     * @param  mixed  $args
     *
     * @since 1.0.0
     *
     */
    protected function restoreDefaultOptions($args)
    {
        if ( ! current_user_can($this->plugin->cap)) {
            return; // Nothing to do.
        } elseif (is_multisite() && ! current_user_can($this->plugin->network_cap)) {
            return; // Nothing to do.
        } elseif (empty($_REQUEST['_wpnonce']) || ! wp_verify_nonce($_REQUEST['_wpnonce'])) {
            return; // Unauthenticated POST data.
        }
        $this->plugin->restoreDefaultOptions(); // Restore defaults.

        $redirect_to = self_admin_url('/admin.php'); // Redirect prep.
        $query_args  = [
            'page'  => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS,
            MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_restored' => '1'
        ];

	    $this->plugin->refreshAfterOptionsUpdate($query_args);

        $redirect_to = add_query_arg(urlencode_deep($query_args), $redirect_to);

        wp_redirect($redirect_to);
        exit();
    }

	/**
	 * Migrate legacy plugin options
	 *
	 * @since 1.0.1
	 *
	 * @param $args
	 */
	protected function migrateFromLegacy($args) {

		if (!current_user_can($this->plugin->cap)) {
			return; // Nothing to do.
		} elseif (is_multisite() && !current_user_can($this->plugin->network_cap)) {
			return; // Nothing to do.
		} elseif (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'])) {
			return; // Unauthenticated POST data.
		}

		$redirect_to = self_admin_url('/admin.php'); // Redirect prep.
		$query_args  = [
			'page'  => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS,
			MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_migrated' => '1'
		];

		$old_options = get_option(MEGAOPTIM_RAPID_CACHE_OLD_GLOBAL_NS.'_options');
		if(!empty($old_options) && is_array($old_options)) {
			$args = $this->plugin->cleanUpOptions($old_options);
			$args = $this->plugin->trimDeep(stripslashes_deep((array) $args));
			$this->plugin->updateOptions($args); // Save/update options.
			$this->plugin->refreshAfterOptionsUpdate($query_args);
		}

		if(isset($_REQUEST['purgeLegacy']) && (int) $_REQUEST['purgeLegacy']) {
			delete_option(MEGAOPTIM_RAPID_CACHE_OLD_GLOBAL_NS.'_notices');
			delete_option(MEGAOPTIM_RAPID_CACHE_OLD_GLOBAL_NS.'_options');
		}

		$redirect_to = add_query_arg(urlencode_deep($query_args), $redirect_to);

		wp_redirect($redirect_to);
		exit();
	}

	/**
	 * Action handler.
	 *
	 * @since 1.0.0 Rewrite.
	 *
	 * @param mixed $args Input action argument(s).
	 */
	protected function exportOptions($args)
	{
		if (!current_user_can($this->plugin->cap)) {
			return; // Nothing to do.
		} elseif (is_multisite() && !current_user_can($this->plugin->network_cap)) {
			return; // Nothing to do.
		} elseif (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'])) {
			return; // Unauthenticated POST data.
		}
		ini_set('zlib.output_compression', false);

		if ($this->plugin->functionIsPossible('apache_setenv')) {
			apache_setenv('no-gzip', '1');
		}
		while (@ob_end_clean()) {
			// Cleans output buffers.
		}
		$export    = json_encode($this->plugin->options, JSON_PRETTY_PRINT);
		$file_name = MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'-options.json';

		nocache_headers();

		header('Accept-Ranges: none');
		header('Content-Encoding: none');
		header('Content-Length: '.strlen($export));
		header('Content-Type: application/json; charset=UTF-8');
		header('Content-Disposition: attachment; filename="'.$file_name.'"');

		exit($export); // Deliver the export file.
	}

    /**
     * Action handler.
     *
     * @param  mixed  $args
     *
     * @since 1.0.0
     *
     */
    protected function dismissNotice($args)
    {
        if ( ! current_user_can($this->plugin->cap)) {
            return; // Nothing to do.
        } elseif (empty($_REQUEST['_wpnonce']) || ! wp_verify_nonce($_REQUEST['_wpnonce'])) {
            return; // Unauthenticated POST data.
        }
        $args = $this->plugin->trimDeep(stripslashes_deep((array) $args));
        $this->plugin->dismissNotice($args['key']);

        wp_redirect(remove_query_arg(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS));
        exit();
    }
}
