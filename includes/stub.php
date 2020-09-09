<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */

namespace MegaOptim\RapidCache;

if ( ! defined('WPINC')) {
    exit('Do NOT access this file directly.');
}

define('MEGAOPTIM_RAPID_CACHE_SHORT_NAME', 'RC');
define('MEGAOPTIM_RAPID_CACHE_NAME', 'Rapid Cache');
define('MEGAOPTIM_RAPID_CACHE_DOMAIN', 'someurl.com');
define('MEGAOPTIM_RAPID_CACHE_GLOBAL_NS', 'rapid_cache');
define('MEGAOPTIM_RAPID_CACHE_SLUG', 'rapid-cache');
define('MEGAOPTIM_RAPID_CACHE_VERSION', '1.0.0');
define('MEGAOPTIM_RAPID_CACHE_PATH', dirname(__DIR__).DIRECTORY_SEPARATOR);
define('MEGAOPTIM_RAPID_CACHE_PLUGIN_FILE', MEGAOPTIM_RAPID_CACHE_PATH.MEGAOPTIM_RAPID_CACHE_SLUG.'.php');
define('MEGAOPTIM_RAPID_CACHE_NS_PATH', str_replace('\\', '/', __NAMESPACE__));
define('MEGAOPTIM_RAPID_CACHE_IS_PRO', mb_strtolower(basename(MEGAOPTIM_RAPID_CACHE_NS_PATH)) === 'pro');

require_once dirname(__DIR__).'/vendor/autoload.php';
