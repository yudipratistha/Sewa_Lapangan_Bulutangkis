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
                                        <h5 class="pull-left">Manajemen Paket Bulanan</h5>
                                    </div>
                                    <div class="card-body">
                                        <fieldset>
                                            <form id="data-paket-sewa-bulanan" action="" method="POST">
                                                @csrf
                                                <div class="row g-2 mb-2 form-payment-method">
                                                    <div class="col-md-6 payment-method-name-div">
                                                        <label class="form-label" for="">Total Durasi Waktu</label>
                                                        <input class="form-control payment-method" type="text" name="total_durasi_waktu_jam" placeholder="..." value="{{$dataPaketSewaBulanan[0]->total_durasi_jam}}" required="">                                                        
                                                    </div>
                                                    <div class="col-md-6 payment-method-name-div">
                                                        <label class="form-label" for="">Harga</label>
                                                        <input class="form-control payment-method" type="number" name="total_harga" placeholder="..." value="{{$dataPaketSewaBulanan[0]->total_harga}}" required="">                                                        
                                                    </div>
                                                </div>
                                                <input type="hidden" name="paket_sewa_bulanan_id" value="{{$dataPaketSewaBulanan[0]->paket_sewa_bulanan_id}}">
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
<script src="{{url('/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{url('/assets/js/time-picker/jquery.timepicker.min.js')}}"></script>
<!-- <script src="{{url('/assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-time-picker/tempusdominus-bootstrap-4.min.js')}}"></script> -->
<script src="{{url('/assets/js/datepicker/date-time-picker/jquery.datetimepicker.full.min.js')}}"></script>
<script src="{{url('/assets/js/leaflet/leaflet.js')}}"></script>
<script src="{{url('/assets/js/leaflet/leaflet-gesture-handling.min.js')}}"></script>

<script>
    function updateOrStoreDataPaketSewaBulanan(){
        swal.fire({
            title: "Perbarui Data Paket Sewa Bulanan",
            text: "Memperbarui data paket sewa bulanan? ",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {  
                var formData = new FormData($("#data-paket-sewa-bulanan").get(0));
                formData.append('_token', '{{ csrf_token() }}');
                return $.ajax({
                    type: "POST", 
                    url: "{{route('pemilikLapangan.updateOrCreatePaketBulananPemilik')}}",
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData, 
                    success: function(data) {
                        var request = 'success';
                    },
                    error: function(xhr, status, error){
                        if(xhr.responseText.search("Call to a member function getRealPath() on null")){
                            $(document).ready(function (){
                                // console.log(xhr.responseJSON)
                                swal.fire({title:"Perbarui Data Paket Sewa Bulanan Error!", icon:"error"});
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
            swal.fire({title:"Memperbarui Data Paket Bulanan Berhasil!", icon:"success"})
            .then(function(){ 
                window.location.reload(true);
            });
            }
        });
    }
</script>
@endsection
