<?php

namespace Gwa\Wordpress\Zero\Helper;

class ErrorReportingHelper
{
    public static function runWithoutDeprecationWarnings(callable $callable)
    {
        $errorReportingWas = error_reporting();
        error_reporting($errorReportingWas & ~E_DEPRECATED & ~E_USER_DEPRECATED);

        $result = $callable();

        error_reporting($errorReportingWas);
        return $result;
    }
}
