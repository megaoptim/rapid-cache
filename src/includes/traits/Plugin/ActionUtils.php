<?php
namespace MegaOptim\RapidCache\Traits\Plugin;

use MegaOptim\RapidCache\Classes;

trait ActionUtils
{
    /**
     * Plugin action handler.
     *
     * @since 150422 Rewrite.
     *
     * @attaches-to `wp_loaded` hook.
     */
    public function actions()
    {
        if (!empty($_REQUEST[GLOBAL_NS])) {
            new Classes\Actions();
        }
        
    }
}
