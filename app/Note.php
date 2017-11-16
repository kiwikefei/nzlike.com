<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Filters\NoteFilter;
class Note extends Model
{
    /**
     * @param $query
     * @param $filter
     * @return mixed
     */
    public function scopeFilter($query, NoteFilter $filter)
    {
        return $filter->apply($query);
    }
}
