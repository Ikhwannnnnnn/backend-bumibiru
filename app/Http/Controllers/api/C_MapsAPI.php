<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use Illuminate\Http\Request;

class C_MapsAPI extends Controller
{
    public function index()
    {
        $mentors = Mentor::where('status', 'publish')->get();
        return response()->json(['status' => 'success', 'data' => $mentors]);
    }

    public function search(Request $request)
    {
        $query = $request->input('search');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius');

        $queryBuilder = Mentor::where('status', 'publish')
            ->where('skills', 'like', '%' . $query . '%');

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

        return response()->json(['status' => 'success', 'data' => $mentors]);
    }
}
