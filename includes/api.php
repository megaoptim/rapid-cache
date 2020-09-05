<?php
/**
 * API Classes.
 *
 * @since 1.0.0
 */
namespace MegaOptim\RapidCache;

use MegaOptim\RapidCache\Classes;

if (!defined('WPINC')) {
    exit('Do NOT access this file directly.');
}

class_alias(__NAMESPACE__.'\\Classes\\ApiBase', MEGAOPTIM_RAPID_CACHE_GLOBAL_NS);