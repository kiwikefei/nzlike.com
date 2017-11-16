<?php
namespace App\Http\Filters;

use Illuminate\Http\Request;

abstract class AbstractFilter
{
    protected $request;
    protected $builder;
    protected $filters = [];
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;
        foreach( $this->getFilters() as $filter => $value) {
            if(method_exists($this, $filter)){
                $this->$filter($value);
            }
        }
        return $this->builder;
    }

    private function getFilters()
    {
        return $this->request->only($this->filters);
    }
}
