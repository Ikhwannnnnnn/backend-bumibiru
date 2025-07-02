<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <a class="d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}" style="text-decoration: none">
        <div class="sidebar-brand-icon">
            <img style="width: 250px" src="{{ asset('img/gurucoding.png') }}" alt="">
        </div>
    </a>

    <ul class="navbar-nav ml-auto">
    <!-- Item menu kembali ke beranda -->
    <li class="nav-item">
        <a class="nav-link" href="http://localhost:5173/">
            <i class="fas fa-home fa-sm fa-fw mr-2 text-gray-400"></i>
            Kembali Ke Beranda
        </a>
    </li>

    <div class="topbar-divider d-none d-sm-block"></div>
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mr-2 d-none d-lg-inline text-dark">{{ session('user.username') }}</span>
            @if (session('user.image') && session('user.image') != 'img_empty.gif')
                <img class="img-profile rounded-circle" src="{{ asset('mentor/img/' . session('user.image')) }}">
            @else
                <img class="img-profile rounded-circle" src="{{ asset('img/undraw_profile.svg') }}">
            @endif
        </a>
        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
            aria-labelledby="userDropdown">
            @if (session('user.role') == 'mentor')
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#profileModal">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#resetPassModal">
                    <i class='bx bxs-key bx-flip-horizontal mr-2 text-gray-400'></i>
                    Reset Password
                </a>
                <div class="dropdown-divider"></div>
            @endif
            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Logout
            </a>
        </div>
    </li>
</ul>


    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="color: black">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yakin ingin keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Logout" di bawah ini jika Anda siap untuk mengakhiri sesi Anda.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    @if (session('user.role')=='mentor')
        {{-- Profile Modal --}}
        <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered modal-lg">
                <div class="modal-content"style="max-width: 90%; margin: 0 auto;">
                    <div class="modal-header bg-gradient-primary justify-content-center" style="background-color: ">
                        <h4 class="modal-title" style="color: white">Profile </h4>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('updateEmail', [encrypt(session('user.email'))]) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-5">
                                    <img src="{{session('foto')??asset('img/undraw_profile.svg')}}" alt="Foto Profil" class="img-fluid rounded-circle">
                                </div>
                                <div class="col-md-7" style="color: black">
                                    <div style="margin-bottom: 10px">
                                        <h5>Username</h5>
                                        <input type="text" class="form-control" value="<?= session('user.username') ?>" readonly>
                                    </div>
                                    <div style="margin-bottom: 10px">
                                        <h5>Email</h5>
                                        <input type="email" class="form-control" name="email" value="<?= session('user.email') ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reset Pass Modal --}}
        <div class="modal fade" id="resetPassModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
                <div class="modal-content" style="max-width: 90%; margin: 0 auto;">
                    <div class="modal-header bg-gradient-primary justify-content-center">
                        <h4 class="modal-title" style="color: white">Reset Password</h4>
                    </div>
                    <div class="modal-body text-dark">
                        <form action="{{ route('resetPass', [encrypt(session('user.email'))]) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="margin-bottom: 10px">
                                        <label class="form-label" for="password">Password Baru</label>
                                        <input type="password" class="form-control" name="password" placeholder="Masukkan password baru" required>
                                    </div>
                                    <div style="margin-bottom: 10px">
                                        <label class="form-label" for="password_confirmation">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" name="password_confirmation" placeholder="Masukkan ulang password baru" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

</nav>