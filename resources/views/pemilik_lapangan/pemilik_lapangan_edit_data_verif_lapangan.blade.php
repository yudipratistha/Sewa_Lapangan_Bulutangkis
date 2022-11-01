@extends('layouts.app')

@section('plugin_css')
    <link rel="stylesheet" type="text/css" href="{{url('/assets/css/datatables.css')}}">
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
            @include('layouts.sidebar_no_menu')
            <!-- Page Sidebar End-->
            <div class="page-body">
                <div class="container-fluid">
                    <div class="card pt-2">
                        <div class="card-header">
                            <!-- <h5 class="pull-left">Material tab with color</h5> -->
                            <h5 class="pull-left">Edit Data Verifikasi Lapangan</h5>
                        </div>
                        <div class="card-body">
                            <form id="update-data-lapangan" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">Nama Lapangan</label>
                                    <div class="input-group"><span class="input-group-text"><i class="icofont icofont-badminton-birdie"></i></span>
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="nama_lapangan_pemilik_lapangan" placeholder="Nama Lapangan" value="{{$dataLapangan->nama_lapangan}}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nama Lengkap</label>
                                    <div class="input-group"><span class="input-group-text"><i class="icon-user"></i></span>
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="nama_pemilik_lapangan" placeholder="Name" value="{{$dataLapangan->nama_pemilik_lapangan}}" required autocomplete="name" autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Alamat Email</label>
                                    <div class="input-group"><span class="input-group-text"><i class="icon-email"></i></span>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email_pemilik_lapangan" placeholder="Test@gmail.com" value="{{$dataLapangan->email}}" disabled>
                                    </div>
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
                                        <input id="nomor-telepon" type="number" class="form-control" name="nomor_telepon_pemilik_lapangan" placeholder="08xxxx" value="{{$dataLapangan->nomor_telepon}}" required>
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
                                <div class="form-group">
                                    <label>Harga Lapangan Per Jam</label>
                                    <div class="input-group"><span class="input-group-text"><i class="icofont icofont-money"></i></span>
                                        <input id="harga-lapangan-per-jam" type="number" class="form-control" name="harga_lapangan_per_jam" placeholder="Harga Lapangan Per Jam..." value="{{$dataLapangan->harga_per_jam}}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah Court</label>
                                    <div class="input-group"><span class="input-group-text"><i class="icofont icofont-badminton-birdie"></i></span>
                                        <input id="nomor-telepon" type="number" class="form-control" name="jumlah_court_pemilik_lapangan" placeholder="Jumlah Court..." value="{{$dataLapangan->jumlah_court}}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="validationDefault04">Lapangan Buka Dari Hari</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icofont icofont-calendar"></i></span>
                                                <select class="form-select" id="validationDefault04" name="lapangan_buka_dari_hari" required="">
                                                    <option disabled="" value="">Pilih Hari...</option>
                                                    <option value="1" @if($dataLapangan->buka_dari_hari == 1) selected @endif>Senin</option>
                                                    <option value="2" @if($dataLapangan->buka_dari_hari == 2) selected @endif>Selasa</option>
                                                    <option value="3" @if($dataLapangan->buka_dari_hari == 3) selected @endif>Rabu</option>
                                                    <option value="4" @if($dataLapangan->buka_dari_hari == 4) selected @endif>Kamis</option>
                                                    <option value="5" @if($dataLapangan->buka_dari_hari == 5) selected @endif>Jumat</option>
                                                    <option value="6" @if($dataLapangan->buka_dari_hari == 6) selected @endif>Sabtu</option>
                                                    <option value="7" @if($dataLapangan->buka_dari_hari == 7) selected @endif>Minggu</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="validationDefault04">Lapangan Buka Sampai Hari</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icofont icofont-calendar"></i></span>
                                                <select class="form-select" id="validationDefault04" name="lapangan_buka_sampai_hari" required="">
                                                    <option disabled="" value="">Pilih Hari...</option>
                                                    <option value="1" @if($dataLapangan->buka_sampai_hari == 1) selected @endif>Senin</option>
                                                    <option value="2" @if($dataLapangan->buka_sampai_hari == 2) selected @endif>Selasa</option>
                                                    <option value="3" @if($dataLapangan->buka_sampai_hari == 3) selected @endif>Rabu</option>
                                                    <option value="4" @if($dataLapangan->buka_sampai_hari == 4) selected @endif>Kamis</option>
                                                    <option value="5" @if($dataLapangan->buka_sampai_hari == 5) selected @endif>Jumat</option>
                                                    <option value="6" @if($dataLapangan->buka_sampai_hari == 6) selected @endif>Sabtu</option>
                                                    <option value="7" @if($dataLapangan->buka_sampai_hari == 7) selected @endif>Minggu</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 mt-1">
                                        <div class="col-md-6">
                                            <label class="form-label" for="validationDefault04">Lapangan Buka Dari Jam</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icofont icofont-clock-time"></i></span>
                                                <input class="form-control" id="buka-dari-jam" name="lapangan_buka_dari_jam" type="text" value="" placeholder="Pilih Jam" data-target="#buka-dari-jam" data-toggle="datetimepicker">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="validationDefault04">Lapangan Buka Sampai Jam</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icofont icofont-clock-time"></i></span>
                                                <input class="form-control timepicker" id="buka-sampai-jam" name="lapangan_buka_sampai_jam" type="text" value="" placeholder="Pilih Jam">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Alamat</label>
                                    <div style="height:360px;width:100%;" id="map-container">
                                        <div style="height: 100%; width: 100%; position: relative;z-index: 0;" id="map"></div>
                                    </div>
                                    <input type="hidden" class="form-control" id="lat-location-lapangan" name="lat_alamat_pemilik_lapangan" value="{{$dataLapangan->titik_koordinat_lat}}">
                                    <input type="hidden" class="form-control" id="lng-location-lapangan" name="lng_alamat_pemilik_lapangan" value="{{$dataLapangan->titik_koordinat_lng}}">
                                </div>
                                <div class="form-group" id="job-description-div">
                                    <label class="form-label">Alamat Tertulis</label>
                                    <div class="input-group"><span class="input-group-text"><i class="icofont icofont-address-book"></i></span>
                                        <textarea class="form-control" id="alamat-tertulis" name="alamat_tertulis_pemilik_lapangan" rows="3">{{$dataLapangan->alamat_lapangan}}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary pull-right" type="button" onclick="updateDataLapangan()">Update Data Lapangan</button>
                                </div>
                            </form>
                        </div>
                    </div>
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
            // defaultTime: '{{$dataLapangan->buka_dari_jam}}'
        }).val('{{date("H:i", strtotime($dataLapangan->buka_dari_jam))}}');

        $("#buka-sampai-jam").datetimepicker({
            datepicker: false,
            step: 30,
            format: 'H:i',
            // minTime: '6',
            // maxTime: '23:30',
            timepickerScrollbar: true,
            scrollTime: true
            // defaultTime: '{{date("H:i", strtotime($dataLapangan->buka_sampai_jam))}}'
        }).val('{{date("H:i", strtotime($dataLapangan->buka_sampai_jam))}}');

        var lapanganImage_1 = "{{Storage::url($lapanganImage['foto_lapangan_1'])}}";
        var lapanganImage_2 = "{{Storage::url($lapanganImage['foto_lapangan_2'])}}";
        var lapanganImage_3 = "{{Storage::url($lapanganImage['foto_lapangan_3'])}}";
        console.log(lapanganImage_1)
        $('#image-preview-foto-lapangan-1').css("background-image", "url('"+lapanganImage_1+"')")
        if(lapanganImage_1 !== "") $('#image-preview-foto-lapangan-1').css("background-image", "url('"+lapanganImage_1+"')");
        if(lapanganImage_2 !== "") $('#image-preview-foto-lapangan-2').css("background-image", "url('"+lapanganImage_2+"')");
        if(lapanganImage_3 !== "") $('#image-preview-foto-lapangan-3').css("background-image", "url('"+lapanganImage_3+"')");

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


        var dataLapanganLat = "{{$dataLapangan->titik_koordinat_lat}}";
        var dataLapanganLng = "{{$dataLapangan->titik_koordinat_lng}}";

        var latlngview = L.latLng(dataLapanganLat, dataLapanganLng);

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
        var latlng = L.latLng(dataLapanganLat, dataLapanganLng);
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

        function updateDataLapangan(){
            swal.fire({
                title: "Perbarui Data Lapangan?",
                text: "Apakah anda ingin perbarui data lapangan?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: "Simpan",
                showLoaderOnConfirm: true,
                preConfirm: (login) => {
                    var form = $("#update-data-lapangan").get(0)
                    return $.ajax({
                        type: "POST",
                        url: "{{route('pemilik_lapangan.updateDataVerifikasiDitolak')}}",
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
                swal.fire({title:"Perbauri Profil Lapangan Berhasil!", icon:"success"})
                .then(function(){
                    window.location.reload();
                });
                }
            })
        }
    </script>
@endsection
