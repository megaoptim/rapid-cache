<?php
namespace MegaOptim\RapidCache\Traits\Plugin;

use MegaOptim\RapidCache\Classes;

trait DbUtils
{
    /**
     * WordPress database instance.
     *
     * @since 1.0.0
     *
     * @return \wpdb Reference for IDEs.
     */
    public function wpdb()
    {
        return $GLOBALS['wpdb'];
    }
}
