<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */
namespace MegaOptim\RapidCache\Traits\Plugin;

use MegaOptim\RapidCache\Classes;

trait BbPressUtils
{
    /**
     * Is bbPress active?
     *
     * @since 1.0.0
     *
     * @return bool `TRUE` if bbPress is active.
     */
    public function isBbPressActive()
    {
        return class_exists('bbPress');
    }

    /**
     * bbPress post types.
     *
     * @since 1.0.0
     *
     * @return array All bbPress post types.
     */
    public function bbPressPostTypes()
    {
        if (!$this->isBbPressActive()) {
            return [];
        }
        if (!is_null($types = &$this->cacheKey('bbPressPostTypes'))) {
            return $types; // Already did this.
        }
        $types   = []; // Initialize.
        $types[] = bbp_get_forum_post_type();
        $types[] = bbp_get_topic_post_type();
        $types[] = bbp_get_reply_post_type();

        return $types;
    }

    /**
     * bbPress post statuses.
     *
     * @since 1.0.0
     *
     * @return array All bbPress post statuses.
     */
    public function bbPressStatuses()
    {
        if (!$this->isBbPressActive()) {
            return [];
        }
        if (!is_null($statuses = &$this->cacheKey('bbPressStatuses'))) {
            return $statuses; // Already did this.
        }
        $statuses = []; // Initialize.

        foreach (get_post_stati([], 'objects') as $_key => $_status) {
            if (isset($_status->label_count['domain']) && $_status->label_count['domain'] === 'bbpress') {
                $statuses[] = $_status->name;
            }
        }
        unset($_key, $_status); // Housekeeping.

        return $statuses;
    }
}
