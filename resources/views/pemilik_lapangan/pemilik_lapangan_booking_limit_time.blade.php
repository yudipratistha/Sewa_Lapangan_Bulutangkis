@extends('layouts.app')

@section('plugin_css')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/jquery.timepicker.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/jquery.datetimepicker.min.css')}}">

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
                                        <h5 class="pull-left">Manajemen Booking Limit Time</h5>
                                    </div>
                                    <div class="card-body">
                                        <fieldset>
                                            <form id="data-limit-waktu-booking" action="" method="POST">
                                                @csrf
                                                <div class="col-md-12 payment-method-name-div">
                                                    <label class="form-label" for="">Set Batasan Waktu Booking</label>
                                                    <div class="input-group"><span class="input-group-text"><i class="icofont icofont-clock-time"></i></span>
                                                        <input class="form-control" id="limit-booking" name="limit_booking" type="text" value="{{$dataLimitBookingTime->limit_booking}}" placeholder="Pilih Waktu">
                                                    </div>
                                                </div>
                                                <input type="hidden" name="paket_sewa_bulanan_id" value="{{$dataLimitBookingTime->limit_booking_time_id}}">
                                                <div class="row g-2 mb-2 button-div">
                                                    <div class="col-md-12" style="text-align: right;">
                                                        <button type="button" onclick="updateOrStoreDataPaketSewaBulanan()" style="border-radius: 0.2rem;" class="btn btn-square btn-success btn-sm">Simpan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </fieldset>
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
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script src="{{url('/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{url('/assets/js/time-picker/jquery.timepicker.min.js')}}"></script>
<!-- <script src="{{url('/assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-time-picker/tempusdominus-bootstrap-4.min.js')}}"></script> -->
<script src="{{url('/assets/js/datepicker/date-time-picker/jquery.datetimepicker.full.min.js')}}"></script>

<script>
    $('#limit-booking').timepicker({
        timeFormat: 'HH:mm:ss',
        interval: 1,
        minTime: '0',
        maxTime: '23:59:59',
        defaultTime: "{{$dataLimitBookingTime->limit_booking}}",
        startTime: '00:00:00',
        dynamic: false,
        dropdown: true,
        scrollbar: true
    });

    function updateOrStoreDataPaketSewaBulanan(){
        swal.fire({
            title: "Perbarui Data Limit Waktu Booking?",
            text: "Memperbarui data limit waktu booking? ",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.updateOrCreateLimitBookingTime')}}",
                    data: $('#data-limit-waktu-booking').serialize(),
                    success: function(data) {
                        var request = 'success';
                    },
                    error: function(xhr, status, error){
                        if(xhr.responseText.search("Call to a member function getRealPath() on null")){
                            $(document).ready(function (){
                                // console.log(xhr.responseJSON)
                                swal.fire({title:"Perbarui Data Limit Waktu Booking Error!", icon:"error"});
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
        })
        .then((result) => {
            if(result.value){
            swal.fire({title:"Memperbarui Data Limit Waktu Booking Berhasil!", icon:"success"})
            .then(function(){
                window.location.reload(true);
            });
            }
        });
    }
</script>
@endsection
