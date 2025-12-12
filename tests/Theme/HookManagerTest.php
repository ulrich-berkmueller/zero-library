<?php

namespace Gwa\Wordpress\Zero\Test\Theme;

use Gwa\Wordpress\Zero\Theme\HookManager;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class HookManagerTest extends TestCase
{
    public function testConstruct(): void
    {
        $instance = new HookManager();
        $this->assertInstanceOf('Gwa\Wordpress\Zero\Theme\HookManager', $instance);
    }
}
