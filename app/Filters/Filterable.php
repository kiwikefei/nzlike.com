<?php
namespace App\Filters;

trait Filterable
{
    /**
     * @param $query
     * @param AbstractFilter $filter
     */
    public function scopeFilter($query, AbstractFilter $filter)
    {
        $filter->apply($query);
    }
}
