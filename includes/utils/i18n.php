<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */

namespace MegaOptim\RapidCache {

    /**
     * Polyfill for {@link \__()}.
     *
     * @since 1.0.0
     *
     * @param string $string      String to translate.
     * @param string $text_domain Plugin text domain.
     *
     * @return string Possibly translated string.
     */
    function __($string, $text_domain)
    {
        static $exists; // Cache.

        if ($exists || ($exists = function_exists('__'))) {
            return \__($string, $text_domain);
        }
        return $string; // Not possible (yet).
    }
}
namespace MegaOptim\RapidCache\Traits\Ac {

    function __($string, $text_domain)
    {
        return \MegaOptim\RapidCache\__($string, $text_domain);
    }
}
namespace MegaOptim\RapidCache\Traits\Shared {

    function __($string, $text_domain)
    {
        return \MegaOptim\RapidCache\__($string, $text_domain);
    }
}
