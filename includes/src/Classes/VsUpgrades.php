<?php
namespace MegaOptim\RapidCache\Classes;

/**
 * Version-Specific Upgrades.
 *
 * @since 1.0.0
 */
class VsUpgrades extends AbsBase
{
    /**
     * @type string Version they are upgrading from.
     *
     * @since 1.0.0
     */
    protected $prev_version = '';

    /**
     * Class constructor.
     *
     * @since 1.0.0
     *
     * @param string $prev_version Version they are upgrading from.
     */
    public function __construct($prev_version)
    {
        parent::__construct();

        $this->prev_version = (string) $prev_version;
        $this->runHandlers(); // Run upgrade(s).
    }

    /**
     * Runs upgrade handlers in the proper order.
     *
     * @since 1.0.0
     */
    protected function runHandlers()
    {
        // $this->fromVersionNumber()
        // ...
    }

}
