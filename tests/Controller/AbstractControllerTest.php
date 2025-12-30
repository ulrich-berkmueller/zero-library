<?php

namespace Gwa\Wordpress\Zero\Test\Controller;

use Gwa\Wordpress\Zero\Test\WpBridge\MockeryWpBridge;
use Gwa\Wordpress\Zero\Timber\MockeryTimberBridge;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Timber\Post;

/**
 * @internal
 */
class AbstractControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testConstruct(): void
    {
        $controller = new MyController();
        $this->assertInstanceOf('Gwa\Wordpress\Zero\Controller\AbstractController', $controller);
    }

    public function testCacheMode(): void
    {
        $controller = new MyController();

        $controller->setCacheMode('transient');
        $controller->setCacheExpiresSeconds(60);

        $this->assertEquals('transient', $controller->getCacheMode());
        $this->assertEquals(60, $controller->getCacheExpiresSeconds());
    }

    public function testGetPost(): void
    {
        $obj = new \stdClass();

        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getPost')
            ->with()
            ->andReturn($obj)
            ->mock()
        ;

        $controller = new MyController();
        $controller->setTimberBridge($bridge);

        $post = $controller->getPost();
        $this->assertSame($obj, $post);
    }

    public function testGetPostForArgs(): void
    {
        $obj = new \stdClass();

        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getPost')
            ->with(['foo' => 'bar'])
            ->andReturn($obj)
            ->mock()
        ;

        $controller = new MyController();
        $controller->setTimberBridge($bridge);

        $post = $controller->getPostForArgs(['foo' => 'bar']);
        $this->assertSame($obj, $post);
    }

    public function testGetPosts(): void
    {
        $obj1 = new \stdClass();
        $obj2 = new \stdClass();

        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getPosts')
            ->with(false, [])
            ->andReturn([$obj1, $obj2])
            ->mock()
        ;

        $controller = new MyController();
        $controller->setTimberBridge($bridge);

        $posts = $controller->getPosts();
        $this->assertSame([$obj1, $obj2], $posts);
    }

    public function testGetPostsForArgs(): void
    {
        $obj1 = new \stdClass();

        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getPosts')
            ->with(['foo' => 'bar'], [])
            ->andReturn([$obj1])
            ->mock()
        ;

        $controller = new MyController();
        $controller->setTimberBridge($bridge);

        $posts = $controller->getPostsForArgs(['foo' => 'bar']);
        $this->assertSame([$obj1], $posts);
    }

    public function testRender(): void
    {
        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getContext')
            ->andReturn([])
            ->shouldReceive('render')
            ->with([], [], false, 'default')
            ->mock()
        ;

        $MockBridge = new MockeryWpBridge();
        $MockBridge->mock()
            ->shouldReceive('addFilter')
            ->mock()
        ;

        $controller = new MyController();
        $controller->setTimberBridge($bridge);
        $controller->setWpBridge($MockBridge);

        $controller->render();
        $this->addToAssertionCount(1);
    }
}
