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
                                    <form method="POST" action="{{ route('penyewaLapangan.register') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label>Nama Lengkap</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icon-user"></i></span>
                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="nama_penyewa_lapangan" placeholder="Name" value="{{ old('name') }}" required autocomplete="name" autofocus>
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
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email_penyewa_lapangan" placeholder="Test@gmail.com" value="{{ old('email') }}" required autocomplete="email">
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
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password_penyewa_lapangan" placeholder="*********" required autocomplete="new-password">
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
                                                <input id="nomor-telepon" type="number" class="form-control" name="nomor_telepon_penyewa_lapangan" placeholder="08xxxx" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary btn-block" type="submit">Buat Akun</button>
                                        </div>
                                        <p>Sudah memiliki akun?<a class="ms-2" href="{{route('login')}}">Sign in</a></p>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="top-profilesecondary" role="tabpanel" aria-labelledby="profile-top-tab">
                                    <form method="POST" action="{{ route('pemilikLapangan.register') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label>Nama Lapangan</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icofont icofont-badminton-birdie"></i></span>
                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="nama_lapangan_pemilik_lapangan" placeholder="Nama Lapangan" value="{{ old('name') }}" required autocomplete="name" autofocus>
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
                                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="nama_pemilik_lapangan" placeholder="Name" value="{{ old('name') }}" required autocomplete="name" autofocus>
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
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email_pemilik_lapangan" placeholder="Test@gmail.com" value="{{ old('email') }}" required autocomplete="email">
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
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password_pemilik_lapangan" placeholder="*********" required autocomplete="new-password">
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
                                                <input id="nomor-telepon" type="number" class="form-control" name="nomor_telepon_pemilik_lapangan" placeholder="08xxxx" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Upload Foto Lapangan</label>
                                            <div class="form-space theme-form row">
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
                                                <input id="harga-lapangan-per-jam" type="number" class="form-control" name="harga_lapangan_per_jam" placeholder="Harga Lapangan Per Jam..." required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Jumlah Court</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icofont icofont-badminton-birdie"></i></span>
                                                <input id="nomor-telepon" type="number" class="form-control" name="jumlah_court_pemilik_lapangan" placeholder="Jumlah Court..." required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="validationDefault04">Lapangan Buka Dari Hari</label>
                                                    <div class="input-group"><span class="input-group-text"><i class="icofont icofont-calendar"></i></span>
                                                        <select class="form-select" id="validationDefault04" name="lapangan_buka_dari_hari" required="">
                                                            <option selected="" disabled="" value="">Pilih Hari...</option>
                                                            <option value="1">Senin</option>
                                                            <option value="2">Selasa</option>
                                                            <option value="3">Rabu</option>
                                                            <option value="4">Kamis</option>
                                                            <option value="5">Jumat</option>
                                                            <option value="6">Sabtu</option>
                                                            <option value="7">Minggu</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="validationDefault04">Lapangan Buka Sampai Hari</label>
                                                    <div class="input-group"><span class="input-group-text"><i class="icofont icofont-calendar"></i></span>
                                                        <select class="form-select" id="validationDefault04" name="lapangan_buka_sampai_hari" required="">
                                                            <option selected="" disabled="" value="">Pilih Hari...</option>
                                                            <option value="1">Senin</option>
                                                            <option value="2">Selasa</option>
                                                            <option value="3">Rabu</option>
                                                            <option value="4">Kamis</option>
                                                            <option value="5">Jumat</option>
                                                            <option value="6">Sabtu</option>
                                                            <option value="7">Minggu</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3">
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
                                            <input type="hidden" class="form-control" id="lat-location-lapangan" name="lat_alamat_pemilik_lapangan">
                                            <input type="hidden" class="form-control" id="lng-location-lapangan" name="lng_alamat_pemilik_lapangan">
                                        </div>
                                        <div class="form-group" id="job-description-div">
                                            <label class="form-label">Alamat Tertulis</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icofont icofont-address-book"></i></span>
                                                <textarea class="form-control" id="alamat-tertulis" name="alamat_tertulis_pemilik_lapangan" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button class="btn btn-primary btn-block" type="submit">Buat Akun</button>
                                        </div>
                                        <p>Sudah memiliki akun?<a class="ms-2" href="{{route('login')}}">Sign in</a></p>
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
@endsection

@section('plugin_js')
<script src="{{url('/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-time-picker/jquery.datetimepicker.full.min.js')}}"></script>
<script src="{{url('/assets/js/leaflet/leaflet.js')}}"></script>
<script src="{{url('/assets/js/leaflet/leaflet-gesture-handling.min.js')}}"></script>
<script>
    $('#tanggal').datepicker({
        language: 'en',
        dateFormat: 'dd-mm-yyyy',
        minDate: new Date() // Now can select only dates, which goes after today
    });

    $("#buka-dari-jam").datetimepicker({
        datepicker: false,
        step: 60,
        format: 'H:i',
        // minTime: '6',
        // maxTime: '24:00',
    }); 

    $("#buka-sampai-jam").datetimepicker({
        datepicker: false,
        step: 60,
        format: 'H:i',
        // minTime: '6',
        // maxTime: '24:00',
    });

    $(".img-add").click(function(){
        $(this).closest(".row").find('.img-add').before('<div class="col-sm-2 imgUp"><div class="imagePreview"></div><label class="btn btn-primary">Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width:0px;height:0px;overflow:hidden;"></label><i class="fa fa-times del"></i></div>');
    });
    $(document).on("click", "i.del" , function() {
    // remove card
        $(this).parent().remove();
    // clear image
    // $(this).parent().find('.imagePreview').css("background-image","url('')");
    });
    
    $(document).on("change",".upload-file", function(){
        var uploadFile = $(this);
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
            if (/^image/.test( files[0].type)){ // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file
    
                reader.onloadend = function(){ // set image data as background of div
                    //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
                uploadFile.closest(".img-up").find('.image-preview').css("background-image", "url("+this.result+")");
            }
        }
    });


    var latlngview = L.latLng($('#lat-location-lapangan').val(), $('#lng-location-lapangan').val());
    if(latlngview.lat == 0 && latlngview.lng == 0) latlngview = L.latLng('-8.660315332079342', '115.21636962890626');
    var map = L.map('map', {
        zoomControl:true,
        gestureHandling: true
    }).setView([latlngview.lat, latlngview.lng],12.5);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoieXVkaXByYXRpc3RoYSIsImEiOiJjbDJ6cHpsZ2owMzQ3M2JtcDQxdzFhdDd5In0.lPuxJO3S88Xy70aZfF4dLQ'
    }).addTo(map);
    var latlng = L.latLng($('#lat-location-lapangan').val(), $('#lng-location-lapangan').val());
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

    $("a[href='#top-profilesecondary']").on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href") // activated tab
        console.log("asda")
        map.invalidateSize();
    });

</script>
@endsection
