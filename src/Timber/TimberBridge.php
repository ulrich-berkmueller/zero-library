<?php

namespace Gwa\Wordpress\Zero\Timber;

use Gwa\Wordpress\Zero\Helper\ErrorReportingHelper;
use Gwa\Wordpress\Zero\Timber\Contracts\TimberBridgeInterface;
use Timber\Timber;

/**
 * @codeCoverageIgnore
 */
class TimberBridge implements TimberBridgeInterface
{
    /**
     * @param string $function
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($function, $args = [])
    {
        return ErrorReportingHelper::runWithoutDeprecationWarnings(function () use ($function, $args) {
            return call_user_func_array([Timber::class, $this->camelToUnderscore($function)], $args);
        });
    }

    /**
     * Rename camelcase to underscore.
     *
     * @param string $string
     *
     * @return string
     */
    public function camelToUnderscore($string)
    {
        return strtolower(preg_replace('/([a-z])([A-Z0-9])/', '$1_$2', $string));
    }
}
