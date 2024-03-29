<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function homepage()
    {
        $ourName = 'Robert';
        $animals = ['Dog', 'Cat', 'Horse', 'Rabbit'];
        return view('homepage', ['name' => $ourName, 'animals' => $animals]);
    }

    public function about()
    {
        return '<h1>About</h1>';
    }
}
