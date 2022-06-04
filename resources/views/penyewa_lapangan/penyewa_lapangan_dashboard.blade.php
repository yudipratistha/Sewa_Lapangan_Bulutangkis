@extends('layouts.app')

@section('title', 'Pemilik Lapangan Dashboard')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/leaflet.css')}}">
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
                    <div class="card">
                        <div class="card-body">
                            <div style="min-height: 80vh;height:80vh;position:relative;width:100%;">
                                <div style="height: 100%; width: 100%; position: relative;z-index: 0;" id="map"></div>
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
<script src="{{url('/assets/js/leaflet/leaflet.js')}}"></script>

<script>
    navigator.geolocation.getCurrentPosition(function(location) {
        var latlng = new L.LatLng(location.coords.latitude, location.coords.longitude);

        var map = L.map('map').setView(latlng, 13)
        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}',{
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://mapbox.com">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox/streets-v11',
            accessToken: 'pk.eyJ1IjoieXVkaXByYXRpc3RoYSIsImEiOiJjbDJ6cHpsZ2owMzQ3M2JtcDQxdzFhdDd5In0.lPuxJO3S88Xy70aZfF4dLQ'
        }).addTo(map);

        var geojsonMarkerOptions = {
            radius: 8,
            fillColor: "#ff7800",
            color: "#000",
            weight: 1,
            opacity: 1,
            fillOpacity: 0.8
        };
        
        var marker = L.circleMarker(latlng, geojsonMarkerOptions).addTo(map);

        $.ajax({
            url: "{{route('penyewaLapangan.getDataLapangan')}}",
            method: "GET",
            dataType: 'json',
            success: function (data){
                $.each(data, function(i, item){
                    var geoJson = {
                        type: "FeatureCollection",
                        features: [{
                            type: 'Feature',
                            geometry: { "type": "Point", "coordinates": [this.titik_koordinat_lng, this.titik_koordinat_lat]},
                            properties: {
                                lapanganid: this.lapangan_id,
                                namaLapangan: this.nama_lapangan,
                                alamatLapangan: this.alamat_lapangan,
                                noTelp: this.user.nomor_telepon
                            }
                        }]
                    };

                    L.geoJson(geoJson, {
                        pointToLayer: function (feature, latlng) {
                            feature.properties.myKey = feature.properties.namaLapangan + ', ' + feature.properties.alamatLapangan
                            return L.marker(latlng);
                        },
                        onEachFeature: onEachFeature
                    }).addTo(map);
                });
            }
        });

        function onEachFeature(feature, layer) {
            layer.on('click', function (e) {
                
                if (layer._popup != undefined) {
                    layer.unbindPopup();
                }

                link = "{{route('penyewaLapangan.getLapanganPicture', ':idLapangan')}}";
                link = link.replace(":idLapangan", feature.properties.lapanganid);

                $.ajax({
                    url: link,
                    method: "GET",
                    dataType: 'json',
                    success: function (data){
                        var slideshowContent = '';
                        var i = 0;
                        $.each(data, function(key, item){
                            if(item !== null){
                                slideshowContent += '<div class="image' + (i === 0 ? ' active' : '') + '">' +
                                                '<img src="{!!Storage::url("'+item+'")!!}" />' +

                                                // '<div class="caption">' + img[1] + '</div>' +
                                                // '<div class="caption">' + feature.properties.namaLapangan + '</div>' +
                                                '</div>';
                            }
                            i++;
                        });

                        linkLapangan = '{{route("penyewaLapangan.profilLapangan", [":idLapangan", ":namaLapangan"])}}';
                        linkLapangan = linkLapangan.replace(":idLapangan", feature.properties.lapanganid);
                        linkLapangan = linkLapangan.replace(":namaLapangan", feature.properties.namaLapangan.replace(/\s+/g, '-').toLowerCase());

                        var popupContent =  
                        '<div id="' + feature.properties.namaLapangan + '" class="popup">' +								
                            "<h4>"+feature.properties.namaLapangan+"</h4>"+
                            "<h6>Alamat: " +feature.properties.alamatLapangan+"</h6>"+
                            "<p>No. Telp: "+feature.properties.noTelp+"</p>"+
                            '<div class="slideshow">' +
                                slideshowContent +
                            '</div>' +
                            '<div class="cycle">' +
                                '<a href="javascript:void(0)" class="prev">&laquo; Previous</a>' +
                                '<a href="javascript:void(0)" class="next">Next &raquo;</a>' +
                            '</div>'+
                            '<a href="'+linkLapangan+'"><button type="button" class="btn btn-square btn-outline-blue mt-1">Pesan Lapangan</button></a>'+
                        '</div>';
                        layer.closePopup();
                        layer.bindPopup(popupContent);
                        layer.openPopup();
                    }
                });
            });
        };  

        $('#map').on('click', '.popup .cycle a', function() {
            var $slideshow = $('.slideshow'),
            $newSlide;
            
            if ($(this).hasClass('next')) {
                $newSlide = $slideshow.find('.active').next();
                if ($newSlide.index() < 0) {
                    $newSlide = $('.image').first();
                }
                
            }else{ 
                $newSlide = $slideshow.find('.active').prev();
                if($newSlide.index() < 0) {
                    $newSlide = $('.image').last();
                }
            }
            
            $slideshow.find('.active').removeClass('active').hide();
            $newSlide.addClass('active').show();
        }); 
    });

    
</script>
@endsection