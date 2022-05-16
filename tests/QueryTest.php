<?php

namespace WpLatitudeBridge\Tests;

use PHPUnit\Framework\TestCase;
use WpLatitudeBridge\InsertQuery;
use WpLatitudeBridge\UpdateQuery;
use WpLatitudeBridge\WpdbEngine;

class QueryTest extends TestCase
{
    protected $engine;

    protected function setUp(): void
    {
        $this->engine = new WpdbEngine();
    }

    public function testInsertQuery()
    {
        // Tests ->values() indirectly via ->map().
        $query = new InsertQuery($this->engine);
        $query->into('places');
        $query->map([
            'name' => 'home',
            'address' => '123 Main St',
        ]);

        $expression = $query->asExpression();

        $this->assertSame(
            'INSERT INTO `places` (`name`, `address`) VALUES (%s, %s)',
            $expression->sql($this->engine)
        );
        $this->assertSame(['home', '123 Main St'], $expression->params($this->engine));
    }

    public function testUpdateQuery()
    {
        $query = new UpdateQuery($this->engine);
        $query->table('places');
        $query->set(['address' => '555 Money Ave']);

        $expression = $query->asExpression();

        $this->assertSame('UPDATE `places` SET `address` = %s', $expression->sql($this->engine));
        $this->assertSame(['555 Money Ave'], $expression->params($this->engine));
    }
}
