<?php
namespace Gwa\Wordpress\Zero\WpBridge;

use Gwa\Wordpress\Zero\WpBridge\Contracts\WpBridgeInterface;

/**
 * Allows us to use a class to call methods in the global namespace.
 * Methods should be called in camelcase.
 *
 * To call
 * wp_get_attachment_image_src(...);
 * use
 * $bridge->wpGetAttachmentImageSrc(...);
 */
class WpBridge implements WpBridgeInterface
{
    /**
     * Magic call on all camel wordpress functions.
     *
     * @param string $function
     * @param array  $args
     *
     * @return mixed
     */
    public function __call($function, $args)
    {
        return call_user_func_array($this->camelToUnderscore($function), $args);
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
