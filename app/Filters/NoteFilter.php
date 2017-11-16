<?php

namespace App\Filters;

class NoteFilter extends AbstractFilter
{
    protected $filters = [
        'title', 'published', 'q'
    ];

    protected function title($title)
    {
        return $this->builder->where('title', 'like', "%{$title}%");
    }

    protected function q($keyword)
    {
        return $this->builder->where('title', 'like', "%{$keyword}%")
            ->orWhere('body', 'like', "%{$keyword}%");
    }

    protected function published()
    {
        return $this->builder->where('published', 1);
    }
}
