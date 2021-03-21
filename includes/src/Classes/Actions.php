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
	    'migrateFromLegacy',
	    'ajaxClearCacheUrl',
	    'ajaxWipeOpCache',
	    'ajaxClearOpCache',
	    'ajaxWipeExpiredTransients',
	    'ajaxClearExpiredTransients',
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


        $response = sprintf(__('<p>Wiped a total of <code>%2$s</code> cache files.</p>', 'rapid-cache'), esc_html(MEGAOPTIM_RAPID_CACHE_NAME), esc_html($counter));
        $response .= __('<p>Cache wiped for all sites. Re-creation will occur automatically over time.</p>', 'rapid-cache');


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
	    if ( ! $this->plugin->currentUserCanClearCache() ) {
		    return; // Not allowed to clear.
	    } elseif ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'] ) ) {
		    return; // Unauthenticated POST data.
	    }
	    $counter         = $this->plugin->clearCache( true );
	    $opcache_counter = $this->plugin->clearOpcache( true );

	    $response = sprintf( __( '<p>Cleared a total of <code>%2$s</code> cache files.</p>', 'rapid-cache' ), esc_html( MEGAOPTIM_RAPID_CACHE_NAME ), esc_html( $counter ) );

	    if ( is_multisite() && is_main_site() ) {
		    $response .= __( '<p>Cache cleared for main site. Re-creation will occur automatically over time.</p>', 'rapid-cache' );
	    } else {
		    $response .= __( '<p>Cache cleared for this site. Re-creation will occur automatically over time.</p>', 'rapid-cache' );
	    }

	    if ( $opcache_counter ) {
		    $response .= sprintf( __( '<p><strong>Also cleared <code>%1$s</code> OPcache keys.</strong></p>', 'rapid-cache' ), $opcache_counter );
	    }

	    exit( $response ); // JavaScript will take it from here.
    }


	/**
	 * Action handler.
	 * @since 1.0.2
	 *
	 * @param mixed Input action argument(s).
	 *
	 * @throws \Exception
	 */
	protected function ajaxClearCacheUrl( $args ) {
		if ( ! ( $url = trim( (string) $args ) ) ) {
			return; // Nothing.
		}
		$home_url = home_url( '/' );

		if ( $url === 'home' ) {
			$url = $home_url;
		}
		$is_multisite    = is_multisite();
		$is_home         = rtrim( $url, '/' ) === rtrim( $home_url, '/' );
		$url_host        = mb_strtolower( parse_url( $url, PHP_URL_HOST ) );
		$home_host       = mb_strtolower( parse_url( $home_url, PHP_URL_HOST ) );
		$is_offsite_host = ! $is_multisite && $url_host !== $home_host;

		if ( ! $this->plugin->currentUserCanClearCache() ) {
			return; // Not allowed to clear.
		} elseif ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'] ) ) {
			return; // Unauthenticated POST data.
		}
		$counter = $this->plugin->deleteFilesFromCacheDir( $this->plugin->buildCachePathRegexFromWcUrl( $url ) );

		if ( $is_home ) { // Make this easier to recognize.
			$response = __( '<p>Home Page cache cleared successfully.</p>', 'rapid-cache' );
		} else {
			$response = __( '<p>Cache cleared successfully.</p>', 'rapid-cache' );
		}
		$response .= sprintf( __( '<p>URL: <code>%1$s</code></p>', 'rapid-cache' ), esc_html( $this->plugin->midClip( $url ) ) );

		if ( $is_offsite_host ) { // Standard install w/ offsite host in URL?
			$response .= sprintf( __( '<p><strong>Notice:</strong> The domain you entered did not match your WordPress Home URL.</p>', 'rapid-cache' ), esc_html( $url_host ) );
		}
		exit( $response ); // JavaScript will take it from here.
	}

	/**
	 * Action handler.
	 * @since 1.0.2
	 *
	 * @param mixed $args
	 */
	protected function ajaxWipeOpCache( $args ) {
		if ( ! is_multisite() || ! $this->plugin->currentUserCanWipeOpCache() ) {
			return; // Nothing to do.
		} elseif ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'] ) ) {
			return; // Unauthenticated POST data.
		}
		$counter = $this->plugin->wipeOpcache( true, false );

		$response = __( '<p>Opcache successfully wiped.</p>', 'rapid-cache' );
		$response .= sprintf( __( '<p>Wiped out <code>%1$s</code> OPcache keys.</p>', 'rapid-cache' ), esc_html( $counter ) );

		exit( $response );
	}


	/**
	 * Action handler.
	 * @since 1.0.2
	 * @param mixed $args
	 */
	protected function ajaxClearOpCache( $args ) {
		if ( ! $this->plugin->currentUserCanClearOpCache() ) {
			return; // Not allowed to clear.
		} elseif ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'] ) ) {
			return; // Unauthenticated POST data.
		}
		$counter = $this->plugin->clearOpcache( true, false );

		$response = __( '<p>Opcache successfully cleared.</p>', 'rapid-cache' );
		$response .= sprintf( __( '<p>Cleared <code>%1$s</code> OPcache keys.</p>', 'rapid-cache' ), esc_html( $counter ) );

		exit( $response );
	}


	/**
	 * Action handler.
	 * @since 1.0.2
	 * @param mixed $args
	 *
	 * @throws \Exception
	 *
	 */
	protected function ajaxWipeExpiredTransients($args)
	{
		if (!$this->plugin->currentUserCanWipeExpiredTransients()) {
			return; // Not allowed to clear.
		} elseif (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'])) {
			return; // Unauthenticated POST data.
		}
		$counter = (int) ($this->plugin->wipeExpiredTransients(true, false) / 2); // Divide in half for Dashboard message

		$response = __('<p>Expired transients wiped successfully.</p>');
		$response .= sprintf(__('<p>Wiped <code>%1$s</code> expired transients for this site.</p>', 'rapid-cache'), esc_html($counter));

		exit($response); // JavaScript will take it from here.
	}

	/**
	 * Action handler.
	 * @since 1.0.2
	 * @param mixed $args
	 *
	 * @throws \Exception
	 */
	protected function ajaxClearExpiredTransients($args)
	{
		if (!$this->plugin->currentUserCanClearExpiredTransients()) {
			return; // Not allowed to clear.
		} elseif (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'])) {
			return; // Unauthenticated POST data.
		}
		$counter = (int) ($this->plugin->clearExpiredTransients(true, false) / 2); // Divide in half for Dashboard message

		$response = __('<p>Expired transients cleared successfully.</p>');
		$response .= sprintf(__('<p>Cleared <code>%1$s</code> expired transients for this site.</p>', 'rapid-cache'), esc_html($counter));

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
