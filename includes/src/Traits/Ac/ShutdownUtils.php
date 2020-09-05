<?php
namespace MegaOptim\RapidCache\Traits\Ac;

use MegaOptim\RapidCache\Classes;

trait ShutdownUtils
{
    /**
     * Registers a shutdown flag.
     *
     * @since 1.0.0
     *
     * @note In `/wp-settings.php`, Rapid Cache is loaded before WP registers its own shutdown function.
     * Therefore, this flag is set before {@link shutdown_action_hook()} fires, and thus before {@link wp_ob_end_flush_all()}.
     *
     * @see http://www.php.net/manual/en/function.register-shutdown-function.php
     */
    public function registerShutdownFlag()
    {
        register_shutdown_function(
            function () {
                $GLOBALS[MEGAOPTIM_RAPID_CACHE_GLOBAL_NS.'_shutdown_flag'] = -1;
            }
        );
    }
}
