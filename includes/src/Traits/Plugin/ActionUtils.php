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

trait ActionUtils {
	/**
	 * Plugin action handler.
	 *
	 * @since 1.0.0
	 *
	 * @attaches-to `wp_loaded` hook.
	 */
	public function actions() {
		if ( ! empty( $_REQUEST[ MEGAOPTIM_RAPID_CACHE_GLOBAL_NS ] ) ) {
			new Classes\Actions();
		}

		if ( ! empty( $_REQUEST[ MEGAOPTIM_RAPID_CACHE_GLOBAL_NS . '_auto_cache_cron' ] ) ) {
			$this->autoCache();
			exit();
		}

	}
}
