<?php

declare(strict_types=1);

namespace WpdbEngineForLatitude\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use WpdbEngineForLatitude\Parameter;
use WpdbEngineForLatitude\WpdbEngine;

class ParameterTest extends TestCase
{
    protected $engine;

    protected function setUp(): void
    {
        $this->engine = new WpdbEngine();
    }

    public function testBool()
    {
        $param = new Parameter(true);

        $this->assertSame('true', $param->sql($this->engine));
        $this->assertSame([], $param->params($this->engine));

        $param = new Parameter(false);

        $this->assertSame('false', $param->sql($this->engine));
        $this->assertSame([], $param->params($this->engine));
    }

    public function testNull()
    {
        $param = new Parameter(null);

        $this->assertSame('NULL', $param->sql($this->engine));
        $this->assertSame([], $param->params($this->engine));
    }

    public function testString()
    {
        $param = new Parameter('test-string');

        $this->assertSame('%s', $param->sql($this->engine));
        $this->assertSame(['test-string'], $param->params($this->engine));
    }

    public function testInt()
    {
        $param = new Parameter(5);

        $this->assertSame('%d', $param->sql($this->engine));
        $this->assertSame([5], $param->params($this->engine));
    }

    public function testFloat()
    {
        $param = new Parameter(7.5);

        $this->assertSame('%f', $param->sql($this->engine));
        $this->assertSame([7.5], $param->params($this->engine));
    }

    public function testUnsupportedType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('must be one of bool|float|int|string|null');

        new Parameter(new stdClass());
    }
}
