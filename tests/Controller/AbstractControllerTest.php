<?php
namespace Gwa\Wordpress\Zero\Test\Controller;

use Gwa\Wordpress\Zero\Timber\MockeryTimberBridge;
use Gwa\Wordpress\Zero\Test\WpBridge\MockeryWpBridge;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class AbstractControllerTest extends MockeryTestCase
{
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
            ->mock();

        $controller = new MyController;
        $controller->setTimberBridge($bridge);

        $post = $controller->getPost();
    }

    public function testGetPostForArgs(): void
    {
        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getPost')
            ->with(['foo' => 'bar'], '\MyPostClass')
            ->mock();

        $controller = new MyController;
        $controller->setTimberBridge($bridge);

        $post = $controller->getPostForArgs(['foo' => 'bar'], '\MyPostClass');
    }

    public function testGetPosts(): void
    {
        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getPosts')
            ->with(false, \Timber\Post::class, false)
            ->mock();

        $controller = new MyController;
        $controller->setTimberBridge($bridge);

        $post = $controller->getPosts();
    }

    public function testGetPostsForArgs(): void
    {
        $bridge = new MockeryTimberBridge();
        $bridge->mock()
            ->shouldReceive('getPosts')
            ->with(['foo' => 'bar'], '\MyPostClass', 'collection')
            ->mock();

        $controller = new MyController;
        $controller->setTimberBridge($bridge);

        $post = $controller->getPostsForArgs(['foo' => 'bar'], '\MyPostClass', 'collection');
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
    }
}
