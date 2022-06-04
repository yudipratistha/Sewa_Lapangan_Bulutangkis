@extends('layouts.app')

@section('title', 'Pemilik Lapangan Dashboard')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/photoswipe.css')}}">
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
            <!-- Container-fluid starts-->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('index')}}">Home</a></li>
                            <li class="breadcrumb-item active">Profil Lapangan</li>
                        </ol>
                    </div>
                    <div class="card" style="margin-bottom: 10px;">
                        <div class="card-header typography pb-0 pb-0">
                            <h3 class="f-w-700 mb-2">{{$dataLapangan->nama_lapangan}}</h3>
                            <h6 class="f-w-300">Alamat: {{$dataLapangan->alamat_lapangan}}</h6>
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
                    <div class="card" style="margin-bottom: 10px;">
                        <div class="card-header">
                            <div class="mb-3 row g-3">
                                <label class="col-xl-1 col-sm-3 col-lg-1 col-form-label">Pilih Tanggal</label>
                                <div class="col-xl-3 col-sm-5 col-lg-7">
                                    <div class="input-group date">
                                        <input class="form-control digits" id="tanggal" name="tanggal" type="text" data-bs-original-title="" title="">
                                        <div class="input-group-text"><i class="fa fa-calendar"> </i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tabbed-card">
                                <ul class="pull-right nav nav-tabs border-tab nav-success" id="top-tabdanger" role="tablist">
                                @for ($court= 1; $court <= $dataLapangan->jumlah_court; $court++)
                                    <li class="nav-item"><a class="nav-link @if($court === 1) active @endif" id="top-home-danger" data-bs-toggle="tab" href="#court-{{$court}}" role="tab" aria-controls="top-homedanger" aria-selected="true"><i class="icofont icofont-badminton-birdie"></i>Court {{$court}}</a>
                                        <div class="material-border"></div>
                                    </li>
                                @endfor
                                </ul>
                                
                                <div class="tab-content" id="top-tabContentdanger">
                                    @for ($court= 1; $court <= $dataLapangan->jumlah_court; $court++)
                                        <div class="tab-pane fade @if($court === 1) active show @endif" id="court-{{$court}}" role="tabpanel" aria-labelledby="top-home-tab">
                                            <div class="table-responsive">
                                                <table class="display datatables hover-table-court-profile" id="table-court-{{$court}}">
                                                    <thead>
                                                        <tr>
                                                            <th>Jam</th>
                                                            <th>Penyewa</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($dataWaktuLapangan as $dataWaktuLapanganKey => $dataWaktuLapanganValue)
                                                            <tr>
                                                                <td>{{$dataWaktuLapanganValue}}</td>
                                                                @php 
                                                                    $status_penyewa = false; 
                                                                    $status_court = false; 
                                                                @endphp
                                                                @if(isset($dataLapanganBooking))
                                                                    @foreach($dataLapanganBooking as $dataLapanganBookingKey => $dataLapanganBookingValue) 
                                                                        @if($court === $dataLapanganBookingValue->court)
                                                                            @for($i=strtotime($dataLapanganBookingValue->jam_mulai); $i < strtotime($dataLapanganBookingValue->jam_selesai); $i+=3600)
                                                                                @if($dataWaktuLapanganValue === date('H:i', $i) . " - ". date('H:i', $i+3600))
                                                                                    <td>Booked</td>
                                                                                    @php $status_penyewa = true; @endphp
                                                                                @endif
                                                                            @endfor
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                                @foreach($dataStatusLapangan as $dataStatusLapanganKey => $dataStatusLapanganValue)
                                                                    @if($court === $dataStatusLapanganValue->court)
                                                                        @if($status_penyewa !== true && $dataWaktuLapanganValue === date('H:i', strtotime($dataStatusLapanganValue->jam_status_berlaku_dari)) . " - ". date('H:i', strtotime($dataStatusLapanganValue->jam_status_berlaku_sampai)))
                                                                            <td>
                                                                                @if($dataStatusLapanganValue->status === 'Available') 
                                                                                    Tersedia
                                                                                @elseif($dataStatusLapanganValue->status === 'Unavailable') 
                                                                                    Tidak Tersedia
                                                                                @endif
                                                                            </td>
                                                                            @php $status_court = true; @endphp
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                            </tr>
                                                        @endforeach
                                                    <tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <h6>Alamat:</h6>
                                <div style="height:360px;width:100%;" id="map-container">
                                    <div style="height: 100%; width: 100%; position: relative;z-index: 0;" id="map"></div>
                                </div>
                                <input type="hidden" class="form-control" id="lat-location-lapangan" name="lat_alamat_pemilik_lapangan">
                                <input type="hidden" class="form-control" id="lng-location-lapangan" name="lng_alamat_pemilik_lapangan">
                            </div>   
                            <a href="{{route('penyewaLapangan.pesanLapangan', [$dataLapangan->lapangan_id, str_replace(' ', '-', strtolower($dataLapangan->nama_lapangan))])}}"><button type="button" class="btn btn-square btn-outline-blue mt-1" style="float:right;">Pesan Sekarang!</button></a>
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
<script src="{{url('/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{url('/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>
<script src="{{url('/assets/js/photoswipe/photoswipe.min.js')}}"></script>
<script src="{{url('/assets/js/photoswipe/photoswipe-ui-default.min.js')}}"></script>
<script src="{{url('/assets/js/photoswipe/photoswipe.js')}}"></script>
<script src="{{url('/assets/js/leaflet/leaflet.js')}}"></script>
<script src="{{url('/assets/js/leaflet/leaflet-gesture-handling.min.js')}}"></script>

<script>
    var jumlah_court = {!! json_encode($dataLapangan->jumlah_court) !!}

    for(let court= 1; court<= jumlah_court; court++){
        $("#table-court-"+court).dataTable({
            bFilter: false,
            "columns": [
                { "orderable": true, "width": "10%" },
                null,
            ],
        });

    }

    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true}).columns.adjust();
    });

    $('#tanggal').datepicker({
        language: 'en',
        dateFormat: 'dd-mm-yyyy',
        minDate: new Date() // Now can select only dates, which goes after today
    });

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
</script>

@endsection
