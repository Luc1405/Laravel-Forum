<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index() {
        $title = "Over onze website";
        $paragraph = "Je stinkt naar saus";

        return view('about', compact('title', 'paragraph'));
    }
}
