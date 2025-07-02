<?php

namespace App\Http\Controllers;

use App\Models\Titik;
use Illuminate\Http\Request;

class TitikController extends Controller
{
    public function index()
    {
        $data = Titik::select('latitude', 'longitude')->get();
        return response()->json($data);
    }

    


}
