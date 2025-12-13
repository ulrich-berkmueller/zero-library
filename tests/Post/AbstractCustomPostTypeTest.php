<?php

namespace Gwa\Wordpress\Zero\Test\Post;

use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
class AbstractCustomPostTypeTest extends TestCase
{
    public function testConstruct(): void
    {
        $instance = new MyCustomPostType();
        $this->assertInstanceOf('Gwa\Wordpress\Zero\Post\AbstractCustomPostType', $instance);
    }
}
