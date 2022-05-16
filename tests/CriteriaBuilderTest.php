<?php

namespace WpLatitudeBridge\Tests;

use PHPUnit\Framework\TestCase;
use WpLatitudeBridge\CriteriaBuilder;
use WpLatitudeBridge\WpdbEngine;

use function Latitude\QueryBuilder\identify;

class CriteriaBuilderTest extends TestCase
{
    protected $engine;

    protected function setUp(): void
    {
        $this->engine = new WpdbEngine();
    }

    public function testBetween()
    {
        $builder = new CriteriaBuilder(identify('quantity'));
        $criteria = $builder->between(4, 7);

        $this->assertSame('`quantity` BETWEEN %d AND %d', $criteria->sql($this->engine));
        $this->assertSame([4, 7], $criteria->params($this->engine));
    }

    public function testNotBetween()
    {
        $builder = new CriteriaBuilder(identify('quantity'));
        $criteria = $builder->notBetween(2, 9);

        $this->assertSame('`quantity` NOT BETWEEN %d AND %d', $criteria->sql($this->engine));
        $this->assertSame([2, 9], $criteria->params($this->engine));
    }

    public function testIn()
    {
        $builder = new CriteriaBuilder(identify('quantity'));
        $criteria = $builder->in(4, 5, 6);

        $this->assertSame('`quantity` IN (%d, %d, %d)', $criteria->sql($this->engine));
        $this->assertSame([4, 5, 6], $criteria->params($this->engine));
    }

    public function testNotIn()
    {
        $builder = new CriteriaBuilder(identify('quantity'));
        $criteria = $builder->notIn(7, 8, 9, 10);

        $this->assertSame('`quantity` NOT IN (%d, %d, %d, %d)', $criteria->sql($this->engine));
        $this->assertSame([7, 8, 9, 10], $criteria->params($this->engine));
    }

    public function testEq()
    {
        $builder = new CriteriaBuilder(identify('quantity'));
        $criteria = $builder->eq(14);

        $this->assertSame('`quantity` = %d', $criteria->sql($this->engine));
        $this->assertSame([14], $criteria->params($this->engine));
    }

    public function testNotEq()
    {
        $builder = new CriteriaBuilder(identify('quantity'));
        $criteria = $builder->notEq(19);

        $this->assertSame('`quantity` != %d', $criteria->sql($this->engine));
        $this->assertSame([19], $criteria->params($this->engine));
    }

    public function testGt()
    {
        $builder = new CriteriaBuilder(identify('quantity'));
        $criteria = $builder->gt(6);

        $this->assertSame('`quantity` > %d', $criteria->sql($this->engine));
        $this->assertSame([6], $criteria->params($this->engine));
    }

    public function testGte()
    {
        $builder = new CriteriaBuilder(identify('quantity'));
        $criteria = $builder->gte(9);

        $this->assertSame('`quantity` >= %d', $criteria->sql($this->engine));
        $this->assertSame([9], $criteria->params($this->engine));
    }

    public function testLt()
    {
        $builder = new CriteriaBuilder(identify('quantity'));
        $criteria = $builder->lt(9);

        $this->assertSame('`quantity` < %d', $criteria->sql($this->engine));
        $this->assertSame([9], $criteria->params($this->engine));
    }

    public function testLte()
    {
        $builder = new CriteriaBuilder(identify('quantity'));
        $criteria = $builder->lte(6);

        $this->assertSame('`quantity` <= %d', $criteria->sql($this->engine));
        $this->assertSame([6], $criteria->params($this->engine));
    }
}
