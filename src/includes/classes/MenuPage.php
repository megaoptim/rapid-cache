<?php
namespace MegaOptim\RapidCache\Classes;

use MegaOptim\RapidCache\Classes;

/**
 * Menu Page.
 *
 * @since 150422 Rewrite.
 */
class MenuPage extends AbsBase
{
    /**
     * Constructor.
     *
     * @since 150422 Rewrite.
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
