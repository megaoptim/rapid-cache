<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */
namespace MegaOptim\RapidCache\Traits\Shared;

use MegaOptim\RapidCache\Classes;

trait ArrayUtils
{
    /**
     * Sorts by key.
     *
     * @since 1.0.0
     *
     * @param array $array Input array.
     * @param int   $flags Defaults to `SORT_REGULAR`.
     *
     * @return array Output array.
     */
    public function ksortDeep($array, $flags = SORT_REGULAR)
    {
        $array = (array) $array;
        $flags = (int) $flags;

        ksort($array, $flags);

        foreach ($array as $_key => &$_value) {
            if (is_array($_value)) {
                $_value = $this->ksortDeep($_value, $flags);
            }
        } // unset($_key, $_value); // Housekeeping.

        return $array;
    }
}
