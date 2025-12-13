<?php

namespace Gwa\Wordpress\Zero\Test\Theme;

use Gwa\Wordpress\Zero\Test\WpBridge\MockeryWpBridge;
use Gwa\Wordpress\Zero\Theme\MenuFactory\MockMenuFactory;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class AbstractThemeTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    private $bridge;
    private $instance;

    protected function setUp(): void
    {
        $this->bridge = new MockeryWpBridge();
        $this->instance = new MyTheme();
        $this->instance->setMenuFactory(new MockMenuFactory());
        $this->instance->setWpBridge($this->bridge);
    }

    // ----------------

    public function testConstruct(): void
    {
        $this->assertInstanceOf('Gwa\Wordpress\Zero\Theme\AbstractTheme', $this->instance);
    }

    public function testGetEnvironment(): void
    {
        $this->assertEquals('production', $this->instance->getEnvironment());
    }

    public function testDevelopmentEnvironmentNotIndexable(): void
    {
        $bridge = new MockeryWpBridge();

        $theme = new BasicTheme('development');
        $theme->setMenuFactory(new MockMenuFactory());
        $theme->setWpBridge($bridge);
        $theme->init();

        $filters = $bridge->getAddedFilters();

        $this->assertEquals('timber_context', $filters[0]->filtername);
        $this->assertEquals('pre_option_blog_public', $filters[1]->filtername);
    }

    public function testGetTextDomain(): void
    {
        $this->assertEquals('mytheme', $this->instance->getTextDomain());
    }

    public function testTranslation(): void
    {
        $this->assertEquals('foo', $this->instance->__('foo'));
    }

    public function testAddThemeLangSupport(): void
    {
        $this->bridge->mock()
            ->shouldReceive('getTemplateDirectory')
            ->andReturn('/foo')
            ->once()
            ->shouldReceive('loadThemeTextdomain')
            ->with('mytheme', '/foo/languages')
            ->once()
            ->mock()
        ;
        $this->instance->addThemeLangSupport();
    }

    public function testInit(): void
    {
        $this->mockBridgeForInit();
        $this->instance->init();

        $this->assertTrue($this->instance->isinit);
    }

    public function testGetContext(): void
    {
        $this->mockBridgeForInit();
        $this->instance->init();

        $data = $this->instance->addToContext([]);

        $this->assertIsArray($data);
    }

    public function testGetDefaultMenuFactory(): void
    {
        $this->instance = new MyTheme();
        $this->assertInstanceOf('Gwa\Wordpress\Zero\Theme\MenuFactory\TimberMenuFactory', $this->instance->getMenuFactory());
    }

    public function testCreateController(): void
    {
        $controller = $this->instance->createController('Gwa\Wordpress\Zero\Test\Controller\MyController');

        $this->assertInstanceOf('Gwa\Wordpress\Zero\Test\Controller\MyController', $controller);
        $this->assertSame($this->instance, $controller->getTheme());
        $this->assertSame($this->instance->getWpBridge(), $controller->getWpBridge());
        $this->assertSame($this->instance->getTimberBridge(), $controller->getTimberBridge());
    }

    // ---------

    private function mockBridgeForInit()
    {
        $this->bridge->mock()
            ->shouldReceive('getTemplateDirectory')
            ->andReturn('/my/path')
            ->shouldReceive('addImageSize')
            ->with('thumbnail', 300, 300, true)
            ->shouldReceive('registerNavMenus')
            ->mock()
        ;
    }
}
