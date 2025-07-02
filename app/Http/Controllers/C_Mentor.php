<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class C_Mentor extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'description' => 'required',
            'phone' => 'required|max:15',
            'skills' => 'required|max:250',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:3072',
            'cv' => 'nullable|mimes:pdf|max:3072',
            'status' => 'required|max:15',
            'alamat' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'tanggal_lapor' => 'nullable|date_format:d-m-Y|before_or_equal:today',
        ]);

        if ($validator->fails()) {
            return back()->with(['message' => $validator->errors()->first(), 'alert-type' => 'error']);
        }

        $data = Mentor::where('id_user', session('user.id'))->first();
        if (!$data) {
            return back()->with(['message' => 'Akun Tidak Terdaftar', 'alert-type' => 'error']);
        }

        $namaFileLama = $request->input('fotoLama');
        $image = $request->file('image');
        if ($image && $image->isValid()) {
            $namaFile = $image->hashName();
            $image->move('mentor/img/', $namaFile);
            if ($namaFileLama != 'img_empty.gif' && $namaFileLama != "") {
                File::delete('mentor/img/' . $namaFileLama);
            }
        } else {
            $namaFile = $namaFileLama;
        }

        $cvLama = $request->input('cvLama');
        $cv = $request->file('cv');
        if ($cv && $cv->isValid()) {
            $namaCV = $cv->hashName();
            $cv->move('mentor/cv/', $namaCV);
            File::delete('mentor/cv/' . $cvLama);
        } else {
            $namaCV = $cvLama;
        }

        // Konversi tanggal
        $tanggalLapor = $request->filled('tanggal_lapor')
            ? Carbon::createFromFormat('d-m-Y', $request->tanggal_lapor)->format('Y-m-d')
            : null;

        $data->update([
            'name' => $request->name,
            'description' => $request->description,
            'phone' => $request->phone,
            'image' => $namaFile,
            'cv' => $namaCV,
            'skills' => $request->skills,
            'status' => $request->status,
            'alamat' => $request->alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'tanggal_lapor' => $tanggalLapor,
        ]);

        return redirect()->back()->with(['message' => 'Successfully saved data', 'alert-type' => 'success']);
    }

    public function search(Request $req)
{
    $q    = $req->query('search');     // Jenis kebakaran
    $lat  = $req->query('latitude');
    $lng  = $req->query('longitude');
    $rad  = $req->query('radius', 0);  // radius dalam kilometer

    $query = Mentor::query(); // Ganti dengan model sesuai tabel kamu

    // Filter berdasarkan jenis kebakaran
    if ($q) {
        $query->where('skills', 'like', '%' . $q . '%');
    }

    // Filter berdasarkan jarak radius jika koordinat lengkap dan radius diberikan
    if ($lat && $lng && $rad > 0) {
        $query->whereRaw(
            "(6371 * acos(
                cos(radians(?)) *
                cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) +
                sin(radians(?)) *
                sin(radians(latitude))
            )) <= ?",
            [$lat, $lng, $lat, $rad]
        );
    }

    return response()->json([
        'status' => 'success',
        'data' => $query->get(),
    ]);
}
}
