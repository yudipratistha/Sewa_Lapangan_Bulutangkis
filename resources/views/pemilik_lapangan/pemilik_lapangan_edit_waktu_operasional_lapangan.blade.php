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
                                        <h5 class="pull-left">Edit Waktu Operasional Lapangan</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="update-waktu-operasional" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="validationDefault04">Lapangan Buka Dari Hari</label>
                                                        <div class="input-group"><span class="input-group-text"><i class="icofont icofont-calendar"></i></span>
                                                            <select class="form-select" id="validationDefault04" name="lapangan_buka_dari_hari" required="">
                                                                <option disabled="" value="">Pilih Hari...</option>
                                                                <option value="1" @if($dataWaktuOperasional->buka_dari_hari == 1) selected @endif>Senin</option>
                                                                <option value="2" @if($dataWaktuOperasional->buka_dari_hari == 2) selected @endif>Selasa</option>
                                                                <option value="3" @if($dataWaktuOperasional->buka_dari_hari == 3) selected @endif>Rabu</option>
                                                                <option value="4" @if($dataWaktuOperasional->buka_dari_hari == 4) selected @endif>Kamis</option>
                                                                <option value="5" @if($dataWaktuOperasional->buka_dari_hari == 5) selected @endif>Jumat</option>
                                                                <option value="6" @if($dataWaktuOperasional->buka_dari_hari == 6) selected @endif>Sabtu</option>
                                                                <option value="7" @if($dataWaktuOperasional->buka_dari_hari == 7) selected @endif>Minggu</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="validationDefault04">Lapangan Buka Sampai Hari</label>
                                                        <div class="input-group"><span class="input-group-text"><i class="icofont icofont-calendar"></i></span>
                                                            <select class="form-select" id="validationDefault04" name="lapangan_buka_sampai_hari" required="">
                                                                <option disabled="" value="">Pilih Hari...</option>
                                                                <option value="1" @if($dataWaktuOperasional->buka_sampai_hari == 1) selected @endif>Senin</option>
                                                                <option value="2" @if($dataWaktuOperasional->buka_sampai_hari == 2) selected @endif>Selasa</option>
                                                                <option value="3" @if($dataWaktuOperasional->buka_sampai_hari == 3) selected @endif>Rabu</option>
                                                                <option value="4" @if($dataWaktuOperasional->buka_sampai_hari == 4) selected @endif>Kamis</option>
                                                                <option value="5" @if($dataWaktuOperasional->buka_sampai_hari == 5) selected @endif>Jumat</option>
                                                                <option value="6" @if($dataWaktuOperasional->buka_sampai_hari == 6) selected @endif>Sabtu</option>
                                                                <option value="7" @if($dataWaktuOperasional->buka_sampai_hari == 7) selected @endif>Minggu</option>
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
                                            <div class="form-group float-end">
                                                <button class="btn btn-primary btn-block" type="button" onclick="updateWaktuOperasionalLapangan()">Simpan</button>
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
        step: 60,
        format: 'H:i',
        // minTime: '6',
        // maxTime: '23:30',
        timepickerScrollbar: true,
        scrollTime: true
        // defaultTime: '{{$dataWaktuOperasional->buka_dari_jam}}'
    }).val('{{date("H:i", strtotime($dataWaktuOperasional->buka_dari_jam))}}');

    $('#buka-dari-jam').on('change', function() {
        let timeMin = $("#buka-dari-jam").val();

        $("#buka-sampai-jam").datetimepicker({
            minTime: timeMin,
        });
    });

    $("#buka-sampai-jam").datetimepicker({
        datepicker: false,
        step: 60,
        format: 'H:i',
        minTime: '{{date("H:i", strtotime($dataWaktuOperasional->buka_dari_jam))}}',
        // maxTime: '23:30',
        timepickerScrollbar: true,
        scrollTime: true
        // defaultTime: '{{date("H:i", strtotime($dataWaktuOperasional->buka_sampai_jam))}}'
    }).val('{{date("H:i", strtotime($dataWaktuOperasional->buka_sampai_jam))}}');

    function updateWaktuOperasionalLapangan(){
        swal.fire({
            title: "Perbarui Waktu Operasional Lapangan?",
            text: "Apakah anda ingin perbarui waktu operasional lapangan?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                var form = $("#update-waktu-operasional").get(0)
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.updateWaktuOperasionalLapangan')}}",
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
            swal.fire({title:"Perbauri Waktu Operasional Lapangan Berhasil!", icon:"success"})
            .then(function(){
                window.location.reload();
            });
            }
        })
    }
</script>
@endsection
