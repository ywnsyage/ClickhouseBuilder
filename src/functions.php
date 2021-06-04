<?php

// @codeCoverageIgnoreStart
if (!function_exists('tap')) {
    /**
     * Call the given Closure with the given value then return the value.
     *
     * @param mixed    $value
     * @param callable $callback
     *
     * @return mixed
     */
    function tap($value, $callback)
    {
        $callback($value);

        return $value;
    }
}
// @codeCoverageIgnoreEnd

// @codeCoverageIgnoreStart
if (!function_exists('array_flatten')) {
    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param array $array
     * @param int   $depth
     *
     * @return array
     */
    function array_flatten($array, $depth = INF): array
    {
        return array_reduce($array, function ($result, $item) use ($depth) {
            if (!is_array($item)) {
                return array_merge($result, [$item]);
            } elseif ($depth === 1) {
                return array_merge($result, array_values($item));
            } else {
                return array_merge($result, array_flatten($item, $depth - 1));
            }
        }, []);
    }
}
// @codeCoverageIgnoreEnd

if (!function_exists('raw')) {
    /**
     * Wrap string into Expression object for inserting in sql query as is.
     *
     * @param string $expr
     *
     * @return \Ywnsyage\ClickhouseBuilder\Query\Expression
     */
    function raw(string $expr): \Ywnsyage\ClickhouseBuilder\Query\Expression
    {
        return new \Ywnsyage\ClickhouseBuilder\Query\Expression($expr);
    }
}

if (!function_exists('into_memory_table')) {
    /**
     * Creates temporary table if table does not exists and inserts provided data into query.
     *
     * @param \Ywnsyage\ClickhouseBuilder\Query\Builder|\Ywnsyage\ClickhouseBuilder\Integrations\Laravel\Builder $builder
     * @param array|null                                                                                           $structure
     *
     * @throws \Ywnsyage\ClickhouseBuilder\Exceptions\BuilderException
     *
     * @return bool
     */
    function into_memory_table($builder, $structure = null): bool
    {
        $tableName = null;
        $from = $builder->getFrom();

        if (!is_null($from)) {
            $tableName = $from->getTable();
        }

        $file = $builder->getValues();
        $format = $builder->getFormat();

        if (is_null($tableName) && $file instanceof \Ywnsyage\Clickhouse\Common\TempTable) {
            $tableName = $file->getName();
        }

        if (is_null($structure) && $file instanceof \Ywnsyage\Clickhouse\Common\TempTable) {
            $structure = $file->getStructure();
        }

        if (is_null($format) && $file instanceof \Ywnsyage\Clickhouse\Common\TempTable) {
            $format = $file->getFormat();
        }

        if (is_null($structure)) {
            throw \Ywnsyage\ClickhouseBuilder\Exceptions\BuilderException::noTableStructureProvided();
        }

        $builder->newQuery()->dropTableIfExists($tableName);
        $builder->newQuery()->createTableIfNotExists($tableName, 'Memory', $structure);

        $result = $builder->newQuery()->table($tableName)->insertFile(array_keys($structure), $file, $format);

        return $result;
    }
}

if (!function_exists('file_from')) {
    function file_from($file): \Ywnsyage\Clickhouse\Interfaces\FileInterface
    {
        if (is_string($file) && is_file($file)) {
            $file = new \Ywnsyage\Clickhouse\Common\File($file);
        } elseif (is_scalar($file)) {
            $file = new \Ywnsyage\Clickhouse\Common\FileFromString($file);
        }

        return $file;
    }
}
