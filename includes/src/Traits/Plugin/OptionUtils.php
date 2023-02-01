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

trait OptionUtils
{
    /**
     * Restore default plugin options.
     *
     * @since 1.0.0.
     *
     * @return array Plugin options after update.
     */
    public function restoreDefaultOptions()
    {
        delete_site_option(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_options');
        $this->options = $this->default_options;
        return $this->getOptions();
    }

    /**
     * Get plugin options.
     *
     * @since 1.0.0.
     *
     * @param bool $intersect Discard options not present in $this->default_options
     * @param bool $refresh   Force-pull options directly from get_site_option()
     *
     * @return array Plugin options.
     *
     * @note The `$intersect` param should be `false` when this method is called by a VS upgrade routine.
     * Also `false` during inital startup or when upgrading. See: <https://git.io/viGIK>
     */
    public function getOptions($intersect = true, $refresh = false)
    {
        if (!($options = $this->options) || $refresh) {
            if (!is_array($options = get_site_option(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_options'))) {
                $options = []; // Force an array of options.
            }
            if (!$options && is_array($zencache_options = get_site_option('zencache_options'))) {
                $options                       = $zencache_options;
                $options['crons_setup']        = $this->default_options['crons_setup'];
                $options['latest_pro_version'] = $this->default_options['latest_pro_version'];
            }
        } // End the collection of all plugin options.

        $this->options = array_merge($this->default_options, $options);
        $this->options = $this->applyWpFilters(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_options', $this->options);
        $this->options = $intersect ? array_intersect_key($this->options, $this->default_options) : $this->options;
        $this->options = array_map('trim', array_map('strval', $this->options));

        $this->options['base_dir'] = trim($this->options['base_dir'], '\\/'." \t\n\r\0\x0B");
        if (!$this->options['base_dir'] || mb_strpos(basename($this->options['base_dir']), 'wp-') === 0) {
            $this->options['base_dir'] = $this->default_options['base_dir'];
        }
        return $this->options; // Plugin options.
    }

    /**
     * Update plugin options.
     *
     * @since 1.0.0.
     *
     * @param array $options   One or more new options.
     * @param bool  $intersect Discard options not present in $this->default_options
     *
     * @return array Plugin options after update.
     *
     * @note $intersect should be `false` when this method is called via a VS upgrade routine. See https://git.io/viGIK
     */
    public function updateOptions(array $options, $intersect = true)
    {
	    if (!empty($options['base_dir']) && $options['base_dir'] !== $this->options['base_dir']) {
            $this->tryErasingAllFilesDirsIn($this->wpContentBaseDirTo(''));
        }
        $this->options = array_merge($this->default_options, $this->options, $options);
        $this->options = $intersect ? array_intersect_key($this->options, $this->default_options) : $this->options;
        $this->options = array_map('trim', array_map('strval', $this->options));

        update_site_option(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_options', $this->options);

        return $this->getOptions($intersect);
    }


	/**
	 * Clean up third party (exported or migrated from legacy) plugin options
	 *
	 * @since 1.0.1
	 *
	 * @param array $options
	 *
	 * @return array
	 */
    public function cleanUpOptions(array $options) {
	    $excluded = array(
		    'crons_setup',
		    'crons_setup_on_namespace',
		    'last_pro_update_check',
		    'crons_setup_on_wp_with_schedules',
		    'version',
	    );
	    foreach($excluded as $key) {
		    if(isset($options[$key])) {
			    unset($options[$key]);
		    }
	    }
	    if(isset($options['base_dir'])) {
		    $options['base_dir'] = str_replace(MEGAOPTIM_RAPID_CACHE_OLD_SLUG, MEGAOPTIM_RAPID_CACHE_SLUG, $args['base_dir']);
	    }
	    return $options;
    }

	/**
	 * Refresh the plugin state after options update
	 *
	 * @since 1.0.1
	 *
	 * @param array $query_args
	 *
	 * @return void
	 */
    public function refreshAfterOptionsUpdate(&$query_args = array()) {

	    // Ensures `autoCacheMaybeClearPrimaryXmlSitemapError()` always validates the XML Sitemap when saving options (when applicable).
	    delete_transient(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'-'.md5($this->plugin->options['auto_cache_sitemap_url']));

	    $this->plugin->autoWipeCache(); // May produce a notice.

	    if ($this->plugin->options['enable']) {
		    if ( ! ($add_wp_cache_to_wp_config = $this->plugin->addWpCacheToWpConfig())) {
			    $query_args[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_wp_config_wp_cache_add_failure'] = '1';
		    }
		    if ($this->plugin->isApache() && ! ($add_wp_htaccess = $this->plugin->addWpHtaccess())) {
			    $query_args[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_wp_htaccess_add_failure'] = '1';
		    }
		    if ($this->plugin->isNginx() && $this->plugin->applyWpFilters(MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_wp_htaccess_nginx_notice', true)
		        && ( ! isset($_SERVER['WP_NGINX_CONFIG']) || $_SERVER['WP_NGINX_CONFIG'] !== 'done')) {
			    $query_args[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_wp_htaccess_nginx_notice'] = '1';
		    }
		    if ( ! ($add_advanced_cache = $this->plugin->addAdvancedCache())) {
			    $query_args[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_advanced_cache_add_failure'] = $add_advanced_cache === null ? 'advanced-cache' : '1';
		    }
		    if ($this->plugin->options['mobile_adaptive_salt_enable'] && !$this->plugin->maybePopulateUaInfoDirectory()) {
			    $query_args[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_ua_info_dir_population_failure'] = '1';
		    }

		    if ( ! $this->plugin->options['auto_cache_enable']) {
			    // Dismiss and check again on `admin_init` via `autoCacheMaybeClearPhpReqsError()`.
			    $this->plugin->dismissMainNotice('auto_cache_engine_minimum_requirements');
		    }
		    if ( ! $this->plugin->options['auto_cache_enable'] || ! $this->plugin->options['auto_cache_sitemap_url']) {
			    // Dismiss and check again on `admin_init` via `autoCacheMaybeClearPrimaryXmlSitemapError()`.
			    $this->plugin->dismissMainNotice('xml_sitemap_missing');
		    }
		    $this->plugin->updateBlogPaths(); // Multisite networks only.
	    } else {
		    if ( ! ($remove_wp_cache_from_wp_config = $this->plugin->removeWpCacheFromWpConfig())) {
			    $query_args[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_wp_config_wp_cache_remove_failure'] = '1';
		    }
		    if ($this->plugin->isApache() && ! ($remove_wp_htaccess = $this->plugin->removeWpHtaccess())) {
			    $query_args[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_wp_htaccess_remove_failure'] = '1';
		    }
		    if ( ! ($remove_advanced_cache = $this->plugin->removeAdvancedCache())) {
			    $query_args[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_advanced_cache_remove_failure'] = '1';
		    }
		    // Dismiss notice when disabling plugin.
		    $this->plugin->dismissMainNotice('xml_sitemap_missing');

		    // Dismiss notice when disabling plugin.
		    $this->plugin->dismissMainNotice('auto_cache_engine_minimum_requirements');
	    }
    }

}
