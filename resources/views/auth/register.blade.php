@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-xl-12 xl-100 col-lg-12 box-col-12">
            <div class="register-card">
                <div class="card register-form pt-2">
                    <div class="card-header">
                        <!-- <h5 class="pull-left">Material tab with color</h5> -->
                        <h5 class="pull-left">Registrasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="tabbed-card">
                            <ul class="pull-right nav nav-tabs border-tab nav-secondary" id="top-tabsecondary" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="top-home-secondary" data-bs-toggle="tab" href="#top-registrasi-penyewa-lapangan" role="tab" aria-controls="top-home" aria-selected="true"><i class="icofont icofont-man-in-glasses"></i>Penyewa Lapangan</a>
                                    <div class="material-border"></div>
                                </li>
                                <li class="nav-item"><a class="nav-link" id="profile-top-secondary" data-bs-toggle="tab" href="#top-profilesecondary" role="tab" aria-controls="top-profilesecondary" aria-selected="false"><i class="icofont icofont-badminton-birdie"></i>Pemilik Lapangan</a>
                                    <div class="material-border"></div>
                                </li>
                            </ul>
                            <div class="tab-content" id="top-tabContentsecondary">
                                <div class="tab-pane fade active show" id="top-registrasi-penyewa-lapangan" role="tabpanel" aria-labelledby="top-home-tab">
                                    <form method="POST" action="{{ route('penyewaLapangan.register') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label>Your Name</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icon-user"></i></span>
                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                            </div>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icon-email"></i></span>
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Test@gmail.com" value="{{ old('email') }}" required autocomplete="email">
                                            </div>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="*********" required autocomplete="new-password">
                                                <div class="show-hide"><span class="show"></span></div>
                                            </div>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <!-- <div class="form-group">
                                            <label>Confirm Password</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                                <div class="show-hide"><span class="show"></span></div>
                                            </div>
                                        </div> -->
                                        <div class="form-group">
                                            <label>Nomor Telepon</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icofont icofont-telephone"></i></span>
                                                <input id="nomor-telepon" type="number" class="form-control" name="nomor_telepon" placeholder="08xxxx" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary btn-block" type="submit">Create Account</button>
                                        </div>
                                        <p>Already have an account?<a class="ms-2" href="log-in.html">Sign in</a></p>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="top-profilesecondary" role="tabpanel" aria-labelledby="profile-top-tab">
                                    
                                    <form method="POST" action="{{ route('pemilikLapangan.register') }}">
                                        @csrf
                                        <div class="form-group">
                                            <label>Nama Lapangan</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icofont icofont-badminton-birdie"></i></span>
                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nama Lapangan" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                            </div>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Nama Lengkap</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icon-user"></i></span>
                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                            </div>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Alamat Email</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icon-email"></i></span>
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Test@gmail.com" value="{{ old('email') }}" required autocomplete="email">
                                            </div>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="*********" required autocomplete="new-password">
                                                <div class="show-hide"><span class="show"></span></div>
                                            </div>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <!-- <div class="form-group">
                                            <label>Confirm Password</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                                <div class="show-hide"><span class="show"></span></div>
                                            </div>
                                        </div> -->
                                        <div class="form-group">
                                            <label>Nomor Telepon</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icofont icofont-telephone"></i></span>
                                                <input id="nomor-telepon" type="number" class="form-control" name="nomor_telepon" placeholder="08xxxx" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary btn-block" type="submit">Create Account</button>
                                        </div>
                                        <p>Already have an account?<a class="ms-2" href="log-in.html">Sign in</a></p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-8">
            <div class="card">
                
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    
                </div>
            </div>
        </div> -->
    </div>
</div>
@endsection
