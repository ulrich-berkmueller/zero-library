<?php

namespace Gwa\Wordpress\Zero\Theme;

use Gwa\Wordpress\Zero\WpBridge\Contracts\WpBridgeAwareInterface;
use Gwa\Wordpress\Zero\WpBridge\Traits\WpBridgeTrait;

/**
 * Used to register filters and actions from theme settings and modules.
 */
class HookManager
{
    use WpBridgeTrait;

    /**
     * @var array
     */
    private $instances = [];

    /**
     * @param string        $action
     * @param object|string $class
     * @param string        $method
     * @param int           $prio
     * @param int           $args
     */
    public function addAction($action, $class, $method, $prio = 10, $args = 1)
    {
        $this->initHook(
            [
                'hooks' => $action,
                'class' => $class,
                'method' => $method,
                'prio' => $prio,
                'args' => $args,
            ],
            'action'
        );
    }

    public function addActions(array $actions)
    {
        $this->initHooks($actions, 'action');
    }

    /**
     * @param string        $action
     * @param object|string $class
     * @param string        $method
     * @param int           $prio
     * @param int           $args
     */
    public function addFilter($action, $class, $method, $prio = 10, $args = 1)
    {
        $this->initHook(
            [
                'hooks' => $action,
                'class' => $class,
                'method' => $method,
                'prio' => $prio,
                'args' => $args,
            ],
            'filter'
        );
    }

    public function addFilters(array $filters)
    {
        $this->initHooks($filters, 'filter');
    }

    // ----------------

    private function initHooks(array $map, $hookkey)
    {
        foreach ($map as $hook) {
            $this->initHook($hook, $hookkey);
        }
    }

    private function initHook(array $settings, $hookkey)
    {
        $action = 'add'.ucfirst($hookkey);

        $hooks = is_array($settings['hooks']) ? $settings['hooks'] : [$settings['hooks']];

        $classarg = $settings['class'];
        $method = $settings['method'] ?? $hookkey;

        $prio = isset($settings['prio']) ? (int) $settings['prio'] : 10;
        $args = isset($settings['args']) ? (int) $settings['args'] : 1;

        $instance = $this->getClassInstance($classarg);

        foreach ($hooks as $hook) {
            call_user_func(
                [$this->getWpBridge(), $action],
                $hook,
                [$instance, $method],
                $prio,
                $args
            );
        }
    }

    /**
     * @param object|string $classarg
     *
     * @return object
     */
    private function getClassInstance($classarg)
    {
        if (is_object($classarg)) {
            return $classarg;
        }

        if (!array_key_exists($classarg, $this->instances)) {
            $class = new $classarg();

            if ($class instanceof WpBridgeAwareInterface) {
                $class->setWpBridge($this->getWpBridge());
            }

            $this->instances[$classarg] = $class;
        }

        return $this->instances[$classarg];
    }
}
