<?php

declare(strict_types=1);

namespace WpLatitudeBridge;

use Latitude\QueryBuilder\Engine\MySqlEngine;
use Latitude\QueryBuilder\Query\InsertQuery as LatitudeInsertQuery;
use Latitude\QueryBuilder\Query\UpdateQuery as LatitudeUpdateQuery;

class WpdbEngine extends MySqlEngine
{
    public function makeUpdate(): LatitudeUpdateQuery
    {
        return new UpdateQuery($this);
    }

    public function makeInsert(): LatitudeInsertQuery
    {
        return new InsertQuery($this);
    }
}
