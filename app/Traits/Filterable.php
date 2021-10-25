<?php

namespace App\Traits;

use stdClass;
use Illuminate\Support\Arr;

trait Filterable
{
    public function getFilterable () {
        return isset($this->filterable) ? $this->filterable : [];
    }

    public function scopeFilter($query, $params)
    {
        $results = [];

        $placeholder = new stdClass;

        foreach ($this->filterable as $filter) {
            $value = data_get($params, $filter, $placeholder);

            if ($value !== $placeholder) {
                Arr::set($results, $filter, $value);
            }
        }

        return $query->where($results);
    }
}