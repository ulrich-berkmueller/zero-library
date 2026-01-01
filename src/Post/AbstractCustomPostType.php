<?php

namespace Gwa\Wordpress\Zero\Post;

use Gwa\Wordpress\Zero\Traits\AddCustomControl;
use PostTypes\PostType;
use PostTypes\Taxonomy;

abstract class AbstractCustomPostType
{
    use AddCustomControl;

    /**
     * @var string
     */
    protected $textdomain;

    /**
     * @param string $textdomain
     */
    final public function init($textdomain)
    {
        $this->textdomain = $textdomain;

        $post = $this->createPostType();

        if ($settings = $this->getTaxonomySettings()) {
            if (is_array($settings) && isset($settings['name'])) {
                $taxonomy = $this->createTaxonomy($settings);
                $taxonomy->posttype($this->getPostType());
                $post->taxonomy($taxonomy->name);
                $taxonomy->register();
            }
        }

        $this->addExtra();

        $post->register();
    }

    /**
     * @return PostType
     */
    protected function createPostType()
    {
        $options = array_merge(
            [
                'supports' => $this->getSupports(),
            ],
            $this->getOptions()
        );

        $names = [
            'name'     => $this->getPostType(),
            'singular' => $this->getSingular(),
            'plural'   => $this->getPlural(),
            'slug'     => $this->getSlug(),
        ];

        $post = new PostType($names, $options);

        $post->icon($this->getIcon());

        return $post;
    }

    /**
     * @return Taxonomy
     */
    protected function createTaxonomy(array $settings)
    {
        $names = [
            'name'     => $settings['name'],
            'singular' => $settings['singular'] ?? $settings['name'],
            'plural'   => $settings['plural'] ?? $settings['name'],
            'slug'     => $settings['slug'] ?? $settings['name'],
        ];
        $options = $settings['options'] ?? [];
        $labels = $settings['labels'] ?? [];

        $taxonomy = new Taxonomy($names, $options, $labels);

        return $taxonomy;
    }

    // -------- ABSTRACT METHODS --------

    /**
     * @return string
     */
    abstract public function getPostType();

    /**
     * @return string
     */
    abstract public function getSingular();

    /**
     * @return string
     */
    abstract public function getPlural();

    /**
     * @return string
     *
     * @see https://developer.wordpress.org/resource/dashicons/
     */
    abstract public function getIcon();

    // -------- OVERRIDE METHODS ---------

    /**
     * Defaults to slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->getPostType();
    }

    /**
     * @return string
     */
    public function getTextDomain()
    {
        return $this->textdomain;
    }

    /**
     * @return string[]
     */
    public function getSupports()
    {
        return [
            'title',
            'editor',
            'thumbnail',
            'page-attributes',
        ];
    }

    /**
     * @return string[]
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @return null|array
     */
    public function getTaxonomySettings()
    {
        return null;
    }

    public function addExtra()
    {
        // hook for subclass
    }
}
