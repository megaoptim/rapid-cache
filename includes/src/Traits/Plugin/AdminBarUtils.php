<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */

namespace MegaOptim\RapidCache\Traits\Plugin;

trait AdminBarUtils {
	/**
	 * Showing admin bar.
	 *
	 * @param  bool  $feature  Check something specific?
	 *
	 * @return bool True if showing.
	 * @since 1.0.0
	 *
	 */
	public function adminBarShowing( $feature = '' ) {

		$feature = trim( mb_strtolower( (string) $feature ) );
		if ( ! is_null( $showing = &$this->cacheKey( 'adminBarShowing', $feature ) ) ) {
			return $showing; // Already cached this.
		}
		$is_multisite = is_multisite(); // Call this once only.
		if ( ( $showing = $this->options['enable'] && is_admin_bar_showing() ) ) {
			switch ( $feature ) {
				case 'cache_wipe':
					$showing &= $is_multisite;
					break;
				case 'cache_clear':
				default: // Default case handler.
					$showing = $is_multisite
					           || ( ! $is_multisite || ! is_network_admin() || $this->isMenuPage( MEGAOPTIM_RAPID_CACHE_GLOBAL_NS . '*' ) );
					break;
			}
		}
		if ( $showing ) {
			$current_user_can_wipe_cache  = $is_multisite && current_user_can( $this->network_cap );
			$current_user_can_clear_cache = $this->currentUserCanClearCache();
			switch ( $feature ) {
				case 'cache_wipe':
					$showing = $current_user_can_wipe_cache;
					break;
				case 'cache_clear':
				default: // Default case handler.
					$showing = $current_user_can_wipe_cache || $current_user_can_clear_cache;

					break;
			}
		}

		return $showing;
	}

	/**
	 * Filter WordPress admin bar.
	 *
	 * @param $wp_admin_bar \WP_Admin_Bar
	 *
	 * @since 1.0.0
	 *
	 * @attaches-to `admin_bar_menu` hook.
	 *
	 */
	public function adminBarMenu( \WP_Admin_Bar &$wp_admin_bar ) {

		if ( ! $this->adminBarShowing() ) {
			return; // Nothing to do.
		}
		if ( $this->adminBarShowing( 'cache_wipe' ) ) {
			$wp_admin_bar->add_menu(
				[
					'parent' => 'top-secondary',
					'id'     => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS . '-wipe',
					'title'  => __( 'Wipe', 'rapid-cache' ),
					'href'   => '#',
					'meta'   => [
						'title'    => __( 'Wipe Cache (Start Fresh). Clears the cache for all sites in this network at once!', 'rapid-cache' ),
						'class'    => '-wipe',
						'tabindex' => - 1,
					],
				]
			);
		}
		if ( $this->adminBarShowing( 'cache_clear' ) ) {

			if ( ( $this->adminBarShowing( 'cache_clear_options' ) ) ) {
				$cache_clear_options = '<li class="-home-url-only"><a href="#" title="' . __( 'Clear the Home Page cache', 'rapid-cache' ) . '">' . __( 'Home Page', 'rapid-cache' ) . '</a></li>';
				if ( ! is_admin() ) {
					$cache_clear_options .= '<li class="-current-url-only"><a href="#" title="' . __( 'Clear the cache for the current URL', 'rapid-cache' ) . '">' . __( 'Current URL', 'rapid-cache' ) . '</a></li>';
				}
				$cache_clear_options .= '<li class="-specific-url-only"><a href="#" title="' . __( 'Clear the cache for a specific URL', 'rapid-cache' ) . '">' . __( 'Specific URL', 'rapid-cache' ) . '</a></li>';
				if ( $this->functionIsPossible( 'opcache_reset' ) && $this->currentUserCanClearOpCache() ) {
					$cache_clear_options .= '<li class="-opcache-only"><a href="#" title="' . __( 'Clear PHP\'s OPcache', 'rapid-cache' ) . '">' . __( 'OPcache', 'rapid-cache' ) . '</a></li>';
				}
				if ( $this->currentUserCanClearExpiredTransients() ) {
					$cache_clear_options .= '<li class="-transients-only"><a href="#" title="' . __( 'Clear expired transients from the database', 'rapid-cache' ) . '">' . __( 'Expired Transients', 'rapid-cache' ) . '</a></li>';
				}
			} else {
				$cache_clear_options = ''; // Empty in this default case.
			}

			$wp_admin_bar->add_menu(
				[
					'parent' => 'top-secondary',
					'id'     => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS . '-clear',
					'title'  => __( 'Clear Cache', 'rapid-cache' ),
					'href'   => '#',
					'meta'   => [
						'title'    => is_multisite() && current_user_can( $this->network_cap )
							? __( 'Clear Cache (Start Fresh). Affects the current site only.', 'rapid-cache' )
							: '',
						'class'    => '-clear',
						'tabindex' => - 1,
					],
				]
			);
			if ( $cache_clear_options ) {
				$wp_admin_bar->add_group(
					[
						'parent' => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS . '-clear',
						'id'     => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS . '-clear-options-wrapper',
						'meta'   => [
							'class' => '-wrapper',
						],
					]
				);
				$wp_admin_bar->add_menu(
					[
						'parent' => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS . '-clear-options-wrapper',
						'id'     => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS . '-clear-options-container',
						'title'  => '<ul class="-options">' .
						            '   ' . $cache_clear_options .
						            '</ul>' .
						            '<div class="-spacer"></div>',
						'meta'   => [
							'class'    => '-container',
							'tabindex' => - 1,
						],
					]
				);
			}

		}

	}

	/**
	 * Injects `<meta>` tag w/ JSON-encoded data.
	 *
	 * @since 1.0.0
	 *
	 * @attaches-to `admin_head` hook.
	 */
	public function adminBarMetaTags() {
		if ( ! $this->adminBarShowing() ) {
			return; // Nothing to do.
		}
		$vars = [
			'_wpnonce'                 => wp_create_nonce(),
			'isMultisite'              => is_multisite(),
			'currentUserHasCap'        => current_user_can( $this->cap ),
			'currentUserHasNetworkCap' => current_user_can( $this->network_cap ),
			'ajaxURL'                  => site_url( '/wp-load.php', is_ssl() ? 'https' : 'http' ),
			'i18n'                     => [
				'name'             => MEGAOPTIM_RAPID_CACHE_NAME,
				'perSymbol'        => __( '%', 'rapid-cache' ),
				'file'             => __( 'file', 'rapid-cache' ),
				'files'            => __( 'files', 'rapid-cache' ),
				'pageCache'        => __( 'Page Cache', 'rapid-cache' ),
				'htmlCompressor'   => __( 'HTML Compressor', 'rapid-cache' ),
				'currentTotal'     => __( 'Current Total', 'rapid-cache' ),
				'currentSite'      => __( 'Current Site', 'rapid-cache' ),
				'xDayHigh'         => __( '%s Day High', 'rapid-cache' ),
				'enterSpecificUrl' => __( 'Enter a specific URL to clear the cache for that page:', 'rapid-cache' ),
			],
		];
		echo '<meta property="' . esc_attr( MEGAOPTIM_RAPID_CACHE_GLOBAL_NS ) . ':admin-bar-vars" content="data-json"' .
		     ' data-json="' . esc_attr( json_encode( $vars ) ) . '" id="' . esc_attr( MEGAOPTIM_RAPID_CACHE_GLOBAL_NS ) . '-admin-bar-vars" />' . "\n";
	}

	/**
	 * Adds CSS for WordPress admin bar.
	 *
	 * @since 1.0.0
	 *
	 * @attaches-to `wp_enqueue_scripts` hook.
	 * @attaches-to `admin_enqueue_scripts` hook.
	 */
	public function adminBarStyles() {
		if ( ! $this->adminBarShowing() ) {
			return; // Nothing to do.
		}
		$deps = []; // Plugin dependencies.
		wp_enqueue_style( MEGAOPTIM_RAPID_CACHE_GLOBAL_NS . '-admin-bar', $this->url( '/assets/css/admin-bar.min.css' ), $deps, MEGAOPTIM_RAPID_CACHE_VERSION, 'all' );
	}

	/**
	 * Adds JS for WordPress admin bar.
	 *
	 * @since 1.0.0
	 *
	 * @attaches-to `wp_enqueue_scripts` hook.
	 * @attaches-to `admin_enqueue_scripts` hook.
	 */
	public function adminBarScripts() {
		if ( ! $this->adminBarShowing() ) {
			return; // Nothing to do.
		}
		$deps = [ 'jquery', 'admin-bar' ]; // Plugin dependencies.

		wp_enqueue_script( MEGAOPTIM_RAPID_CACHE_GLOBAL_NS . '-admin-bar', $this->url( '/assets/js/admin-bar.min.js' ), $deps, MEGAOPTIM_RAPID_CACHE_VERSION, true );
	}
}
