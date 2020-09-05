<?php
namespace MegaOptim\RapidCache\Traits\Plugin;

use MegaOptim\RapidCache\Classes;

trait ActionUtils
{
    /**
     * Plugin action handler.
     *
     * @since 1.0.0
     *
     * @attaches-to `wp_loaded` hook.
     */
    public function actions()
    {
        if (!empty($_REQUEST[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS])) {
            new Classes\Actions();
        }
        
    }
}
