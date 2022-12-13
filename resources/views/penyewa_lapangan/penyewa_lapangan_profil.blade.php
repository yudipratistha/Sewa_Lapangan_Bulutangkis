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
                            <h5 class="pull-left">Edit Profil Penyewa Lapangan</h5>
                        </div>
                        <div class="card-body">
                            <form id="update-profile">
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
                                    <button onClick="profileUpdate()" class="btn btn-success m-r-15" type="button" style="display: inline;">Simpan</button>
                                    <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modal-ganti-password" data-bs-original-title="" title="">Ubah Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Change Password-->
        <div class="modal fade" id="modal-ganti-password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center d-block">
                        <h4 class="modal-title ">Ganti Password</h3>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="card-body">
                        <form id="change-password">
                            @csrf
                            <div class="mb-3">
                                <label for="password-baru" class="form-label">Password Baru</label>
                                <input name="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" id="password-baru"
                                    placeholder="Password Baru" required="">
                                @error('new_password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="ulangi-password-baru" class="form-label">Ulangi Password Baru</label>
                                <input name="new_password_confirmation" type="password" class="form-control" id="ulangi-password-baru"
                                    placeholder="Ulangi Password Baru" required="">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onClick="changePassword()" class="btn btn-success">Konfirmasi Ubah Password</button>
                        <button type="button" class="btn btn-square btn-outline-light txt-dark" data-bs-dismiss="modal">Tutup</button>
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
<script src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>

<script>
    $(document).ready(function(){
        $('#password-baru').on('input', function(){
            $('#password-baru').parent().children('.invalid-feedback').remove();
            $('#password-baru').removeClass('is-invalid');
        });

        $('#ulangi-password-baru').on('input', function(){
            $('#ulangi-password-baru').parent().children('.invalid-feedback').remove();
            $('#ulangi-password-baru').removeClass('is-invalid');
        });
    });

    function profileUpdate(){
        swal.fire({
            title: "Konfirmasi Ubah Profil?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Save",
            closeOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('penyewaLapangan.updateProfil')}}",
                    datatype : "json",
                    data: $('#update-profile').serialize(),
                    success: function(data){

                    },
                    error: function(data){
                        var responseErrTxt = '';

                        if(data.responseJSON.errorTextJamBooking.trim()){
                            responseErrTxt = data.responseJSON.errorTextJamBooking+'<br>';
                        }

                        swal.fire({title:"Data Profil Baru Gagal Tersimpan!", icon:"error", html: responseErrTxt});
                    }
                });
            }
        }).then((result) => {
            if(result.value){
                swal.fire({title:"Data Profil Baru Berhasil Tersimpan!", icon:"success"})
                .then(function(){
                    window.location.href = "{{route('penyewaLapangan.editProfil')}}";
                });
            }
        });
    }

    function changePassword(){
        swal.fire({
            title: "Konfirmasi Ubah Password?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Save",
            closeOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('updatePassword')}}",
                    datatype : "json",
                    data: $('#change-password').serialize(),
                    success: function(data){

                    },
                    error: function(data){
                        if(data.responseJSON.errors.new_password[0] === 'Inputan tidak boleh kosong!'){
                            $('#password-baru').parent().children('.invalid-feedback').remove();
                            $('#password-baru').addClass('is-invalid');
                            $('#password-baru').after('<div class="invalid-feedback">'+data.responseJSON.errors.new_password[0]+'</div>');
                        }

                        $('#ulangi-password-baru').parent().children('.invalid-feedback').remove();
                        $('#ulangi-password-baru').addClass('is-invalid');
                        $('#ulangi-password-baru').after('<div class="invalid-feedback">'+data.responseJSON.errors.new_password[0]+'</div>');

                        swal.fire({title:"Password Baru Gagal Tersimpan!", icon:"error", html: data.responseJSON.errors.new_password[0]});
                    }
                });
            }
        }).then((result) => {
            if(result.value){
                swal.fire({title:"Password Baru Berhasil Tersimpan!", icon:"success"})
                .then(function(){
                    window.location.href = "{{route('penyewaLapangan.editProfil')}}";
                });
            }
        });
    }
</script>
@endsection

