<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */

namespace MegaOptim\RapidCache;

use MegaOptim\RapidCache\Classes;

if ( ! defined('WPINC')) {
    exit('Do NOT access this file directly.');
}
require_once dirname(__FILE__).'/stub.php';

$GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_uninstalling'] = true; // Needs to be set before calling Conflicts class

if ( ! Classes\Conflicts::check()) {
    $GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS] = new Classes\Plugin(false);
    $GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS]->uninstall();
}
