<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use Illuminate\Http\Request;

class C_Maps extends Controller
{
    public function index()
    {
        $data = Mentor::where('status', 'publish')->get();
        return view('maps', compact('data'));
    }

    public function search(Request $request)
    {
        $query = $request->input('search');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius');

        $queryBuilder = Mentor::where('status', 'publish')
            ->where('description', 'like', '%' . $query . '%');

        if ($latitude && $longitude && $radius) {
            $mentors = $queryBuilder->selectRaw("
                *, 
                (
                    6371 * acos(
                        cos(radians($latitude)) 
                        * cos(radians(latitude)) 
                        * cos(radians(longitude) - radians($longitude)) 
                        + sin(radians($latitude)) 
                        * sin(radians(latitude))
                    )
                ) AS distance")
                ->having('distance', '<=', $radius)
                ->orderBy('distance')
                ->get();
        } else {
            $mentors = $queryBuilder->get();
        }

        return response()->json($mentors);
    }
}
