<?php

namespace App\Http\Filters;

use App\Note;

class NoteFilter extends AbstractFilter
{
    protected $filters = [
        'title', 'published'
    ];

    protected function title($title)
    {
        return $this->builder->where('title', 'like', "%{$title}%");
    }
}
