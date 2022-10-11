@extends('layouts.app')

@section('title', 'Pemilik Lapangan Dashboard')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/jquery-ui.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/sweetalert2.css')}}">
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
                        <div class="col-md-12 card-header row pb-0 pe-0">
                            <div class="col-md-4 mb-3 mt-0 row g-3">
                                <label class="col-md-3 mt-2 col-form-label">Pilih Tanggal</label>
                                <div class="col-md-9 mt-2">
                                    <div class="input-group date">
                                        <input class="form-control digits" id="tanggal" name="tanggal" type="text" placeholder="dd-mm-yyyy" readonly>
                                        <div class="input-group-text"><i class="fa fa-calendar"> </i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <ul class="pull-right nav nav-tabs border-tab nav-success" id="top-tabdanger" role="tablist">
                                    @for ($court= 1; $court <= $dataLapangan->jumlah_court; $court++)
                                        <li class="nav-item"><a class="nav-link @if($court === 1) active @endif" id="top-home-danger" data-bs-toggle="tab" href="#court-{{$court}}" role="tab" aria-controls="top-homedanger" aria-selected="true"><i class="icofont icofont-badminton-birdie"></i>Court {{$court}}</a>
                                            <div class="material-border"></div>
                                        </li>
                                    @endfor
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tabbed-card">
                                
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
                            <!-- <a href="{{route('penyewaLapangan.pesanLapanganPerJam', [$dataLapangan->lapangan_id, str_replace(' ', '-', strtolower($dataLapangan->nama_lapangan))])}}"><button type="button" class="btn btn-square btn-outline-blue mt-1" style="float:right;">Pesan Sekarang!</button></a> -->
                            <button type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modal-jenis-booking" data-bs-original-title="" title="" class="btn btn-square btn-outline-blue mt-1" style="float:right;">Pilih Jenis Booking</button>
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

<!-- Modal Jenis Booking-->
<div class="modal fade" id="modal-jenis-booking" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header text-center d-block">
                <h4 class="modal-title ">Pilih Jenis Booking</h3>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card paket-bulanan-card">
                            <div class="pilih-jenis-booking media p-20" style="-webkit-box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);cursor: pointer;">
                                <div class="media-body">
                                    <h6 class="mt-0">Paket Bulanan</h6>
                                </div>
                                <div class="radio radio-primary me-3" style="display: contents;">
                                    <input id="radio30" type="radio" name="pilih_pembayaran" value="bulanan">
                                    <label for="radio30"></label>
                                </div>
                            </div>
                        </div>
                        <div class="card pilih-jenis-booking-card">
                            <div class="pilih-jenis-booking media p-20" style="-webkit-box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);cursor: pointer;">
                                <div class="media-body">
                                    <h6 class="mt-0">Paket Per Jam</h6>
                                </div>
                                <div class="radio radio-primary me-3" style="display: contents;">
                                    <input id="radio30" type="radio" name="pilih_pembayaran" value="perjam">
                                    <label for="radio30"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-square btn-outline-light txt-dark" data-bs-dismiss="modal">Close</button>
                <button type="button" onClick="pesanLapangan()" class="btn btn-square btn-outline-blue">Konfirmasi Jenis Booking</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('plugin_js')
<script src="{{url('/assets/js/datepicker/date-picker-jquery-ui/jquery-ui.js')}}"></script>
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
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        autoclose: true,
        onSelect: function(dateText) {
            $('#tgl-booking').empty().append(dateText);
            date = dateText;
            $.ajax({
                url: "{{route('penyewaLapangan.getDataProfilLapangan', $dataLapangan->lapangan_id)}}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "tanggal" : date
                },
                dataType: "json",
                success:function(data){
                    console.log(date)
                    for(let courtCount= 1; courtCount<= jumlah_court; courtCount++){
                        $('#table-court-'+courtCount).DataTable().clear().draw();
                        $('#table-court-'+courtCount).DataTable().rows.add(data['court_'+courtCount]);
                        $('#table-court-'+courtCount).DataTable().columns.adjust().draw();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
            return $('#tanggal').trigger('change');
        }
    }).datepicker('setDate', new Date());
    $('.ui-datepicker-current-day').click();

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

    function pesanLapangan(){
        var pilihPembayaran = $(".pilih-jenis-booking").children('.radio').children('input').filter(":checked").val();

        if(pilihPembayaran === "bulanan"){
            Swal.fire({
                title: 'Konfirmasi Jenis Sewa Bulanan?',
			    icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Pesan Sekarang!',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    window.location.href = "{{route('penyewaLapangan.pesanLapanganBulanan', [$dataLapangan->lapangan_id, str_replace(' ', '-', strtolower($dataLapangan->nama_lapangan))])}}";
                }
            });
            
        }else if(pilihPembayaran === "perjam"){
            Swal.fire({
                title: 'Konfirmasi Jenis Sewa Per Jam?',
			    icon: "warning",
                showCancelButton: true,
                confirmButtonText: 'Pesan Sekarang!',
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    window.location.href = "{{route('penyewaLapangan.pesanLapanganPerJam', [$dataLapangan->lapangan_id, str_replace(' ', '-', strtolower($dataLapangan->nama_lapangan))])}}";
                }
            });
        }
    }
</script>

@endsection
