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

trait I18nUtils
{
    /**
     * `X file` or `X files`, translated w/ singlular/plural context.
     *
     * @since 1.0.0
     *
     * @param int $counter Total files; i.e. the counter.
     *
     * @return string The phrase `X file` or `X files`.
     */
    public function i18nFiles($counter)
    {
        $counter = (integer) $counter;
        return sprintf(_n('%1$s file', '%1$s files', $counter, 'rapid-cache'), $counter);
    }

    /**
     * `X directory` or `X directories`, translated w/ singlular/plural context.
     *
     * @since 1.0.0
     *
     * @param int $counter Total directories; i.e. the counter.
     *
     * @return string The phrase `X directory` or `X directories`.
     */
    public function i18nDirs($counter)
    {
        $counter = (integer) $counter;
        return sprintf(_n('%1$s directory', '%1$s directories', $counter, 'rapid-cache'), $counter);
    }

    /**
     * `X file/directory` or `X files/directories`, translated w/ singlular/plural context.
     *
     * @since 1.0.0
     *
     * @param int $counter Total files/directories; i.e. the counter.
     *
     * @return string The phrase `X file/directory` or `X files/directories`.
     */
    public function i18nFilesDirs($counter)
    {
        $counter = (integer) $counter;
        return sprintf(_n('%1$s file/directory', '%1$s files/directories', $counter, 'rapid-cache'), $counter);
    }
}
