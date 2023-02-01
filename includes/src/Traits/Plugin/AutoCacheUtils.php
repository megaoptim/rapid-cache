<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2023 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */

namespace MegaOptim\RapidCache\Traits\Plugin;

use MegaOptim\RapidCache\Classes;

trait AutoCacheUtils {

	/**
	 * Runs the auto-cache engine via CRON job.
	 *
	 * @since 1.2.0
	 *
	 * @attaches-to `_cron_'.__MEGAOPTIM_RAPID_CACHE_GLOBAL_NS__.'_auto_cache`
	 */
	public function autoCache() {
		if ( ! $this->options['enable'] ) {
			return; // Nothing to do.
		}
		if ( ! $this->options['auto_cache_enable'] ) {
			return; // Nothing to do.
		}
		if ( ! $this->options['auto_cache_sitemap_url'] ) {
			if ( ! $this->options['auto_cache_other_urls'] ) {
				return; // Nothing to do.
			}
		}
		if ( ! $this->autoCacheCheckPhpReqs() ) {
			return; // Server does not meet minimum requirements.
		}
		new Classes\AutoCache();
	}

	/**
	 * Check if PHP configuration meets minimum requirements for Auto-Cache Engine and remove old notice if necessary.
	 *
	 * @since 1.2.0
	 *
	 * @attaches-to `admin_init`
	 */
	public function autoCacheMaybeClearPhpReqsError() {
		if ( ! is_null( $done = &$this->cacheKey( 'autoCacheMaybeClearPhpReqsError' ) ) ) {
			return; // Already did this.
		}
		$done = true; // Flag as having been done.

		if ( ! $this->options['enable'] ) {
			return; // Nothing to do.
		}
		if ( ! $this->options['auto_cache_enable'] ) {
			return; // Nothing to do.
		}
		$this->autoCacheCheckPhpReqs();
	}

	/**
	 * Check if PHP configuration meets minimum requirements for Auto-Cache Engine and display a notice if necessary.
	 *
	 * @return bool `TRUE` if all required PHP configuration is present, else `FALSE`. This also creates a dashboard notice in some cases.
	 *
	 * @note  Unlike `autoCacheCheckXmlSitemap()`, this routine is NOT used by the Auto-Cache Engine class when the Auto-Cache Engine is running.
	 *        However, this routine is called prior to running the Auto-Cache Engine, so caching here should be avoided (this gets called during
	 *        `admin_init` and prior to running the Auto-Cache Engine).
	 * @since 1.2.0
	 *
	 */
	public function autoCacheCheckPhpReqs() {
		if ( ! filter_var( ini_get( 'allow_url_fopen' ), FILTER_VALIDATE_BOOLEAN ) && ! $this->functionIsPossible( 'curl_version' ) ) { // Is allow_url_fopen=0 and cURL unavailable?
			$this->dismissMainNotice( 'auto_cache_engine_minimum_requirements' ); // Clear any previous notice.
			$this->enqueueMainNotice(
				sprintf( __( '<strong>%1$s says...</strong> The Auto-Cache Engine requires <a href="https://www.php.net/manual/en/filesystem.configuration.php#ini.allow-url-fopen" target="_blank">PHP URL-aware fopen wrappers</a> (<code>allow_url_fopen=1</code>) or the <a href="https://cometcache.com/r/php-net-curl/" target="_blank">PHP cURL functions</a> to be installed and available, however your PHP configuration does not meet these minimum requirements. Please contact your web hosting company to resolve this issue or disable the Auto-Cache Engine in the <a href="' . esc_attr( add_query_arg( urlencode_deep( [ 'page' => MEGAOPTIM_RAPID_CACHE_GLOBAL_NS ] ), self_admin_url( '/admin.php' ) ) ) . '">settings</a>.', 'rapid-cache' ), esc_html( NAME ) ),
				[ 'class' => 'error', 'persistent_key' => 'auto_cache_engine_minimum_requirements', 'dismissable' => false ]
			);

			return false; // Nothing more we can do in this case.
		}
		$this->dismissMainNotice( 'auto_cache_engine_minimum_requirements' ); // Any previous problems have been fixed; dismiss any existing failure notice

		return true;
	}

	/**
	 * Check if Auto-Cache Engine XML Sitemap is valid and remove old notice if necessary.
	 *
	 * @since 1.2.0
	 *
	 * @attaches-to `admin_init`
	 */
	public function autoCacheMaybeClearPrimaryXmlSitemapError() {
		if ( ! is_null( $done = &$this->cacheKey( 'autoCacheMaybeClearPrimaryXmlSitemapError' ) ) ) {
			return; // Already did this.
		}
		$done = true; // Flag as having been done.

		if ( ! $this->options['enable'] ) {
			return; // Nothing to do.
		}
		if ( ! $this->options['auto_cache_enable'] ) {
			return; // Nothing to do.
		}
		if ( ! $this->options['auto_cache_sitemap_url'] ) {
			return; // Nothing to do.
		}
		if ( ( $last_checked = get_transient( MEGAOPTIM_RAPID_CACHE_GLOBAL_NS . '-' . md5( $this->options['auto_cache_sitemap_url'] ) ) ) && ( time() <= ( (int) $last_checked + HOUR_IN_SECONDS ) ) ) {
			$this->dismissMainNotice( 'xml_sitemap_missing' ); // Previous error was fixed; we only create transient when Sitemap passes validation

			return; // Nothing to do; already checked within the last hour.
		}
		$is_multisite                = is_multisite(); // Multisite network?
		$can_consider_domain_mapping = $is_multisite && $this->canConsiderDomainMapping();
		$blog_url                    = rtrim( network_home_url( '' ), '/' );

		if ( $is_multisite && $can_consider_domain_mapping ) {
			$blog_url = $this->domainMappingUrlFilter( $blog_url );
		}
		if ( $blog_url && ( $blog_sitemap_path = ltrim( $this->options['auto_cache_sitemap_url'], '/' ) ) ) {
			$this->autoCacheCheckXmlSitemap( $blog_url . '/' . $blog_sitemap_path, false, false );
		}
	}

	/**
	 * Check if Auto-Cache Engine XML Sitemap is valid and display a notice if necessary.
	 *
	 * @param  string  $sitemap  A URL to an XML sitemap file.
	 *                                     This supports nested XML sitemap index files too; i.e. `<sitemapindex>`.
	 *                                     Note that GZIP files are NOT supported at this time.
	 * @param  bool  $is_nested_sitemap  Are we traversing a primary sitemap and now dealing with a nested sitemap?
	 * @param  bool|null  $is_child_blog  Is this routine being called from a child blog?
	 *
	 * @return bool `TRUE` if there was no failure fetching XML Sitemap, else `FALSE`. This also creates a dashboard notice in some cases.
	 *
	 * @note  This routine is also used by the AutoCache class when the Auto-Cache Engine is running.
	 * @since 1.2.0
	 *
	 */
	public function autoCacheCheckXmlSitemap( $sitemap, $is_nested_sitemap = false, $is_child_blog = null ) {
		$failure = ''; // Initialize.

		if ( is_wp_error( $head = wp_remote_head( $sitemap, [ 'redirection' => 5, 'user-agent' => $this->options['auto_cache_user_agent'] . '; ' . MEGAOPTIM_RAPID_CACHE_GLOBAL_NS . ' ' . MEGAOPTIM_RAPID_CACHE_VERSION ] ) ) ) {
			$failure = 'WP_Http says: ' . $head->get_error_message() . '.';
			if ( mb_stripos( $head->get_error_message(), 'timed out' ) !== false || mb_stripos( $head->get_error_message(), 'timeout' ) !== false ) { // $head->get_error_code() only returns generic `http_request_failed`
				$failure .= '<br /><em>' . __( 'Note: Most timeout errors are resolved by refreshing the page and trying again. If timeout errors persist, please see <a href="http://cometcache.com/r/kb-article-why-am-i-seeing-a-timeout-error/" target="_blank">this article</a>.', 'rapid-cache' ) . '</em>';
			}
		} elseif ( empty( $head['response']['code'] ) || (int) $head['response']['code'] >= 400 ) {
			$failure = sprintf( __( 'HEAD response code (<code>%1$s</code>) indicates an error.', 'rapid-cache' ), esc_html( (int) @$head['response']['code'] ) );
		} elseif ( empty( $head['headers']['content-type'] ) || mb_stripos( $head['headers']['content-type'], 'xml' ) === false ) {
			$failure = sprintf( __( 'Content-Type (<code>%1$s</code>) indicates an error.', 'rapid-cache' ), esc_html( (string) @$head['headers']['content-type'] ) );
		}
		if ( $failure ) { // Failure encountered above?
			if ( ! $is_child_blog && ! $is_nested_sitemap && $this->options['auto_cache_sitemap_url'] ) { // If this is a primary sitemap location.
				$this->dismissMainNotice( 'xml_sitemap_missing' ); // Clear any previous XML Sitemap notice, which may reference an old URL; see http://wsharks.com/1SAofhP
				$this->enqueueMainNotice(
					sprintf( __( '<strong>%1$s says...</strong> The Auto-Cache Engine is currently configured with an XML Sitemap location that could not be found. We suggest that you install the <a href="http://cometcache.com/r/google-xml-sitemaps-plugin/" target="_blank">Google XML Sitemaps</a> plugin. Or, empty the XML Sitemap field and only use the list of URLs instead. See: <strong>Dashboard → %1$s → Auto-Cache Engine → XML Sitemap URL</strong>', 'rapid-cache' ), esc_html( NAME ) ) . '</p><hr />' .
					sprintf( __( '<p><strong>Problematic Sitemap URL:</strong> <a href="%1$s" target="_blank">%1$s</a> / <strong>Diagnostic Report:</strong> %2$s', 'rapid-cache' ), esc_html( $sitemap ), $failure ),
					[ 'class' => 'error', 'persistent_key' => 'xml_sitemap_missing', 'dismissable' => false ]
				);
				delete_transient( MEGAOPTIM_RAPID_CACHE_GLOBAL_NS . '-' . md5( $this->options['auto_cache_sitemap_url'] ) ); // Ensures that we check the XML Sitemap URL again immediately until the issue is fixed
			}

			return false; // Nothing more we can do in this case.
		}

		if ( ! $is_child_blog && ! $is_nested_sitemap ) { // Any previous problems have been fixed; dismiss any existing failure notice
			$this->dismissMainNotice( 'xml_sitemap_missing' );
			set_transient( MEGAOPTIM_RAPID_CACHE_GLOBAL_NS . '-' . md5( $this->options['auto_cache_sitemap_url'] ), time(), WEEK_IN_SECONDS ); // Reduce repeated validation attempts.
		}

		return true;
	}

	/**
	 * Download XML Sitemap via WP HTTP API and store in a temp file.
	 *
	 * @param  string  $url  to XML Sitemap that needs to be downloaded
	 *
	 * @return string Path to downloaded XML Sitemap file
	 * @throws \Exception If unable to write temporary file or download XML Sitemap
	 *
	 * @since 1.2.0
	 *
	 */
	public function autoCacheWpRemoteGetXmlSitemap( $url ) {
		if ( ! ( $tmp_dir = $this->getTmpDir() ) ) {
			throw new \Exception( __( 'No writable tmp directory.', 'rapid-cache' ) );
		}
		$tmp_file = tempnam( $tmp_dir, 'rapid-cache-' ) . '.xml';

		$response = wp_remote_get( $url, [ 'user-agent' => $this->plugin->options['auto_cache_user_agent'] . '; ' . MEGAOPTIM_RAPID_CACHE_GLOBAL_NS . ' ' . MEGAOPTIM_RAPID_CACHE_VERSION ] );
		if ( $response && ! is_wp_error( $response ) ) {
			file_put_contents( $tmp_file, $response['body'] );
		} else {
			throw new \Exception( sprintf( __( 'Failed to download XML Sitemap: `%1$s`.', 'rapid-cache' ), $response->get_error_message() ) );
		}

		return $tmp_file;
	}
}