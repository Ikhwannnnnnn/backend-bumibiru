<?php

namespace App\Exports;

use App\Models\Mentor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MentorExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Mentor::join('users', 'mentors.id_user', '=', 'users.id')
            ->select(
                'mentors.name',
                'users.email',
                'mentors.phone',
                'mentors.skills',
                'mentors.tanggal_lapor',
                'mentors.alamat',
                'mentors.longitude',
                'mentors.latitude'
            )
            ->whereNull('mentors.deleted_at') // pastikan hanya yang tidak dihapus yang diambil
            ->get();
    }

    public function headings(): array
    {
        return ["Nama", "Email", "No. Handphone", "Jenis Kebakaran", "Tanggal Lapor", "Alamat", "Longitude", "Latitude"];
    }
}

