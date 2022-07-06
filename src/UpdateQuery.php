<?php

declare(strict_types=1);

namespace WpdbEngineForLatitude;

use Latitude\QueryBuilder\Query\UpdateQuery as LatitudeUpdateQuery;
use Latitude\QueryBuilder\StatementInterface;

use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\identify;
use function Latitude\QueryBuilder\listing;

class UpdateQuery extends LatitudeUpdateQuery
{
    public function set(array $map): self
    {
        $this->set = listing(array_map(
            fn ($key, $val): StatementInterface => express('%s = %s', identify($key), param($val)),
            array_keys($map),
            $map
        ));

        return $this;
    }
}
