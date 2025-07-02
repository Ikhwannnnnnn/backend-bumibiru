<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="cari mentor, BumiBiru, Bumi, Biru">
    <meta name="description" content="Lihat lokasi kebakaran disekitar anda">
    <meta name="author" content="BumiBiru">
    <link rel="icon" href="{{ asset('img/logo-iwan.png') }}">
    <title>BUMIBIRU</title>
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous"/>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
    <link rel="stylesheet" href="{{ asset('leaflet/leaflet.css') }}">
    <style>
        #map-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        #map {
            height: 100%;
        }

        #search-box {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
    </style>
</head>

<body>
    <div id="search-box">
        <form id="search-form" action="javascript:void(0);">
            <div class="input-group">
                <input type="text" class="form-control" name="search" id="search-input" placeholder="Cari berdasarkan Jenis Kebakaran">
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">
                <input type="number" class="form-control" name="radius" id="radius" placeholder="Radius (km)">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
    </div>

    <div id="map-container">
        <div id="map"></div>
    </div>

    <!-- Mentor Detail Modal -->
    <div class="modal fade" id="mentorModal" tabindex="-1" aria-labelledby="mentorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mentorModalLabel">Kejadian Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="mentorDetails"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.js') }}"></script>
    <script src="{{ asset('leaflet/leaflet.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        @if(Session::has('message'))
        var type = "{{ Session::get('alert-type', 'info') }}";
        switch (type) {
            case 'info':
                toastr.info("{{ Session::get('message') }}");
                break;
            case 'success':
                toastr.success("{{ Session::get('message') }}");
                break;
            case 'warning':
                toastr.warning("{{ Session::get('message') }}");
                break;
            case 'error':
                toastr.error("{{ Session::get('message') }}");
                break;
        }
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            var map = L.map('map').locate({setView: true, maxZoom: 16});
            var markers = [];

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            function onLocationFound(e) {
                var radius = e.accuracy / 2;
                L.marker(e.latlng).addTo(map)
                    .bindPopup("You are within " + radius + " meters from this point").openPopup();
                L.circle(e.latlng, radius).addTo(map);
            }

            function onLocationError(e) {
                alert(e.message);
            }

            map.on('locationfound', onLocationFound);
            map.on('locationerror', onLocationError);

            function addMentorsToMap(mentors) {
                markers.forEach(marker => map.removeLayer(marker));
                markers = [];
                mentors.forEach(function(mentor) {
                    var marker = L.marker([mentor.latitude, mentor.longitude]).addTo(map);
                    marker.on('click', function() {
                        var modalContent = `
                            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
                                <div class="modal-content" style="max-width: 90%; margin: 0 auto;">
                                    <div class="modal-header bg-gradient-primary justify-content-center" style="background-color: ">
                                        <h4 class="modal-title" style="color: white">Mentor Details</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <img src="${mentor.image === 'img_empty.gif' ? '{{ asset('img/img_empty.gif') }}' : `{{ asset('mentor/img/') }}/${mentor.image}` }" alt="${mentor.name}" class="img-fluid rounded-circle">
                                                <h5 class="mt-3 text-center text-gray-900">${mentor.name}</h5>
                                            </div>
                                            <div class="col-md-7" style="color: black">
                                                <div style="margin-bottom: 10px">
                                                    <p>${mentor.description}</p>
                                                </div>
                                                <div style="margin-bottom: 10px">
                                                    <strong>Keterampilan</strong>
                                                    <p>${mentor.skills}</p>
                                                </div>
                                                <div style="margin-bottom: 10px">
                                                    <strong>Informasi Kontak</strong>
                                                    <p><a href="https://wa.me/${mentor.phone}" target="_blank">${mentor.phone}</a></p>
                                                </div>
                                                <div style="margin-bottom: 10px">
                                                    <strong>CV</strong>
                                                    ${mentor.cv ? `<p><a href="{{ asset('mentor/cv/') }}/${mentor.cv}" target="_blank">Preview CV</a></p>` : '<p>Tidak ada CV</p>'}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        `;
                        $('#mentorModal .modal-content').html(modalContent);
                        var mentorModal = new bootstrap.Modal(document.getElementById('mentorModal'));
                        mentorModal.show();
                    });
                    markers.push(marker);
                });
            }

            addMentorsToMap(@json($data));

            navigator.geolocation.getCurrentPosition(function(position) {
                var latitude = position.coords.latitude;
                var longitude = position.coords.longitude;
                document.getElementById('latitude').value = latitude;
                document.getElementById('longitude').value = longitude;
            });

            document.getElementById('search-form').addEventListener('submit', function() {
                var searchInput = document.getElementById('search-input').value;
                var latitude = document.getElementById('latitude').value;
                var longitude = document.getElementById('longitude').value;
                var radius = document.getElementById('radius').value;
                if (latitude && longitude && radius) {
                    $.ajax({
                        url: "{{ route('search') }}",
                        method: "GET",
                        data: { 
                            search: searchInput,
                            latitude: latitude,
                            longitude: longitude,
                            radius: radius
                        },
                        success: function(data) {
                            addMentorsToMap(data);
                        }
                    });
                } else {
                    $.ajax({
                        url: "{{ route('search') }}",
                        method: "GET",
                        data: { 
                            search: searchInput
                        },
                        success: function(data) {
                            addMentorsToMap(data);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
