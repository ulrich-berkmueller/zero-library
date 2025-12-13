<?php

namespace Gwa\Wordpress\Zero\Theme\MenuFactory;

use Timber\Menu;
use Timber\Timber;

class TimberMenuFactory implements MenuFactoryContract
{
    /**
     * @param string $slug
     *
     * @return null|Menu
     */
    public function create($slug)
    {
        return Timber::get_menu($slug);
    }
}
