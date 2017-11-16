<?php
namespace App\Filters;

abstract class AbstractFilter
{
    protected $builder;
    protected $filters = [];

    public function apply($builder)
    {
        $this->builder = $builder;
        foreach( $this->getRequestFilters() as $filter => $value) {
            if(method_exists($this, $filter)){
                $this->$filter($value);
            }
        }
    }

    private function getRequestFilters()
    {
        return request()->only($this->filters);
    }
}

