<?php
namespace MegaOptim\RapidCache\Traits\Plugin;

use MegaOptim\RapidCache\Classes;

trait CleanupUtils
{
    /**
     * Runs cleanup routine via CRON job.
     *
     * @since 151002 While working on directory stats.
     *
     * @attaches-to `'_cron_'.__GLOBAL_NS__.'_cleanup'`
     */
    public function cleanupCache()
    {
        if (!$this->options['enable']) {
            return; // Nothing to do.
        }
        

        
        $this->wurgeCache(); // Purge now.
    }
}
