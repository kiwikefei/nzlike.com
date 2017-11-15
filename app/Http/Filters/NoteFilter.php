<?php

namespace App\Http\Filters;

use App\Note;

class NoteFilter extends AbstractFilter
{
    protected $filters = [
        'title', 'published'
    ];

    public function title()
    {
        
    }
}
