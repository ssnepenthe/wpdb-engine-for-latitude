<?php

declare(strict_types=1);

namespace WpdbEngineForLatitude;

use InvalidArgumentException;
use Latitude\QueryBuilder\EngineInterface;
use Latitude\QueryBuilder\StatementInterface;

final class Parameter implements StatementInterface
{
    /** @var string */
    private $sql = '';

    /** @var array */
    private $params = [];

    public function __construct($value)
    {
        if (is_bool($value) || is_null($value)) {
            $this->sql = $value;
        } else {
            if (is_string($value)) {
                $this->sql = '%s';
            } elseif (is_int($value)) {
                $this->sql = '%d';
            } elseif (is_float($value)) {
                $this->sql = '%f';
            } else {
                $supportedTypes = ['bool', 'float', 'int', 'string', 'null'];

                throw new InvalidArgumentException(sprintf(
                    'Parameter of type %s not supported - must be one of %s',
                    gettype($value),
                    implode('|', $supportedTypes)
                ));
            }

            $this->params[] = $value;
        }
    }

    public function sql(EngineInterface $engine): string
    {
        return $engine->exportParameter($this->sql);
    }

    public function params(EngineInterface $engine): array
    {
        return $this->params;
    }
}
