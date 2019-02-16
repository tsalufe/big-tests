<?php
namespace BigTests;

use Illuminate\Database\Query\Builder;

abstract class AbstractMysqlBigTests extends AbstractBigTests
{
    /** @var Builder */
    protected $queryBuilder;

    public function __construct(Builder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function getBigDatas() : \Generator
    {
        return $this->queryBuilder->cursor();
    }
}
