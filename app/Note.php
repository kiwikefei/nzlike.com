<?php

namespace App;
use App\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use Filterable;
    /**
     * @param $query
     * @param $filter
     * @return mixed
     */

}
