<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2023 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */

namespace MegaOptim\RapidCache\Traits\Plugin;

trait WcpUrlUtils {

	/**
	 * Automatically clears cache files for a list of custom URLs.
	 *
	 * @return int Total files cleared by this routine (if any).
	 *
	 * @note Unlike many of the other `auto_` methods, this one is NOT currently
	 *    attached to any hooks. However, it is called upon by other routines attached to hooks.
	 * @throws \Exception If a clear failure occurs.
	 *
	 * @since 1.2.0
	 *
	 */
	public function autoClearUrlsCache() {
		$counter = 0; // Initialize.

		if ( ! is_null( $done = &$this->cacheKey( 'autoClearUrlsCache' ) ) ) {
			return $counter; // Already did this.
		}
		$done = true; // Flag as having been done.

		if ( ! $this->options['enable'] ) {
			return $counter; // Nothing to do.
		}
		if ( ! $this->options['cache_clear_urls'] ) {
			return $counter; // Nothing to do.
		}
		if ( ! is_dir( $cache_dir = $this->cacheDir() ) ) {
			return $counter; // Nothing to do.
		}
		foreach ( preg_split( '/[' . "\r\n" . ']+/', $this->options['cache_clear_urls'], - 1, PREG_SPLIT_NO_EMPTY ) as $_url ) {
			if ( mb_stripos( $_url, 'http' ) === 0 ) {
				$_regex  = $this->buildCachePathRegexFromWcUrl( $_url );
				$counter += $this->deleteFilesFromCacheDir( $_regex );
			}
		}
		unset( $_url, $_regex ); // Housekeeping.

		if ( $counter && is_admin() ) {
			$this->enqueueNotice( sprintf( __( 'Found %1$s in the cache matching a custom list of URLs; auto-clearing.', 'rapid-cache' ), esc_html( $this->i18nFiles( $counter ) ) ), [ 'combinable' => true ] );
		}

		return $counter;
	}
}
