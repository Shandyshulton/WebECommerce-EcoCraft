<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackController extends Controller
{
    public function show()
    {
        // Menampilkan view track.blade.php yang ada di folder track
        return view('track.track');
    }
}
