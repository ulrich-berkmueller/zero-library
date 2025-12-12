<?php

namespace Gwa\Wordpress\Zero\Test\Module;

use Gwa\Wordpress\Zero\Test\WpBridge\MockeryWpBridge;
use Gwa\Wordpress\Zero\Theme\HookManager;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class AbstractModuleTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    private $bridge;
    private $hookmanager;
    private $instance;

    protected function setUp(): void
    {
        $this->bridge = new MockeryWpBridge();
        $this->hookmanager = new HookManager();
        $this->hookmanager->setWpBridge($this->bridge);
        $this->instance = new MyModule();
    }

    // ----------------

    public function testConstruct(): void
    {
        $this->assertInstanceOf('Gwa\Wordpress\Zero\Module\AbstractThemeModule', $this->instance);
    }

    public function testBasicModule(): void
    {
        $module = new BasicModule();
        $module->init($this->bridge, [], $this->hookmanager);

        $this->assertIsArray($module->getContext());

        $context = $module->getContext();

        $this->assertEmpty($context);
        $this->assertEquals('basic', $module->getSlug());
    }

    public function testInit(): void
    {
        $this->instance->init($this->bridge, [], $this->hookmanager);
        $this->assertTrue($this->instance->isinit);
    }

    public function testGetContext(): void
    {
        $this->assertIsArray($this->instance->getContext());
    }
}
