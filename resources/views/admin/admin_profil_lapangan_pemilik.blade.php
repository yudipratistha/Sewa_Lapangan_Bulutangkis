@extends('layouts.app')

@section('title', 'Pemilik Lapangan Dashboard')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/jquery-ui.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/jquery.timepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/jquery.datetimepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/photoswipe.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/leaflet.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/leaflet-gesture-handling.min.css')}}">
<style>
.nav-tabs {
    white-space: nowrap !important;
    flex-wrap: nowrap !important;
    max-width: 85% !important;
    overflow-x: scroll !important;
    overflow-y: hidden !important;
    -webkit-overflow-scrolling: touch !important;
}
.nav-item>li {
    display: inline-block !important;
}
#icons {
    margin: 0;
    padding: 0;
}
#icons li {
    margin: 2px;
    position: relative;
    padding: 4px 0;
    cursor: pointer;
    float: left;
    list-style: none;
}
#icons span.ui-icon {
    float: left;
    margin: 0 4px;
}
</style>
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
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('penyewaLapangan.dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Profil Lapangan</li>
                        </ol>
                    </div>
                    <div class="card" style="margin-bottom: 10px;">
                        <div class="card-header typography pb-0 pb-0">
                            <h3 class="f-w-700 mb-2">Nama Lapangan: {{$dataLapangan->nama_lapangan}}</h3>
                            <h6 class="f-w-300">Nama Pemilik Lapangan: {{$dataLapangan->nama_pemilik_lapangan}}</h6>
                        </div>
                        <div class="card-body">
                            <div class="row my-gallery lapangan-profile-galery gallery" id="aniimated-thumbnials" itemscope="">
                                <figure class="col-md-4 img-hover hover-1" itemprop="associatedMedia" itemscope=""><a href="{!!url(Storage::url($dataLapangan->foto_lapangan_1))!!}" itemprop="contentUrl" data-size="1600x950">
                                    <div><img src="{!!Storage::url($dataLapangan->foto_lapangan_1)!!}" itemprop="thumbnail" alt="Image description"></div></a>
                                </figure>
                                <figure class="col-md-4 img-hover hover-1" itemprop="associatedMedia" itemscope=""><a href="{!!url(Storage::url($dataLapangan->foto_lapangan_2))!!}" itemprop="contentUrl" data-size="1600x950">
                                    <div><img src="{!!Storage::url($dataLapangan->foto_lapangan_2)!!}" itemprop="thumbnail" alt="Image description"></div></a>
                                </figure>
                                <figure class="col-md-4 img-hover hover-1" itemprop="associatedMedia" itemscope=""><a href="{!!url(Storage::url($dataLapangan->foto_lapangan_3))!!}" itemprop="contentUrl" data-size="1600x950">
                                    <div><img src="{!!Storage::url($dataLapangan->foto_lapangan_3)!!}" itemprop="thumbnail" alt="Image description"></div></a>
                                </figure>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Harga Lapangan Per Jam</label>
                                    <div class="input-group"><span class="input-group-text"><i class="icofont icofont-money"></i></span>
                                        <input id="harga-lapangan-per-jam" type="number" class="form-control" name="harga_lapangan_per_jam" value="{{$dataLapangan->harga_per_jam}}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Jumlah Court</label>
                                    <div class="input-group"><span class="input-group-text"><i class="icofont icofont-badminton-birdie"></i></span>
                                        <input id="nomor-telepon" type="number" class="form-control" name="jumlah_court_pemilik_lapangan" value="{{$dataLapangan->jumlah_court}}" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="validationDefault04">Lapangan Buka Dari Hari</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icofont icofont-calendar"></i></span>
                                                <select class="form-select" id="validationDefault04" name="lapangan_buka_dari_hari" disabled>
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
                                                <select class="form-select" id="validationDefault04" name="lapangan_buka_sampai_hari" disabled>
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
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label" for="validationDefault04">Lapangan Buka Dari Jam</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icofont icofont-clock-time"></i></span>
                                                <input class="form-control" id="buka-dari-jam" name="lapangan_buka_dari_jam" type="text" value="" placeholder="Pilih Jam" data-target="#buka-dari-jam" data-toggle="datetimepicker" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="validationDefault04">Lapangan Buka Sampai Jam</label>
                                            <div class="input-group"><span class="input-group-text"><i class="icofont icofont-clock-time"></i></span>
                                                <input class="form-control timepicker" id="buka-sampai-jam" name="lapangan_buka_sampai_jam" type="text" value="" placeholder="Pilih Jam" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h6>Titik Lapangan:</h6>
                                <div style="height:360px;width:100%;" id="map-container">
                                    <div style="height: 100%; width: 100%; position: relative;z-index: 0;" id="map"></div>
                                </div>
                                <input type="hidden" class="form-control" id="lat-location-lapangan" name="lat_alamat_pemilik_lapangan">
                                <input type="hidden" class="form-control" id="lng-location-lapangan" name="lng_alamat_pemilik_lapangan">

                                <div class="form-group mt-3" id="job-description-div">
                                    <label class="form-label">Alamat Tertulis</label>
                                    <div class="input-group"><span class="input-group-text"><i class="icofont icofont-address-book"></i></span>
                                        <textarea class="form-control" id="alamat-tertulis" name="alamat_tertulis_pemilik_lapangan" disabled rows="3">{{$dataLapangan->alamat_lapangan}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="unapproveLapangan({{ $dataLapangan->lapangan_id }})" class="btn btn-square btn-outline-warning" style="float:right;">Unapprove</button>
                            <button type="button" onclick="approveLapangan({{ $dataLapangan->lapangan_id }})" class="btn btn-square btn-outline-primary" style="float:right; margin-right:10px">Approve</button>
                        </div>
                    </div>
                </div>
                <!-- Root element of PhotoSwipe. Must have class pswp.-->
                <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                <!--
                Background of PhotoSwipe.
                It's a separate element, as animating opacity is faster than rgba().
                -->
                    <div class="pswp__bg"></div>
                    <!-- Slides wrapper with overflow:hidden.-->
                    <div class="pswp__scroll-wrap">
                        <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory.-->
                        <!-- don't modify these 3 pswp__item elements, data is added later on.-->
                        <div class="pswp__container">
                            <div class="pswp__item"></div>
                            <div class="pswp__item"></div>
                            <div class="pswp__item"></div>
                        </div>
                        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed.-->
                        <div class="pswp__ui pswp__ui--hidden">
                            <div class="pswp__top-bar">
                                <!-- Controls are self-explanatory. Order can be changed.-->
                                <div class="pswp__counter"></div>
                                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                                <button class="pswp__button pswp__button--share" title="Share"></button>
                                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                                <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR-->
                                <!-- element will get class pswp__preloader--active when preloader is running-->
                                <div class="pswp__preloader">
                                    <div class="pswp__preloader__icn">
                                        <div class="pswp__preloader__cut">
                                            <div class="pswp__preloader__donut"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                                <div class="pswp__share-tooltip"></div>
                            </div>
                            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                            <div class="pswp__caption">
                                <div class="pswp__caption__center"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Container-fluid Ends-->
        </div>
        <!-- footer start-->
        @include('layouts.footer')
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
<script src="{{url('/assets/js/photoswipe/photoswipe.min.js')}}"></script>
<script src="{{url('/assets/js/photoswipe/photoswipe-ui-default.min.js')}}"></script>
<script src="{{url('/assets/js/photoswipe/photoswipe.js')}}"></script>
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
    }).val('{{date("H:i", strtotime($dataLapangan->buka_dari_jam))}}');

    $("#buka-sampai-jam").datetimepicker({
        datepicker: false,
        step: 30,
        format: 'H:i',
        // minTime: '6',
        // maxTime: '23:30',
        timepickerScrollbar: true,
        scrollTime: true
    }).val('{{date("H:i", strtotime($dataLapangan->buka_sampai_jam))}}');

    var dataProfilPemilikLapanganLat = "{{$dataLapangan->titik_koordinat_lat}}";
    var dataProfilPemilikLapanganLng = "{{$dataLapangan->titik_koordinat_lng}}";

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

    L.marker(latlngview).addTo(map);

    $(".pilih-jenis-booking").on("click", function(){
        $(this).children('.radio').children('input').prop('checked', true);

        $(".pilih-jenis-booking-card").removeClass("invalid-pilih-jenis-booking-card");
        $(".pilih-jenis-booking").removeClass("invalid-pilih-jenis-booking");
    });

    function approveLapangan(lapanganId){
        link = "{{route('administrator.approveProfilLapangan', ':id')}}";
        link = link.replace(':id', lapanganId);

        swal.fire({
            title: "Approve Lapangan?",
            text: "Status verifikasi lapangan akan diperbaharui!",
            icon: "warning",
            showCancelButton: true,
            // confirmButtonClass: "btn-danger",
            confirmButtonText: "Save",
            closeOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: link,
                    datatype : "json",
                    data: {"_token": "{{ csrf_token() }}"},
                    success: function(data){

                    },
                    error: function(data){
                        swal.fire({title:"Aprrove lapangan Gagal!", text:"Perbarui status verifikasi lapangan gagal di proses.", icon:"error"});
                    }
                });
            }
        }).then((result) => {
            if(result.value){
                swal.fire({title:"Aprrove lapangan Berhasil!", text:"Status verifikasi lapangan telah berhasil di perbarui.", icon:"success"})
                .then(function(){
                    history.back();
                });
            }
        });
    }

    function unapproveLapangan(lapanganId){
        link = "{{route('administrator.unapproveProfilLapangan', ':id')}}";
        link = link.replace(':id', lapanganId);

        swal.fire({
            title: "Unapprove Lapangan?",
            text: "Status verifikasi lapangan akan diperbaharui!",
            icon: "warning",
            html: '\
            <div class="swal2-html-container mb-0" id="swal2-html-container" style="display: block; margin:0px;">Status verifikasi lapangan akan diperbaharui!</div>\
            <div class="form-group mt-5 mb-0">\
                <label class="form-label pull-left">Alasan Ditolak:</label>\
                <textarea class="swal2-input form-control mt-0 ms-0 me-0" id="alasan-ditolak" name="alasan_ditolak" rows="3" style="width: 100%; height: 64px;"></textarea>\
            </div>',
            showCancelButton: true,
            // confirmButtonClass: "btn-danger",
            confirmButtonText: "Save",
            closeOnConfirm: true,
            onOpen: function(a) {
                console.log(a)

            },
            preConfirm: () => {
                $('#alasan-ditolak').on("change keyup paste", function() {
                    $('#swal2-validation-message').remove();
                    $("#alasan-ditolak").removeClass("is-invalid");
                    $("#alasan-ditolak").addClass("is-valid");
                });

                if($("#alasan-ditolak").val() === ''){
                    $("#alasan-ditolak").addClass("is-invalid");
                    swal.showValidationMessage('Alasan Tidak Boleh Kosong!');
                }else{
                    return $.ajax({
                        type: "POST",
                        url: link,
                        datatype : "json",
                        data: {"_token": "{{ csrf_token() }}", 'alasan_ditolak': $("#alasan-ditolak").val()},
                        success: function(data){

                        },
                        error: function(data){
                            swal.fire({title:"Unapprove Lapangan Gagal!", text:"Status verifikasi lapangan gagal di proses.", icon:"error"});
                        }
                    });
                }
            }
        }).then((result) => {
            if(result.value){
                swal.fire({title:"Unapprove Lapangan Berhasil!", text:"Status verifikasi lapangan telah berhasil di perbarui.", icon:"success"})
                .then(function(){
                    history.back();
                });
            }
        });
    }
</script>

@endsection
