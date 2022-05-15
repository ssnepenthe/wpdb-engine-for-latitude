<?php

namespace WpLatitudeBridge\Tests;

use PHPUnit\Framework\TestCase;
use WpLatitudeBridge\LikeBegins;
use WpLatitudeBridge\LikeContains;
use WpLatitudeBridge\LikeEnds;
use WpLatitudeBridge\WpdbEngine;

class LikeTest extends TestCase
{
    protected $engine;

    protected function setUp(): void
    {
        $this->engine = new WpdbEngine();
    }

    public function testLikeBegins()
    {
        $statement = new LikeBegins('john');

        $this->assertSame('%s', $statement->sql($this->engine));
        $this->assertSame(['john%'], $statement->params($this->engine));
    }

    public function testLikeContains()
    {
        $statement = new LikeContains('john');

        $this->assertSame('%s', $statement->sql($this->engine));
        $this->assertSame(['%john%'], $statement->params($this->engine));
    }

    public function testLikeEnds()
    {
        $statement = new LikeEnds('john');

        $this->assertSame('%s', $statement->sql($this->engine));
        $this->assertSame(['%john'], $statement->params($this->engine));
    }
}
