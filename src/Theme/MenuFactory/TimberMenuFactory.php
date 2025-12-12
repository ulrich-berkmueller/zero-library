<?php

namespace Gwa\Wordpress\Zero\Theme\MenuFactory;

use Timber\Menu;

class TimberMenuFactory implements MenuFactoryContract
{
    /**
     * @param string $slug
     * @return TimberMenu
     * @codeCoverageIgnore
     */
    public function create($slug)
    {
        return new Menu($slug);
    }
}
