<?php
if ( ! defined('WPINC')) {
    exit('Do NOT access this file directly.');
}

require_once dirname(__FILE__).'/requirements.php';

$checker = rapid_cache_get_requirements_checker();
$issue   = $checker->detectIssue();
if (is_null($issue)) {
    require_once __DIR__.'/src/includes/uninstall.php';
}
