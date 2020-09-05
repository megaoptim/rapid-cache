<?php
/*
Version: 170220
Text Domain: rapid-cache
Plugin Name: Rapid Cache
Network: true

Author: WP Sharks
Author URI: https://rapid-cache.com

Plugin URI: https://rapidcache.com/
Description: Rapid Cache is an advanced WordPress caching plugin inspired by simplicity.
*/

/*
Plugin Name: Rapid Cache
Plugin URI: https://megaoptim.com/tools/rapid-cache
Description: Rapid Cache is fork of Comet Cache and advanced WordPress caching plugin inspired by simplicity
Author: MegaOptim
Author URI: https://megaoptim.com
Version: 1.0.0
Text Domain: rapid-cache
Domain Path: /languages
*/

if ( ! defined('WPINC')) {
    exit('Do NOT access this file directly.');
}

define('MEGAOPTIM_RAPID_CACHE_URL', plugin_dir_url(__FILE__));
define('MEGAOPTIM_RAPID_CACHE_PATH', trailingslashit(plugin_dir_path(__FILE__)));

require_once MEGAOPTIM_RAPID_CACHE_PATH.'requirements.php';

$checker = rapid_cache_get_requirements_checker();

$issue = $checker->detectIssue();

if (is_null($issue)) {
    require_once dirname(__FILE__).'/src/includes/plugin.php';
} else {
    $checker->output($issue);
}
