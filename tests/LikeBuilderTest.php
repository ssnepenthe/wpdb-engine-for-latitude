<?php

namespace WpLatitudeBridge\Tests;

use PHPUnit\Framework\TestCase;
use WpLatitudeBridge\LikeBuilder;
use WpLatitudeBridge\WpdbEngine;

use function Latitude\QueryBuilder\identify;

class LikeBuilderTest extends TestCase
{
    protected $engine;
    protected $likeBuilder;

    protected function setUp(): void
    {
        $this->engine = new WpdbEngine();
        $this->likeBuilder = new LikeBuilder(identify('first_name'));
    }

    public function testBegins()
    {
        $criteria = $this->likeBuilder->begins('john');

        $this->assertSame('"first_name" LIKE %s', $criteria->sql($this->engine));
        $this->assertSame(['john%'], $criteria->params($this->engine));
    }

    public function testNotBegins()
    {
        $criteria = $this->likeBuilder->notBegins('john');

        $this->assertSame('"first_name" NOT LIKE %s', $criteria->sql($this->engine));
        $this->assertSame(['john%'], $criteria->params($this->engine));
    }

    public function testContains()
    {
        $criteria = $this->likeBuilder->contains('john');

        $this->assertSame('"first_name" LIKE %s', $criteria->sql($this->engine));
        $this->assertSame(['%john%'], $criteria->params($this->engine));
    }

    public function testNotContains()
    {
        $criteria = $this->likeBuilder->notContains('john');

        $this->assertSame('"first_name" NOT LIKE %s', $criteria->sql($this->engine));
        $this->assertSame(['%john%'], $criteria->params($this->engine));
    }

    public function testEnds()
    {
        $criteria = $this->likeBuilder->ends('john');

        $this->assertSame('"first_name" LIKE %s', $criteria->sql($this->engine));
        $this->assertSame(['%john'], $criteria->params($this->engine));
    }

    public function testNotEnds()
    {
        $criteria = $this->likeBuilder->notEnds('john');

        $this->assertSame('"first_name" NOT LIKE %s', $criteria->sql($this->engine));
        $this->assertSame(['%john'], $criteria->params($this->engine));
    }
}
