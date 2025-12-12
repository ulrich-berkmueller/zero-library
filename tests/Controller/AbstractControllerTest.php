<?php
namespace Gwa\Wordpress\Zero\Test\Controller;

use Gwa\Wordpress\Zero\Timber\MockeryTimberBridge;
use Gwa\Wordpress\Zero\Test\WpBridge\MockeryWpBridge;
use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class AbstractControllerTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    public function testConstruct(): void
    {
        $controller = new MyController;
        $this->assertInstanceOf('Gwa\Wordpress\Zero\Controller\AbstractController', $controller);
    }

    public function testCacheMode(): void
    {
        $controller = new MyController;

        $controller->setCacheMode('transient');
        $controller->setCacheExpiresSeconds(60);

        $this->assertEquals('transient', $controller->getCacheMode());
        $this->assertEquals(60, $controller->getCacheExpiresSeconds());
    }

    public function testGetPost(): void
    {
        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getPost')
            ->with(false, \Timber\Post::class)
            ->andReturn(['dummy-post'])
            ->mock();

        $controller = new MyController;
        $controller->setTimberBridge($bridge);

        $post = $controller->getPost();
        $this->assertSame(['dummy-post'], $post);
    }

    public function testGetPostForArgs(): void
    {
        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getPost')
            ->with(['foo' => 'bar'], '\MyPostClass')
            ->andReturn(['dummy-post'])
            ->mock();

        $controller = new MyController;
        $controller->setTimberBridge($bridge);

        $post = $controller->getPostForArgs(['foo' => 'bar'], '\MyPostClass');
        $this->assertSame(['dummy-post'], $post);
    }

    public function testGetPosts(): void
    {
        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getPosts')
            ->with(false, \Timber\Post::class, false)
            ->andReturn(['p1', 'p2'])
            ->once()
            ->mock();

        $controller = new MyController();
        $controller->setTimberBridge($bridge);

        $posts = $controller->getPosts();
        $this->assertSame(['p1', 'p2'], $posts);
    }

    public function testGetPostsForArgs(): void
    {
        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getPosts')
            ->with(['foo' => 'bar'], '\MyPostClass', 'collection')
            ->andReturn(['p1'])
            ->mock();

        $controller = new MyController();
        $controller->setTimberBridge($bridge);

        $posts = $controller->getPostsForArgs(['foo' => 'bar'], '\MyPostClass', 'collection');
        $this->assertSame(['p1'], $posts);
    }

    public function testRender(): void
    {
        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getContext')
            ->andReturn([])
            ->shouldReceive('render')
            ->with([], [], false, 'default')
            ->mock();

        $MockBridge = new MockeryWpBridge;
        $MockBridge->mock()
            ->shouldReceive('addFilter')
            ->mock();

        $controller = new MyController;
        $controller->setTimberBridge($bridge);
        $controller->setWpBridge($MockBridge);

        $controller->render();
        $this->addToAssertionCount(1);
    }
}
