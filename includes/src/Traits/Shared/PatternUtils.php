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

trait PatternUtils
{
    /**
     * Convert line-delimited patterns to a regex.
     *
     * @since 1.0.0
     *
     * @param string $patterns Line-delimited list of patterns.
     *
     * @return string A `/(?:list|of|regex)/i` patterns.
     */
    public function lineDelimitedPatternsToRegex($patterns)
    {
        $regex    = ''; // Initialize list of regex patterns.
        $patterns = (string) $patterns;

        if (($patterns = preg_split('/['."\r\n".']+/', $patterns, -1, PREG_SPLIT_NO_EMPTY))) {
            $regex = '/(?:'.implode('|', array_map([$this, 'wdRegexToActualRegexFrag'], $patterns)).')/i';
        }
        return $regex;
    }

    /**
     * Convert watered-down regex to actual regex.
     *
     * @since 1.0.0
     *
     * @param string $string Input watered-down regex to convert.
     *
     * @return string Actual regex pattern after conversion.
     */
    public function wdRegexToActualRegexFrag($string)
    {
        return preg_replace(
            [
                '/\\\\\^/u',
                '/\\\\\*\\\\\*/u',
                '/\\\\\*/u',
                '/\\\\\$/u',
            ],
            [
                '^', // Beginning of line.
                '.*?', // Zero or more chars.
                '[^\/]*?', // Zero or more chars != /.
                '$', // End of line.
            ],
            preg_quote((string) $string, '/')
        );
    }
}
