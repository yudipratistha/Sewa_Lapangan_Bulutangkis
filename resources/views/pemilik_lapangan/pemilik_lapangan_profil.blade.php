@extends('layouts.app')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/dropzone.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/jquery.timepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/jquery.datetimepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/leaflet.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/leaflet-gesture-handling.min.css')}}">

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
                <div class="container-fluid">
                    <div class="edit-profile">
                        <div class="row">
                            <div class="col-xl-12 xl-100 col-lg-12 box-col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <!-- <h5 class="pull-left">Material tab with color</h5> -->
                                        <h5 class="pull-left">Edit Profil Pemilik Lapangan</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="update-lapangan-profile" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                <label class="form-label">Nama Lapangan</label>
                                                <div class="input-group"><span class="input-group-text"><i class="icofont icofont-badminton-birdie"></i></span>
                                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="nama_lapangan_pemilik_lapangan" placeholder="Nama Lapangan" value="{{$dataProfilPemilikLapangan->nama_lapangan}}" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Nama Lengkap</label>
                                                <div class="input-group"><span class="input-group-text"><i class="icon-user"></i></span>
                                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="nama_pemilik_lapangan" placeholder="Name" value="{{$dataProfilPemilikLapangan->user->name}}" required autocomplete="name" autofocus>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Alamat Email</label>
                                                <div class="input-group"><span class="input-group-text"><i class="icon-email"></i></span>
                                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email_pemilik_lapangan" placeholder="Test@gmail.com" value="{{$dataProfilPemilikLapangan->user->email}}" disabled>
                                                </div>
                                            </div>
                                            <!-- <div class="form-group">
                                                <label>Password</label>
                                                <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password_pemilik_lapangan" placeholder="*********" required autocomplete="new-password">
                                                    <div class="show-hide"><span class="show"></span></div>
                                                </div>
                                                @error('password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div> -->
                                            <!-- <div class="form-group">
                                                <label>Confirm Password</label>
                                                <div class="input-group"><span class="input-group-text"><i class="icon-lock"></i></span>
                                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                                    <div class="show-hide"><span class="show"></span></div>
                                                </div>
                                            </div> -->
                                            <div class="form-group">
                                                <label>Chat Id</label>
                                                <div class="input-group"><span class="input-group-text"><i class="icofont icofont-robot"></i></span>
                                                    <input id="chat-id" type="number" class="form-control" name="chat_id_pemilik_lapangan" placeholder="Didapatkan melalui bot Telegram" value="{{$dataProfilPemilikLapangan->user->chat_id}}" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Nomor Telepon</label>
                                                <div class="input-group"><span class="input-group-text"><i class="icofont icofont-telephone"></i></span>
                                                    <input id="nomor-telepon" type="number" class="form-control" name="nomor_telepon_pemilik_lapangan" placeholder="08xxxx" value="{{$dataProfilPemilikLapangan->user->nomor_telepon}}" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Upload Foto Lapangan</label>
                                                <div class="form-space theme-form row mt-2">
                                                    <div class="col-md-4 img-up">
                                                        <div class="image-preview" id="image-preview-foto-lapangan-1"></div>
                                                        <label class="btn btn-primary">Upload
                                                            <input type="file" class="upload-file img" name="foto_lapangan_1" value="foto_lapangan_1" style="width: 0px;height: 0px;overflow: hidden;">
                                                        </label>
                                                    </div>
                                                    <div class="col-md-4 img-up">
                                                        <div class="image-preview" id="image-preview-foto-lapangan-2"></div>
                                                        <label class="btn btn-primary">Upload
                                                            <input type="file" class="upload-file img" name="foto_lapangan_2" value="foto_lapangan_2" style="width: 0px;height: 0px;overflow: hidden;">
                                                        </label>
                                                    </div>
                                                    <div class="col-md-4 img-up">
                                                        <div class="image-preview" id="image-preview-foto-lapangan-3"></div>
                                                        <label class="btn btn-primary">Upload
                                                            <input type="file" class="upload-file img" name="foto_lapangan_3" value="foto_lapangan_3" style="width: 0px;height: 0px;overflow: hidden;">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="form-group">
                                                <label>Harga Lapangan Per Jam</label>
                                                <div class="input-group"><span class="input-group-text"><i class="icofont icofont-money"></i></span>
                                                    <input id="harga-lapangan-per-jam" type="number" class="form-control" name="harga_lapangan_per_jam" placeholder="Harga Lapangan Per Jam..." value="{{$dataProfilPemilikLapangan->harga_per_jam}}" required>
                                                </div>
                                            </div> --}}
                                            <div class="form-group">
                                                <label class="form-label">Alamat</label>
                                                <div style="height:360px;width:100%;" id="map-container">
                                                    <div style="height: 100%; width: 100%; position: relative;z-index: 0;" id="map"></div>
                                                </div>
                                                <input type="hidden" class="form-control" id="lat-location-lapangan" name="lat_alamat_pemilik_lapangan" value="{{$dataProfilPemilikLapangan->titik_koordinat_lat}}">
                                                <input type="hidden" class="form-control" id="lng-location-lapangan" name="lng_alamat_pemilik_lapangan" value="{{$dataProfilPemilikLapangan->titik_koordinat_lng}}">
                                            </div>
                                            <div class="form-group" id="job-description-div">
                                                <label class="form-label">Alamat Tertulis</label>
                                                <div class="input-group"><span class="input-group-text"><i class="icofont icofont-address-book"></i></span>
                                                    <textarea class="form-control" id="alamat-tertulis" name="alamat_tertulis_pemilik_lapangan" rows="3">{{$dataProfilPemilikLapangan->alamat_lapangan}}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group float-end">
                                                <button class="btn btn-success btn-block" type="button" onclick="updateProfilLapangan()" style="display: inline;">Simpan</button>
                                                <button class="btn btn-secondary" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modal-ganti-password" data-bs-original-title="" title="">Ubah Password</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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
@endsection

@section('plugin_js')
<script src="{{url('/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{url('/assets/js/time-picker/jquery.timepicker.min.js')}}"></script>
<!-- <script src="{{url('/assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-time-picker/tempusdominus-bootstrap-4.min.js')}}"></script> -->
<script src="{{url('/assets/js/datepicker/date-time-picker/jquery.datetimepicker.full.min.js')}}"></script>
<script src="{{url('/assets/js/leaflet/leaflet.js')}}"></script>
<script src="{{url('/assets/js/leaflet/leaflet-gesture-handling.min.js')}}"></script>
<script>

    $("#buka-dari-jam").datetimepicker({
        datepicker: false,
        step: 30,
        format: 'H:i',
        // minTime: '6',
        // maxTime: '23:30',
        timepickerScrollbar: true,
        scrollTime: true
        // defaultTime: '{{$dataProfilPemilikLapangan->buka_dari_jam}}'
    }).val('{{date("H:i", strtotime($dataProfilPemilikLapangan->buka_dari_jam))}}');

    $("#buka-sampai-jam").datetimepicker({
        datepicker: false,
        step: 30,
        format: 'H:i',
        // minTime: '6',
        // maxTime: '23:30',
        timepickerScrollbar: true,
        scrollTime: true
        // defaultTime: '{{date("H:i", strtotime($dataProfilPemilikLapangan->buka_sampai_jam))}}'
    }).val('{{date("H:i", strtotime($dataProfilPemilikLapangan->buka_sampai_jam))}}');
    var lapanganImage_1 = "{{$lapanganImage['foto_lapangan_1']}}";
    var lapanganImage_2 = "{{$lapanganImage['foto_lapangan_2']}}";
    var lapanganImage_3 = "{{$lapanganImage['foto_lapangan_3']}}";

    if(lapanganImage_1 !== "") $('#image-preview-foto-lapangan-1').css("background-image", "url({!!Storage::url($lapanganImage['foto_lapangan_1'])!!})");
    if(lapanganImage_2 !== "") $('#image-preview-foto-lapangan-2').css("background-image", "url({!!Storage::url($lapanganImage['foto_lapangan_2'])!!})");
    if(lapanganImage_3 !== "") $('#image-preview-foto-lapangan-3').css("background-image", "url({!!Storage::url($lapanganImage['foto_lapangan_3'])!!})");

    $(".img-add").click(function(){
        $(this).closest(".row").find('.img-add').before('<div class="col-sm-2 imgUp"><div class="imagePreview"></div><label class="btn btn-primary">Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width:0px;height:0px;overflow:hidden;"></label><i class="fa fa-times del"></i></div>');
    });
    $(document).on("click", "i.del" , function() {
        $(this).parent().remove();
    });

    $(document).on("change",".upload-file", function(){
        var uploadFile = $(this);
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return;
        if (/^image/.test( files[0].type)){
            var reader = new FileReader();
            reader.readAsDataURL(files[0]);

            reader.onloadend = function(){
                uploadFile.closest(".img-up").find('.image-preview').css("background-image", "url("+this.result+")");
            }
        }
    });


    var dataProfilPemilikLapanganLat = "{{$dataProfilPemilikLapangan->titik_koordinat_lat}}";
    var dataProfilPemilikLapanganLng = "{{$dataProfilPemilikLapangan->titik_koordinat_lng}}";

    var latlngview = L.latLng(dataProfilPemilikLapanganLat, dataProfilPemilikLapanganLng);

    if(latlngview.lat === 0 && latlngview.lng === 0) latlngview = L.latLng('-8.660315332079342', '115.21636962890626');
    var map = L.map('map', {
        zoomControl:true,
        gestureHandling: true
    }).setView([latlngview.lat, latlngview.lng],17);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 20,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoieXVkaXByYXRpc3RoYSIsImEiOiJjbDJ6cHpsZ2owMzQ3M2JtcDQxdzFhdDd5In0.lPuxJO3S88Xy70aZfF4dLQ'
    }).addTo(map);
    var latlng = L.latLng(dataProfilPemilikLapanganLat, dataProfilPemilikLapanganLng);
    if(latlng != undefined){
        currentMarker = L.marker(latlng, {
            draggable: true
        }).addTo(map);
    }
    var currentMarker;
    map.on('click', function(e) {
        if (currentMarker != undefined) {
            map.removeLayer(currentMarker);
        };
        currentMarker = L.marker(e.latlng, {
            draggable: true
        }).addTo(map)
        latLngInput(e.latlng.lat, e.latlng.lng)
        currentMarker.on("dragend", function(ev) {
            var chagedPos = ev.target.getLatLng();
            latLngInput(chagedPos.lat, chagedPos.lng)
        });
    });
    if(currentMarker != undefined){
        currentMarker.on("dragend", function(ev) {
            var chagedPos = ev.target.getLatLng();
            latLngInput(chagedPos.lat, chagedPos.lng)
        });
    }
    function latLngInput(lat, lng){
        $('#lat-location-lapangan').val(lat).trigger('change');
        $('#lng-location-lapangan').val(lng).trigger('change');
    }

    function updateProfilLapangan(){
        swal.fire({
            title: "Perbarui Profil Lapangan?",
            text: "Apakah anda ingin perbarui profil lapangan?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                var form = $("#update-lapangan-profile").get(0)
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.updateProfil')}}",
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: new FormData(form),
                    success: function(data) {
                        var request = 'success';
                    },
                    error: function(xhr, status, error){
                        if(xhr.responseText.search("Call to a member function getRealPath() on null")){
                            $(document).ready(function (){
                                // console.log(xhr.responseJSON.errors)
                                swal.fire({title:"Ticket failed Update!", text: "This ticket failed to updated!", icon:"error"});
                                var errorMsg = $('');

                                $.each(xhr.responseJSON.errors, function (i, field) {

                                });
                            });
                        }else{
                            console.log(xhr)
                        }
                    }
                });
            }
        }).then((result) => {
        console.log("sadsa ", result.value)
            if(result.value){
            swal.fire({title:"Pembaharuan Profil Lapangan Berhasil!", icon:"success"})
            .then(function(){
                window.location.reload();
            });
            }
        })
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
                    window.location.reload();
                });
            }
        });
    }
</script>
@endsection
