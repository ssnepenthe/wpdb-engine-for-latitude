<?php

declare(strict_types=1);

namespace WpdbEngineForLatitude;

use Latitude\QueryBuilder\Query\MySql\InsertQuery as LatitudeInsertQuery;

use function Latitude\QueryBuilder\express;
use function Latitude\QueryBuilder\listing;

class InsertQuery extends LatitudeInsertQuery
{
    public function values(...$params): self
    {
        $this->values[] = express('(%s)', listing(paramAll($params)));

        return $this;
    }
}
