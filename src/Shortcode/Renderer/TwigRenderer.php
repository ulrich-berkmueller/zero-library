<?php

namespace Gwa\Wordpress\Zero\Shortcode\Renderer;

use Gwa\Wordpress\Zero\Shortcode\Contract\TemplateRendererInterface;

/**
 * Extend Twig\Environment to implement TemplateRendererInterface.
 * Required methods already exist on Twig\Environment.
 */
class TwigRenderer extends \Twig\Environment implements TemplateRendererInterface {}
