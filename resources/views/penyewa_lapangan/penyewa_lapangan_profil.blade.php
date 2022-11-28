@extends('layouts.app')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/dropzone.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/sweetalert2.css')}}">

@endsection

@section('content')
<!-- page-wrapper Start       -->
<div class="page-wrapper compact-wrapper" id="pageWrapper">
    <!-- Page Header Start-->
    @include('layouts.header')
    <!-- Page Header End-->
    <!-- Page Body Start-->
    <div class="page-body-wrapper sidebar-icon">
        <!-- Page Sidebar Start-->
        @include('layouts.sidebar')
        <!-- Page Sidebar End-->
        <div class="page-body">
            <!-- Container-fluid starts-->
            <div class="container-fluid">
                <div class="row">
                    <div class="card pt-2">
                        <div class="card-header">
                            <!-- <h5 class="pull-left">Material tab with color</h5> -->
                            <h5 class="pull-left">Edit Profil Pemilik Lapangan</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                @csrf
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <div class="input-group"><span class="input-group-text"><i class="icon-user"></i></span>
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="nama_penyewa_lapangan" placeholder="Name" value="{{$dataUser->name}}" required autocomplete="name" autofocus>
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
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email_penyewa_lapangan" placeholder="Test@gmail.com" value="{{$dataUser->email}}" disabled>
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Nomor Telepon</label>
                                    <div class="input-group"><span class="input-group-text"><i class="icofont icofont-telephone"></i></span>
                                        <input id="nomor-telepon" type="number" class="form-control" name="nomor_telepon_penyewa_lapangan" placeholder="08xxxx" value="{{$dataUser->nomor_telepon}}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Chat Id</label>
                                    <div class="input-group"><span class="input-group-text"><i class="icofont icofont-robot"></i></span>
                                        <input id="chat-id" type="number" class="form-control" name="chat_id_penyewa_lapangan" placeholder="Didapatkan melalui bot Telegram" value="{{$dataUser->chat_id}}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary btn-block" type="submit">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal tutorial bot Telegram --}}
        
        <!-- footer start-->
        @include('layouts.footer')
        <div class="modal fade" id="modalTutorial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center d-block">
                        <h4 class="modal-title ">Tutorial Registrasi Bot Telegram</h3>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-square btn-outline-light txt-dark" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('plugin_js')
@endsection

