<?php

// @todo Add Tests

namespace WpLatitudeBridge;

use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\isStatement;

function field($name): CriteriaBuilder
{
    return new CriteriaBuilder(identify($name));
}

function param($value): StatementInterface
{
    return isStatement($value) ? $value : new Parameter($value);
}

/**
 * @return StatementInterface[]
 */
function paramAll(array $values): array
{
    return array_map(__NAMESPACE__ . '\\param', $values);
}

function search($name): LikeBuilder
{
    return new LikeBuilder(identify($name));
}
