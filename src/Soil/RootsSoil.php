<?php

namespace Gwa\Wordpress\Zero\Soil;

class RootsSoil
{
    /**
     * All soil theme func.
     *
     * @var array
     */
    protected $soilFunc = [
        'clean-up' => true,
        'disable-rest-api' => false,
        'disable-asset-versioning' => true,
        'disable-trackbacks' => true,
        'js-to-footer' => true,
        'nav-walker' => true,
        'nice-search' => true,
        'relative-urls' => true,
    ];

    protected $google = [
        'boot' => false,
        'user' => '',
    ];

    /**
     * Change the standard configs.
     *
     * @return self
     */
    public function changeOptions(array $soil)
    {
        $this->soilFunc = array_merge($this->soilFunc, $soil);

        return $this;
    }

    /**
     * Add google analytics.
     *
     * @param bool   $boot
     * @param string $user
     */
    public function addGoogleAnalytics($boot = false, $user = '')
    {
        $this->google['boot'] = $boot;
        $this->google['user'] = $user;
    }

    /**
     * Active soil thme supports.
     */
    public function init()
    {
        foreach ($this->soilFunc as $key => $value) {
            if (true === $value) {
                add_theme_support('soil', $key);
            }
        }

        $google = $this->google;

        if (is_bool($google['boot']) && true === $google['boot']) {
            add_theme_support('soil', ['google-analytics' => $google['user']]);
        }
    }
}
