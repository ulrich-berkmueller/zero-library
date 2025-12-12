<?php

namespace Gwa\Wordpress\Zero\Shortcode;

use Gwa\Wordpress\Zero\Module\AbstractThemeModule;
use Gwa\Wordpress\Zero\WpBridge\Contracts\WpBridgeInterface;
use Gwa\Wordpress\Zero\WpBridge\Traits\WpBridgeTrait;

/**
 * Abstract class to be extended by all shortcodes.
 * Shortcodes should be inited from within a module.
 */
abstract class AbstractShortcode
{
    use WpBridgeTrait;

    /**
     * AbstractThemeModule.
     */
    private $module;

    /**
     * @param AbstractThemeModule $module
     */
    final public function init(WpBridgeInterface $bridge, $module = null)
    {
        $this->setWpBridge($bridge);
        $this->module = $module;
        $this->getWpBridge()->addShortcode($this->getShortcode(), [$this, 'render']);

        $this->doInit();
    }

    /**
     * Returns the unique shortcode "slug".
     *
     * @return string
     */
    abstract public function getShortcode();

    /**
     * Renders the final content.
     *
     * @param array  $atts
     * @param string $content
     *
     * @return string
     */
    abstract public function render($atts, $content = '');

    /**
     * @return null|AbstractThemeModule
     */
    final public function getModule()
    {
        return $this->module;
    }

    protected function doInit()
    {
        // override in concrete Shortcode class, if required.
    }

    /**
     * Override to set the default attributes for your shortcode.
     *
     * @return array
     */
    protected function getDefaultAtts()
    {
        return [];
    }

    /**
     * Helper method to merge atts passed to render with defaults
     * defined in `getDefaultAtts`.
     *
     * @param array $atts
     *
     * @return array
     */
    final protected function getNormedAtts($atts)
    {
        return $this->getWpBridge()->shortcodeAtts($this->getDefaultAtts(), $atts);
    }
}
