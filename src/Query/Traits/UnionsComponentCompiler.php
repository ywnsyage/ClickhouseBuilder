<?php

namespace Ywnsyage\ClickhouseBuilder\Query\Traits;

use Ywnsyage\ClickhouseBuilder\Query\BaseBuilder as Builder;

trait UnionsComponentCompiler
{
    /**
     * Compiles unions to string to pass this string in query.
     *
     * @param Builder $builder
     * @param array   $unions
     *
     * @return string
     */
    public function compileUnionsComponent(Builder $builder, array $unions): string
    {
        return 'UNION ALL '.
            implode(' UNION ALL ', array_map(function ($query) {
                return $query->toSql();
            }, $unions));
    }
}
