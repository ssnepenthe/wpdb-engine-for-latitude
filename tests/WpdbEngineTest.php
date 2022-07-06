<?php

declare(strict_types=1);

namespace WpdbEngineForLatitude\Tests;

use PHPUnit\Framework\TestCase;
use WpdbEngineForLatitude\InsertQuery;
use WpdbEngineForLatitude\UpdateQuery;
use WpdbEngineForLatitude\WpdbEngine;

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
