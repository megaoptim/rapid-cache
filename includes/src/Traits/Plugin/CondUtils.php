<?php
namespace MegaOptim\RapidCache\Traits\Plugin;

use MegaOptim\RapidCache\Classes;

trait CondUtils
{
    /**
     * Is pro preview?
     *
     * @since 1.0.0
     *
     * @return bool `TRUE` if it's a pro preview.
     */
    public function isProPreview()
    {
        return !empty($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_pro_preview']);
    }
}
