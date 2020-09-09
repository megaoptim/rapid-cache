<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */

if ( ! defined('WPINC')) {
    exit('Do NOT access this file directly.');
}

require_once dirname(__FILE__).'/requirements.php';

$checker = rapid_cache_get_requirements_checker();
$issue   = $checker->detectIssue();
if (is_null($issue)) {
    require_once __DIR__.'/includes/uninstall.php';
}
