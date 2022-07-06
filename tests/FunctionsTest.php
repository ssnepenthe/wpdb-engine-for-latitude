<?php

declare(strict_types=1);

namespace WpdbEngineForLatitude\Tests;

use InvalidArgumentException;
use Latitude\QueryBuilder\Builder\CriteriaBuilder as LatitudeCriteriaBuilder;
use Latitude\QueryBuilder\Builder\LikeBuilder as LatitudeLikeBuilder;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use WpdbEngineForLatitude\CriteriaBuilder;
use WpdbEngineForLatitude\LikeBuilder;
use WpdbEngineForLatitude\Parameter;
use WpdbEngineForLatitude\WpdbEngine;

use function WpdbEngineForLatitude\field;
use function WpdbEngineForLatitude\param;
use function WpdbEngineForLatitude\paramAll;
use function WpdbEngineForLatitude\search;

class FunctionsTest extends TestCase
{
    protected $engine;

    protected function setUp(): void
    {
        $this->engine = new WpdbEngine();
    }

    public function testField()
    {
        $builder = field('total');

        $this->assertInstanceOf(CriteriaBuilder::class, $builder);

        $statement = $this->getStatement($builder);

        $this->assertSame('`total`', $statement->sql($this->engine));
        $this->assertSame([], $statement->params($this->engine));
    }

    public function testParam()
    {
        $param = param(15);

        $this->assertInstanceOf(Parameter::class, $param);

        $this->assertSame('%d', $param->sql($this->engine));
        $this->assertSame([15], $param->params($this->engine));

        $this->assertSame($param, param($param));
    }

    public function testParamAll()
    {
        $params = paramAll([1, 'john']);

        $this->assertIsArray($params);

        foreach ($params as $param) {
            $this->assertInstanceOf(Parameter::class, $param);
        }
    }

    public function testSearch()
    {
        $builder = search('first_name');

        $this->assertInstanceOf(LikeBuilder::class, $builder);

        $statement = $this->getStatement($builder);

        $this->assertSame('`first_name`', $statement->sql($this->engine));
        $this->assertSame([], $statement->params($this->engine));
    }

    protected function getStatement($value)
    {
        // Don't hate...
        if (is_subclass_of($value, LatitudeCriteriaBuilder::class)) {
            $class = LatitudeCriteriaBuilder::class;
        } elseif (\is_subclass_of($value, LatitudeLikeBuilder::class)) {
            $class = LatitudeLikeBuilder::class;
        } else {
            throw new InvalidArgumentException();
        }

        $ref = new ReflectionProperty($class, 'statement');
        $ref->setAccessible(true);

        return $ref->getValue($value);
    }
}
