@extends('layout.main')
@section('title', 'BumiBiru | Dashboard')
@section('keywords', 'Sistem Pengelolaan BUMIBIRU, BUMIBIRU, Sistem Pengelolaan, Website, BUMIBIRU, admin')
@section('description', 'Dashboard Admin BUMIBIRU')


@section('content')
<div class="row">
    <div class="col-lg-12 text-center">
        <h2 class="h3 mb-4">Detail Kejadian Kebakaran</h2>
    </div>
</div>
<style>
    #map { height: 500px; }
</style>
<div class="row">
    <div class="card col-lg-12" style="color: black">
        <div class="card-header">Informasi Kebakaran</div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name">Nama Lengkap</label>
                    <p id="name" class="form-control-plaintext">{{ $data->name }}</p>
                </div>
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <p id="email" class="form-control-plaintext">{{ session('user.email') }}</p>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="phone">No. Handphone</label>
                    <p id="phone" class="form-control-plaintext">{{ $data->phone }}</p>
                </div>
                <div class="form-group col-md-6">
                    <label for="skills">Jenis Kebakaran:</label>
                    <p id="skills" class="form-control-plaintext">{{ $data->skills }}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <p id="description" class="form-control-plaintext">{{ $data->description }}</p>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="image">Foto</label>
                    <div>
                        @if ($data->image && $data->image != 'img_empty.gif')
                            <img src="{{ asset('mentor/img/'.$data->image) }}" class="img-thumbnail" style="max-height: 250px; height: auto;">
                        @else
                            <img src="{{ asset('img/img_empty.gif') }}" class="img-thumbnail" style="max-height: 250px; height: auto;">
                        @endif
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label for="cv">Dokumen Tambahan</label>
                    <div>
                        @if ($data->cv)
                            <a href="{{ asset('mentor/cv/'.$data->cv) }}" target="_blank">Dokumen Tambahan</a>
                        @else
                            <p class="form-control-plaintext">Dokumen Tambahan tidak diunggah</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <p id="alamat" class="form-control-plaintext">{{ $data->alamat }}</p>
            </div>
            <div id="map" class="form-group"></div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="latitude">Latitude</label>
                    <p id="latitude" class="form-control-plaintext">{{ $data->latitude }}</p>
                </div>
                <div class="form-group col-md-6">
                    <label for="longitude">Longitude</label>
                    <p id="longitude" class="form-control-plaintext">{{ $data->longitude }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var map = L.map('map').setView([{{ $data->latitude }}, {{ $data->longitude }}], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker = L.marker([{{ $data->latitude }}, {{ $data->longitude }}]).addTo(map);
</script>
@endsection
