<?php
/**
 * Plugin.
 *
 * @since 1.0.0
 */

namespace MegaOptim\RapidCache;

use MegaOptim\RapidCache\Classes;

if ( ! defined('WPINC')) {
    exit('Do NOT access this file directly.');
}

require_once dirname(__FILE__).'/stub.php';

if ( ! Classes\Conflicts::check()) {
    $GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS] = new Classes\Plugin();
}
