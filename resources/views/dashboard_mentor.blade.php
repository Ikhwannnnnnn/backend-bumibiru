@extends('layout.main')
@section('title', 'BumiBiru | Dashboard')
@section('keywords', 'Sistem Pengelolaan BUMIBIRU, BUMIBIRU, Sistem Pengelolaan, Website, BUMIBIRU, admin')
@section('description', 'Dashboard Admin BUMIBIRU')


@section('content')
<div class="row">
    <div class="col-lg-12 text-center">
        <h2 class="h3 mb-4">Selamat Datang di <br> Dashboard BUMIBIRU</h2>
    </div>
</div>
<style>
    #map { height: 500px; }
</style>
<div class="row">
    <div class="card col-lg-12" style="color: black">
        <div class="card-header">Informasi Kejadian</div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('saveMentor') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{$data->name}}" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{session('user.email')}}" required disabled>
                    </div>
                </div>
                <div class="form-row">
    <div class="form-group col-md-6">
        <label for="phone">No. Handphone</label>
        <input type="text" class="form-control" id="phone" name="phone" value="{{ $data->phone }}" required>
        <div id="phone-error" class="invalid-feedback" style="display: none;">Nomor handphone harus dimulai dengan +62</div>
    </div>
    <div class="form-group col-md-6">
        <label for="skills">Jenis Kebakaran:</label>
        <select class="form-control" id="skills" name="skills" required>
            <option value="" disabled {{ $data->skills == '' ? 'selected' : '' }}>-- Pilih Jenis Kebakaran --</option>
            <option value="Kebakaran Lahan" {{ $data->skills == 'Kebakaran Lahan' ? 'selected' : '' }}>Kebakaran Lahan</option>
            <option value="Kebakaran Hutan" {{ $data->skills == 'Kebakaran Hutan' ? 'selected' : '' }}>Kebakaran Hutan</option>
            <option value="Kebakaran Gambut" {{ $data->skills == 'Kebakaran Gambut' ? 'selected' : '' }}>Kebakaran Gambut</option>
            <option value="Kebakaran Campuran" {{ $data->skills == 'Kebakaran Campuran' ? 'selected' : '' }}>Kebakaran Campuran</option>
        </select>
    </div>
</div>

                <div class="form-row">
                    <div class="form-group col-md-6">
    <label for="tanggal_lapor">Tanggal Lapor</label>
    <input
        type="text"
        name="tanggal_lapor"
        id="tanggal_lapor"
        class="form-control @error('tanggal_lapor') is-invalid @enderror"
        placeholder="DD-MM-YYYY"
        value="{{ old('tanggal_lapor', isset($data) && $data->tanggal_lapor ? $data->tanggal_lapor->format('d-m-Y') : '') }}"
    >
    @error('tanggal_lapor')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required>{{$data->description}}</textarea>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="image">Foto</label>
                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*" onchange="previewImage()">
                        <input type="hidden" name="fotoLama" value="{{$data->image}}">
                        <div class="form-group mt-3">
                            @if ($data->image && $data->image != 'img_empty.gif')
                                <img src="{{ asset('/mentor/img/'.$data->image) }}" class="img-thumbnail img-preview" style="max-height: 250px; height: auto;">
                            @else
                                <img src="{{ asset('img/img_empty.gif') }}" class="img-thumbnail img-preview" style="max-height: 250px; height: auto;">
                            @endif
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="cv">Dokumen Tambahan (Opsional)</label>
                        <input type="file" class="form-control-file" id="cv" name="cv" accept=".pdf">
                        <input type="hidden" name="cvLama" value="{{$data->cv}}">
                        <small class="form-text text-muted">Please upload file.</small>
                        @if ($data->cv)
                            <div class="mt-3">
                                <a href="{{ asset('mentor/cv/'.$data->cv) }}" target="_blank">View</a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="alamat" name="alamat" value="{{$data->alamat}}" required onchange="updateMapPosition()">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="showMyLocation()">Show My Location</button>
                        </div>
                    </div>
                </div>
                <div id="map" class="form-group"></div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="latitude">Latitude</label>
                        <input type="text" class="form-control" id="latitude" name="latitude" value="{{$data->latitude}}" required readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="longitude">Longitude</label>
                        <input type="text" class="form-control" id="longitude" name="longitude" value="{{$data->longitude}}" required readonly>
                    </div>
                </div>
                <div class="form-group text-right">
                    <button type="submit" name="status" value="Not_Publish" class="btn btn-secondary">Not Publish</button>
                    <button type="submit" name="status" value="Publish" class="btn btn-primary">Publish</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    var map = L.map('map').setView([-7.797068, 110.370529], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    var marker;

    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        if (marker) {
            map.removeLayer(marker);
        }

        marker = L.marker([lat, lng]).addTo(map);

        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;

        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('alamat').value = data.display_name;
            });
    });

    function updateMapPosition() {
        var address = document.getElementById('alamat').value;

        if (address.trim() !== '') {
            fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(address)}&format=json`)
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    var lat = data[0].lat;
                    var lon = data[0].lon;

                    map.panTo(new L.LatLng(lat, lon));

                    if (marker) {
                        marker.setLatLng([lat, lon]);
                    } else {
                        marker = L.marker([lat, lon]).addTo(map);
                    }

                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lon;
                } else {
                    alert('Alamat tidak ditemukan!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }

    function showMyLocation() {
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lon = position.coords.longitude;

                map.panTo(new L.LatLng(lat, lon));

                if (marker) {
                    marker.setLatLng([lat, lon]);
                } else {
                    marker = L.marker([lat, lon]).addTo(map);
                }

                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('alamat').value = data.display_name;
                    });

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lon;
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
    window.onload = function() {
        updateMapPosition();
    };
    document.getElementById('phone').addEventListener('blur', function() {
        var phoneInput = this.value;
        if (!phoneInput.startsWith('+62')) {
            document.getElementById('phone').classList.add('is-invalid');
            document.getElementById('phone-error').style.display = 'block';
        } else {
            document.getElementById('phone').classList.remove('is-invalid');
            document.getElementById('phone-error').style.display = 'none';
        }
    });
</script>
@endsection
