<?php
namespace App\Filters;
Trait Filterable
{
    public function scopeFilter($query, AbstractFilter $filter)
    {
        return $filter->apply($query);
    }
}
