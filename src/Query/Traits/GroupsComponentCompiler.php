<?php

namespace Ywnsyage\ClickhouseBuilder\Query\Traits;

use Ywnsyage\ClickhouseBuilder\Query\BaseBuilder as Builder;
use Ywnsyage\ClickhouseBuilder\Query\Column;

trait GroupsComponentCompiler
{
    /**
     * Compiles groupings to string to pass this string in query.
     *
     * @param Builder  $builder
     * @param Column[] $columns
     *
     * @return string
     */
    private function compileGroupsComponent(Builder $builder, array $columns): string
    {
        $compiledColumns = [];

        foreach ($columns as $column) {
            $compiledColumns[] = $this->compileColumn($column);
        }

        if (!empty($compiledColumns) && !in_array('*', $compiledColumns, true)) {
            return 'GROUP BY '.implode(', ', $compiledColumns);
        } else {
            return '';
        }
    }
}
