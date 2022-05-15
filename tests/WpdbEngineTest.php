<?php

namespace WpLatitudeBridge\Tests;

use PHPUnit\Framework\TestCase;
use WpLatitudeBridge\InsertQuery;
use WpLatitudeBridge\UpdateQuery;
use WpLatitudeBridge\WpdbEngine;

class WpdbEngineTest extends TestCase
{
    public function testMakeUpdate()
    {
        $engine = new WpdbEngine();

        $this->assertInstanceOf(UpdateQuery::class, $engine->makeUpdate());
    }

    public function testMakeInsert()
    {
        $engine = new WpdbEngine();

        $this->assertInstanceOf(InsertQuery::class, $engine->makeInsert());
    }
}
