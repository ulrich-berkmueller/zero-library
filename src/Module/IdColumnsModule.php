<?php
namespace Gwa\Wordpress\Zero\Module;

use Gwa\Wordpress\Zero\WpBridge\Traits\WpBridgeTrait;
use Gwa\Wordpress\Zero\Module\AbstractThemeModule;

class IdColumnsModule extends AbstractThemeModule
{
    use WpBridgeTrait;

    /**
     * Add id columns to column posts
     *
     * @param array $defaults
     */
    public function addColumnId($defaults)
    {
        $defaults['date column-id'] = __('ID');
        return $defaults;
    }

    /**
     * Add id columns to custom column post
     *
     * @param string $columnName
     * @param string $id
     */
    public function addColumnIdValue($columnName, $id)
    {
        if ($columnName === 'date column-id') {
            echo $id;
        }
    }

    /**
    * Return the ID for the column
    */
    public function addColumnReturnValue($value, $columName, $id)
    {
        if ($columName === 'date column-id') {
            $value = $id;
        }

        return $value;
    }

    /**
     * Adds a id column on all admin pages
     */
    public function addIdColumn()
    {
        foreach (get_taxonomies() as $taxonomy) {
            $this->getWpBridge()->addAction("manage_edit-{$taxonomy}_columns", [$this, 'addColumnId']);
            $this->getWpBridge()->addFilter("manage_{$taxonomy}_custom_column", [$this, 'addColumnReturnValue'], 10, 3);
            $this->getWpBridge()->addFilter("manage_edit-{$taxonomy}_sortable_columns", [$this, 'addColumnId']);
        }

        foreach (get_post_types() as $ptype) {
            $this->getWpBridge()->addAction("manage_edit-{$ptype}_columns", [$this, 'addColumnId']);
            $this->getWpBridge()->addFilter("manage_{$ptype}_posts_custom_column", [$this, 'addColumnIdValue'], 10, 3);
            $this->getWpBridge()->addFilter("manage_edit-{$ptype}_sortable_columns", [$this, 'addColumnId']);
        }

        $this->getWpBridge()->addAction('manage_media_custom_column', [$this, 'addColumnIdValue'], 10, 2);
        $this->getWpBridge()->addAction('manage_link_custom_column', [$this, 'addColumnId'], 10, 2);
        $this->getWpBridge()->addAction('manage_edit-link-categories_columns', [$this, 'addColumnId']);
        $this->getWpBridge()->addAction('manage_users_columns', [$this, 'addColumnId']);
        $this->getWpBridge()->addAction('manage_edit-comments_columns', [$this, 'addColumnId']);
        $this->getWpBridge()->addAction('manage_comments_custom_column', [$this, 'addColumnIdValue'], 10, 2);

        $this->getWpBridge()->addFilter('manage_media_columns', [$this, 'addColumnId']);
        $this->getWpBridge()->addFilter('manage_link-manager_columns', [$this, 'addColumnId']);
        $this->getWpBridge()->addFilter('manage_link_categories_custom_column', [$this, 'addColumnReturnValue'], 10, 3);
        $this->getWpBridge()->addFilter('manage_users_custom_column', [$this, 'addColumnReturnValue'], 10, 3);
        $this->getWpBridge()->addFilter('manage_edit-comments_sortable_columns', [$this, 'addColumnId']);
    }

    protected function getActionMap()
    {
        return [
            [
                'hooks'  => 'admin_init',
                'class'  => $this,
                'method' => 'addIdColumn',
                'prio'   => 199,
                'args'   => 1,
            ],
        ];
    }
}
