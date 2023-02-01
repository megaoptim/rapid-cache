<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */

namespace MegaOptim\RapidCache\Traits\Ac;

trait AbortUtils {
	/**
	 * Ignores user aborts; when/if the Auto-Cache Engine is running.
	 *
	 * @since 1.0.0
	 */
	public function maybeIgnoreUserAbort() {
		if ( $this->isAutoCacheEngine() ) {
			ignore_user_abort( true );
		}
	}
}
