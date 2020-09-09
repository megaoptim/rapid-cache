<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */
namespace MegaOptim\RapidCache\Classes;

use MegaOptim\RapidCache\Traits;

/**
 * Class AdvancedCache
 * @package MegaOptim\RapidCache\Classes
 * @since 1.0.0
 */
class AdvancedCache extends AbsBaseAp
{
    /*[.build.php-auto-generate-use-Traits]*/
    use Traits\Ac\AbortUtils;
    use Traits\Ac\ClientSideUtils;
    use Traits\Ac\NcDebugUtils;
    use Traits\Ac\ObUtils;
    use Traits\Ac\PostloadUtils;
    use Traits\Ac\ShutdownUtils;
    /*[/.build.php-auto-generate-use-Traits]*/

    /**
     * Microtime.
     *
     * @since 1.0.0
     *
     * @type float Microtime.
     */
    public $timer = 0;

    /**
     * True if running.
     *
     * @since 1.0.0
     *
     * @type bool True if running.
     */
    public $is_running = false;

    /**
     * Class constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();

        if (!defined('RAPID_CACHE_AC_FILE_VERSION')) {
            return; // Missing; wait for update.
        } elseif (RAPID_CACHE_AC_FILE_VERSION !== MEGAOPTIM_RAPID_CACHE_VERSION) {
            return; // Version mismatch; wait for update.
            //
        } elseif (!defined('WP_CACHE') || !WP_CACHE || !RAPID_CACHE_ENABLE) {
            return; // Not enabled in `wp-config.php` or otherwise.
        } elseif (defined('WP_INSTALLING') || defined('RELOCATE')) {
            return; // Not applicable; installing and/or relocating.
            //
        } elseif (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) {
            return; // Not applicable; bypass API requests.
        } elseif (defined('REST_REQUEST') && REST_REQUEST) {
            return; // Not applicable; bypass API requests.
        }
        // Note: `REST_REQUEST` is only here as a way of future-proofing the software.
        // Ideally, we could catch all API requests here to avoid any overhead in processing.
        // I suspect this will be the case in a future release of WordPress.

        // For now, `REST_REQUEST` is not defined by WP until later in the `parse_request` phase.
        // Therefore, this check by itself is not enough to avoid all REST requests at this time.
        // See: `traits/Ac/ObUtils.php` for additional checks for `REST_REQUEST` API calls.

        // `XMLRPC_REQUEST` on the other hand, is set very early via `xmlrpc.php`. So no issue.
        // -------------------------------------------------------------------------------------------------------------

        $this->is_running = true;
        $this->timer      = microtime(true);

        $this->registerShutdownFlag();
        $this->maybeIgnoreUserAbort();
        $this->maybeStopBrowserCaching();
        
        $this->maybeStartOutputBuffering();
    }
}
