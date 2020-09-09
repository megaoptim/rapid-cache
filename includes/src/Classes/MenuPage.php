<?php
/**
 * This file is part of Rapid Cache
 *
 * @license GPLv3 (See LICENSE.txt for more details)
 * @copyright 2020 MegaOptim (https://megaoptim.com)
 * @copyright 2016 WP Sharks (https://wpsharks.com/)
 */
namespace MegaOptim\RapidCache\Classes;

use MegaOptim\RapidCache\Classes;

/**
 * Menu Page.
 *
 * @since 1.0.0
 */
class MenuPage extends AbsBase
{
    /**
     * Constructor.
     *
     * @since 1.0.0
     *
     * @param string $menu_page Menu page.
     */
    public function __construct($menu_page = '')
    {
        parent::__construct();

        if ($menu_page) {
            switch ($menu_page) {
                case 'options':
                    new Classes\MenuPageOptions();
                    break;

                
            }
        }
    }
}
