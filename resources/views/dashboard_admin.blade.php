@extends('layout.main')
@section('title', 'BumiBiru | Dashboard')
@section('keywords', 'Sistem Pengelolaan BUMIBIRU, BUMIBIRU, Sistem Pengelolaan, Website, BUMIBIRU, admin')
@section('description', 'Dashboard Admin BUMIBIRU')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h2>List Kejadian</h2>
        <div class="card">
            <div class="card-body">
                <button class="btn btn-success mb-3" onclick="window.location.href='{{ route('exportMentors') }}'">
                    Export ke Excel
                </button>
                <div class="table-responsive">
                    <table id="dataTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. Handphone</th>
                                <th>Jenis Kebakaran</th>
                                <th>Tanggal Lapor</th>
                                <th>Alamat</th>
                                <th>Longitude</th>
                                <th>Latitude</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mentors as $mentor)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $mentor->name }}</td>
                                    <td>{{ $mentor->user->email }}</td>
                                    <td>{{ $mentor->phone }}</td>
                                    <td>{{ $mentor->skills }}</td>
                                    <td>{{ $mentor->tanggal_lapor ? \Carbon\Carbon::parse($mentor->tanggal_lapor)->format('d-m-Y') : '-' }}</td>
                                    <td>{{ substr($mentor->alamat, 0, 50) }}...</td>
                                    <td>{{ $mentor->longitude }}</td>
                                    <td>{{ $mentor->latitude }}</td>
                                    <td>
                                        <a href="{{ route('detailMentor', encrypt($mentor->id)) }}" class="btn btn-info btn-sm">Detail</a>
                                        <form action="{{ route('destroyMentor', encrypt($mentor->id)) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                
                const mentorId = this.getAttribute('data-id');
                
                swal({
                    title: "Apakah Anda yakin?",
                    text: "Setelah dihapus, Anda tidak akan dapat memulihkan data Kebakaran ini!",
                    icon: "warning",
                    buttons: ["Batal", "Ya, hapus"],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        document.getElementById('deleteForm' + mentorId).submit();
                    } else {
                        swal("tidak dihapus.", {
                            icon: "info",
                        });
                    }
                });
            });
        });
    });
</script>
@endsection
