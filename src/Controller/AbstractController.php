<?php

namespace Gwa\Wordpress\Zero\Controller;

use Gwa\Wordpress\Zero\Timber\Traits\TimberBridgeTrait;
use Gwa\Wordpress\Zero\Traits\HasTheme;
use Gwa\Wordpress\Zero\WpBridge\Contracts\WpBridgeAwareInterface;
use Gwa\Wordpress\Zero\WpBridge\Traits\WpBridgeTrait;
use Timber\Loader;
use Timber\Post;
use Timber\Timber;

abstract class AbstractController
{
    use TimberBridgeTrait;
    use WpBridgeTrait;
    use HasTheme;

    protected $cacheType = [
        'none' => Loader::CACHE_NONE,
        'object' => Loader::CACHE_OBJECT,
        'transient' => Loader::CACHE_TRANSIENT,
        'site.transient' => Loader::CACHE_SITE_TRANSIENT,
        'default' => Loader::CACHE_USE_DEFAULT,
    ];

    /**
     * @var int
     */
    protected $cacheExpiresSeconds;

    /**
     * @var string
     */
    protected $cacheMode = Loader::CACHE_USE_DEFAULT;

    /**
     * @param string $mode
     *
     * @return self
     */
    public function setCacheMode($mode = 'default')
    {
        $this->cacheMode = $this->cacheType[$mode];

        return $this;
    }

    /**
     * @return string
     */
    public function getCacheMode()
    {
        return $this->cacheType[$this->cacheMode];
    }

    /**
     * @param int $seconds
     *
     * @return self
     */
    public function setCacheExpiresSeconds($seconds)
    {
        $this->cacheExpiresSeconds = $seconds;

        return $this;
    }

    /**
     * @return int
     */
    public function getCacheExpiresSeconds()
    {
        return $this->cacheExpiresSeconds;
    }

    /**
     * @return null|array|array<string,string|\Timber>
     */
    abstract public function getContext();

    /**
     * @return string[]
     */
    abstract public function getTemplates();

    /**
     * @return null|bool|\Timber\Post
     */
    public function getPost()
    {
        $post = $this->getTimberBridge()->getPost(); // Timber::get_post(...)
        $this->addWpBridgeToPost($post);
        return $post;
    }

    /**
     * @param string[] $args
     *
     * @return null|bool|\Timber\Post
     */
    public function getPostForArgs($args)
    {
        $post = $this->getTimberBridge()->getPost($args); // Timber::get_post(...)
        $this->addWpBridgeToPost($post);
        return $post;
    }

    /**
     * @param bool   $collection
     *
     * @return null|bool|array
     */
    public function getPosts()
    {
        $options = [];
        $posts = $this->getTimberBridge()->getPosts(false, $options); // Timber::get_posts(...)
        $this->addWpBridgeToPosts($posts);
        return $posts;
    }

    /**
     * @param string[] $args
     * @param bool     $collection
     *
     * @return null|bool|array
     */
    public function getPostsForArgs($args)
    {
        $options = [];
        $posts = $this->getTimberBridge()->getPosts($args, $options); // Timber::get_posts(...)
        $this->addWpBridgeToPosts($posts);
        return $posts;
    }

    /**
     *  Render template.
     *
     * @return void|null|bool|string
     */
    public function render()
    {
        // FIXME: no hook anymore to automatically inject wp bridge
        // $this->getWpBridge()->addFilter('timber_post_getter_get_posts', [$this, 'addWpBridgeToPosts'], 10, 3);

        $context = $this->getContext();
        $templates = $this->getTemplates();

        $this->validateTemplates($templates);
        $this->validateContext($context);

        $this->getTimberBridge()->render(
            $templates,
            array_merge($this->getTimberBridge()->getContext(), $context),
            $this->getCacheExpiresSeconds() ?: false, // False disables cache altogether.
            $this->getCacheMode()
        );
    }


    public function addWpBridgeToPost($post)
    {
        if ($post instanceof Post) {
            $post->setup();
        }
        if ($post instanceof WpBridgeAwareInterface) {
            $post->setWpBridge($this->getWpBridge());
        }
        return $post;
    }

    public function addWpBridgeToPosts(iterable $posts)
    {
        foreach ($posts as $post) {
            if ($post instanceof Post) {
                $post->setup();
            }
            if ($post instanceof WpBridgeAwareInterface) {
                $post->setWpBridge($this->getWpBridge());
            }
        }
        return $posts;
    }

    /**
     * Check if context is a array.
     *
     * @param null|array $context
     */
    protected function validateContext($context)
    {
        if (!is_array($context)) {
            throw new \LogicException('::getContext should return a array');
        }
    }

    /**
     * Check if getTemplates is a array and template file exist.
     *
     * @param string[] $templates
     */
    protected function validateTemplates($templates)
    {
        if (!is_array($templates)) {
            throw new \LogicException('::getTemplates should return a array');
        }
    }

    /**
     * Returns basename of template set for current page.
     * Useful for setting templates based on template slug.
     *
     * @return string
     */
    protected function getTemplateSlug()
    {
        return $this->getWpBridge()->getPageTemplateSlug();
    }
}
