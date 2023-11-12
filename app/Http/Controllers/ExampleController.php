<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function homepage() {
        // imagine we loaded data from the database
        $ourName = 'Brad';
        $animals = ['Mewsalot', 'Barksalot', 'Purrsloud'];

        return view('homepage', ['allAnimals' => $animals, 'name'=> $ourName]);
    }

    public function aboutPage() {
        return view('single-post');
    }
}