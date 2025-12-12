<?php

namespace Gwa\Wordpress\Zero\WpBridge\Contracts;

interface WpBridgeInterface
{
    public function __call($function, $args);
}
