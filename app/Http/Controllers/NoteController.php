<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Http\Request;
use App\Http\Filters\NoteFilter;

class NoteController extends Controller
{
    //
    public function index(NoteFilter $filter)
    {
        return Note::latest()->filter($filter)->get();
    }

}
