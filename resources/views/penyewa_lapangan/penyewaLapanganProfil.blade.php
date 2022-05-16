@extends('layouts.app')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/dropzone.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/jquery.timepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/leaflet.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/leaflet-gesture-handling.min.css')}}">

@endsection

@section('content')
<div class="container-fluid">
    <div class="edit-profile">
        <div class="row">
            <div class="col-xl-12 xl-100 col-lg-12 box-col-12">
                <div class="card pt-2">
                    <div class="card-header">
                        <!-- <h5 class="pull-left">Material tab with color</h5> -->
                        <h5 class="pull-left">Edit Profil Pemilik Lapangan</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('penyewaLapangan.register') }}">
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
<script src="{{url('/assets/js/time-picker/jquery.timepicker.min.js')}}"></script>
<script src="{{url('/assets/js/leaflet/leaflet.js')}}"></script>
<script src="{{url('/assets/js/leaflet/leaflet-gesture-handling.min.js')}}"></script>
<script>
    $('#tanggal').datepicker({
        language: 'en',
        dateFormat: 'dd-mm-yyyy',
        minDate: new Date() // Now can select only dates, which goes after today
    });

    $('.timepicker').timepicker({
        timeFormat: 'h:mm p',
        interval: 30,
        minTime: '6:00am',
        maxTime: '11:00pm',
        defaultTime: '6:00am',
        startTime: '6:00am',
        dynamic: true,
        dropdown: true,
        scrollbar: true
    });

  $(".imgAdd").click(function(){
  $(this).closest(".row").find('.imgAdd').before('<div class="col-sm-2 imgUp"><div class="imagePreview"></div><label class="btn btn-primary">Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width:0px;height:0px;overflow:hidden;"></label><i class="fa fa-times del"></i></div>');
    });
    $(document).on("click", "i.del" , function() {
    // 	to remove card
    $(this).parent().remove();
    // to clear image
    // $(this).parent().find('.imagePreview').css("background-image","url('')");
    });
    $(function() {
        $(document).on("change",".uploadFile", function()
        {
                var uploadFile = $(this);
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
    
            if (/^image/.test( files[0].type)){ // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file
    
                reader.onloadend = function(){ // set image data as background of div
                    //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
    uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url("+this.result+")");
                }
            }
        
        });
    });


        var latlngview = L.latLng($('#job-lat-location').val(), $('#job-lng-location').val());
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
        var latlng = L.latLng($('#job-lat-location').val(), $('#job-lng-location').val());
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
            $('#job-lat-location').val(lat).trigger('change');
            $('#job-lng-location').val(lng).trigger('change');
        }

</script>
@endsection
