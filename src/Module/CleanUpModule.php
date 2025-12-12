<?php
namespace Gwa\Wordpress\Zero\Module;

use Gwa\Wordpress\WpBridge\Traits\WpBridgeTrait;
use Gwa\Wordpress\Zero\Module\AbstractThemeModule;

class CleanUpModule extends AbstractThemeModule
{
    use WpBridgeTrait;

    /**
     * The default WordPress head is a mess. Let's clean it up.
     */
    public function wpHeadCleanup()
    {
        // index link
        $this->getWpBridge()->removeAction('wp_head', 'index_rel_link');
        // previous link
        $this->getWpBridge()->removeAction('wp_head', 'parent_post_rel_link', 10, 0);
        // start link
        $this->getWpBridge()->removeAction('wp_head', 'start_post_rel_link', 10, 0);
        // remove WP version from css
        $this->getWpBridge()->addFilter('style_loader_src', [$this, 'removeWpVerCssJs'], 9999);
        // remove Wp version from scripts
        $this->getWpBridge()->addFilter('script_loader_src', [$this, 'removeWpVerCssJs'], 9999);
    }

    public function removeRssVersion()
    {
        return '';
    }

    public function removeWpVerCssJs($src)
    {
        if (strpos($src, 'ver=')) {
            $src = $this->getWpBridge()->removeQueryArg('ver', $src);
        }

        return $src;
    }

    /**
     * Clean the output of attributes of images in editor.
     * Courtesy of SitePoint. http://www.sitepoint.com/wordpress-change-img-tag-html/
     *
     * @param string $class
     * @param string $id
     * @param string $align
     * @param string $size
     *
     * @return string
     */
    public function imageTagClassClean($class, $id, $align, $size)
    {
        return 'align'.esc_attr($align);
    }

    /**
     * Remove width and height in editor, for a better responsive world.
     *
     * @param string $html
     * @param string $id
     * @param string $alt
     * @param string $title
     *
     * @return string
     */
    public function imageEditorRemoveHightAndWidth($html, $id, $alt, $title)
    {
        return preg_replace([
            '/\s+width="\d+"/i',
            '/\s+height="\d+"/i',
            '/alt=""/i'
        ], [
            '',
            '',
            '',
            'alt="'.$title.'"'
        ], $html);
    }

    /**
     * Remove image attributes
     *
     * @param  string $html
     *
     * @return string
     */
    public function removeImageAttributes($html)
    {
        $html = preg_replace('/(width|height)="\d*"\s/', '', $html);

        return $html;
    }

    public function shortcodeParagraphFix($content)
    {
        // Suchen und Ersetzen Strings festlegen
        $array = [
            '<p>[' => '[',
            ']</p>' => ']',
            ']<br />' => ']'
        ];

        return strtr($content, $array);
    }

    protected function getActionMap()
    {
        return [
            [
                'hooks'  => 'init',
                'class'  => $this,
                'method' => 'wpHeadCleanup',
                'prio'   => 10,
                'args'   => 1,
            ],
        ];
    }

    /**
     * Override in concrete subclass.
     *
     * @return array
     */
    protected function getFilterMap()
    {
        return [
            [
                'hooks'  => 'the_generator',
                'class'  => $this,
                'method' => 'removeRssVersion',
                'prio'   => 10,
                'args'   => 1,
            ], [
                'hooks'  => 'get_image_tag_class',
                'class'  => $this,
                'method' => 'imageTagClassClean',
                'prio'   => 0,
                'args'   => 4,
            ], [
                'hooks'  => 'get_image_tag',
                'class'  => $this,
                'method' => 'imageEditorRemoveHightAndWidth',
                'prio'   => 0,
                'args'   => 4,
            ], [
                'hooks'  => 'the_content',
                'class'  => $this,
                'method' => 'shortcodeParagraphFix',
                'prio'   => 10,
                'args'   => 1,
            ], [
                'hooks'  => ['post_thumbnail_html', 'removeImageAttributes'],
                'class'  => $this,
                'method' => 'removeImageAttributes',
                'prio'   => 10,
                'args'   => 1,
            ],
        ];
    }
}
