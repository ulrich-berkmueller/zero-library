<?php

namespace Gwa\Wordpress\Zero\WpBridge\Contracts;

interface WpBridgeAwareInterface
{
    /**
     * Set WpBridge.
     */
    public function setWpBridge(WpBridgeInterface $wpbridge);

    /**
     * Get WpBridge.
     *
     * @return WpBridge
     */
    public function getWpBridge();
}
