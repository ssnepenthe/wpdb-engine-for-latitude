<?php

declare(strict_types=1);

namespace WpdbEngineForLatitude;

use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\StatementInterface;

final class LikeBegins implements StatementInterface
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function sql(EngineInterface $engine): string
    {
        return '%s';
    }

    public function params(EngineInterface $engine): array
    {
        $value = $engine->escapeLike($this->value);

        return ["$value%"];
    }
}
