<?php
/*
Plugin Name: Rapid Cache
Plugin URI: https://megaoptim.com/tools/rapid-cache
Description: Rapid Cache is a fork of Comet Cache, an advanced WordPress caching plugin inspired by simplicity
Author: MegaOptim
Author URI: https://megaoptim.com
Version: 1.2.1
Text Domain: rapid-cache
Domain Path: /languages
*/

if ( ! defined('WPINC')) {
    exit('Do NOT access this file directly.');
}

require_once plugin_dir_path(__FILE__).'requirements.php';

$checker = rapid_cache_get_requirements_checker();
$issue = $checker->detectIssue();

if (is_null($issue)) {
    require_once dirname(__FILE__).'/includes/plugin.php';
} else {
    $checker->output($issue);
}
