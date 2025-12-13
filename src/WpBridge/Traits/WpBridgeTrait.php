<?php

namespace Gwa\Wordpress\Zero\WpBridge\Traits;

use Gwa\Wordpress\Zero\WpBridge\Contracts\WpBridgeInterface;
use Gwa\Wordpress\Zero\WpBridge\WpBridge;

/**
 * Trait to be used by all classes that use WpBridge.
 */
trait WpBridgeTrait
{
    /**
     * WpBridge instance.
     *
     * @var WpBridgeInterface
     */
    private $wpbridge;

    /**
     * Set WpBridge.
     */
    public function setWpBridge(WpBridgeInterface $wpbridge)
    {
        $this->wpbridge = $wpbridge;

        return $this;
    }

    /**
     * Get WpBridge.
     *
     * @return WpBridgeInterface
     */
    public function getWpBridge()
    {
        return $this->wpbridge ?? WpBridge::instance();
    }
}
