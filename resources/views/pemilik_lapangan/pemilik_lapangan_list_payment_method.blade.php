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
                                        <h5 class="pull-left">Daftar Metode Pembayaran</h5>
                                    </div>
                                    <div class="card-body">
                                        <fieldset>
                                            <form id="data-payment-method" action="" method="POST">
                                                @csrf
                                                @for ($counter= 0; $counter < count($dataDaftarJenisPembayaranLapangan); $counter++)
                                                    @if($counter <= 0)
                                                        <div class="row g-2 mb-2 form-payment-method">
                                                            <div class="col-md-4 payment-method-name-div">
                                                                <label class="form-label" for="">Nama Metode Pembayaran 1</label>
                                                                <input class="form-control payment-method" type="text" name="nama_metode_pembayaran[]" placeholder="..." value="{{$dataDaftarJenisPembayaranLapangan[$counter]->nama_jenis_pembayaran}}" required="" @if($dataDaftarJenisPembayaranLapangan[$counter]->status_delete === 1) disabled @endif>
                                                            </div>
                                                            <div class="col-md-4 atas-nama-div">
                                                                <label class="form-label">Atas Nama 1</label>
                                                                <input class="form-control" name="atas_nama[]" type="text" placeholder="..." value="{{$dataDaftarJenisPembayaranLapangan[$counter]->atas_nama}}" required="" @if($dataDaftarJenisPembayaranLapangan[$counter]->status_delete === 1) disabled @endif>
                                                            </div>
                                                            <div class="col-md-4 no-rek-virtual-account">
                                                                <label class="form-label">Nomor Rekening / Virtual Account / E-Wallet 1</label>
                                                                <input class="form-control" name="no_rek_virtual_account[]" type="number" placeholder="..." value="{{$dataDaftarJenisPembayaranLapangan[$counter]->no_rekening}}" required="" @if($dataDaftarJenisPembayaranLapangan[$counter]->status_delete === 1) disabled @endif>
                                                            </div>
                                                            <input type="hidden" name="jenis_pembayaran_metode_id[]" value="{{$dataDaftarJenisPembayaranLapangan[$counter]->daftar_jenis_pembayaran_id}}"  @if($dataDaftarJenisPembayaranLapangan[$counter]->status_delete === 1) disabled @endif>
                                                        </div>
                                                    @else
                                                        <div class="row g-2 mb-2 form-payment-method">
                                                            <div class="col-md-4 payment-method-name-div">
                                                                <label class="form-label payment-method-name-label" for="">Nama Metode Pembayaran {{$counter+1}}</label>
                                                                <input class="form-control payment-method" type="text" name="nama_metode_pembayaran[]" value="{{$dataDaftarJenisPembayaranLapangan[$counter]->nama_jenis_pembayaran}}" placeholder="..." required="" @if($dataDaftarJenisPembayaranLapangan[$counter]->status_delete === 1) disabled @endif>
                                                            </div>
                                                            <div class="col-md-4 atas-nama-div">
                                                                <label class="form-label atas-nama-label" for="">Atas Nama {{$counter+1}}</label>
                                                                <input class="form-control" type="text" name="atas_nama[]" placeholder="..." value="{{$dataDaftarJenisPembayaranLapangan[$counter]->atas_nama}}" required="" @if($dataDaftarJenisPembayaranLapangan[$counter]->status_delete === 1) disabled @endif>
                                                            </div>
                                                            <div class="col-md-4 mt-2 px-0 ps-1 no-rek-virtual-account-div">
                                                                <label class="form-label no-rek-virtual-account-label">Nomor Rekening / Virtual Account / E-Wallet {{$counter+1}}</label>
                                                                <div class="row">
                                                                    <div class="col-md-11 pe-3">
                                                                        <input class="form-control no-rek-virtual-account" name="no_rek_virtual_account[]" type="number" value="{{$dataDaftarJenisPembayaranLapangan[$counter]->no_rekening}}" placeholder="..." required="" @if($dataDaftarJenisPembayaranLapangan[$counter]->status_delete === 1) disabled @endif>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        @if($dataDaftarJenisPembayaranLapangan[$counter]->status_delete === 1)
                                                                            <button type="button" onclick="restoreDataPaymentMethod({{$dataDaftarJenisPembayaranLapangan[$counter]->daftar_jenis_pembayaran_id}})" class="btn btn-outline-success pull-right" style="width: 37px; padding-top: 5px; padding-left: 0px; padding-right: 0px; padding-bottom: 4px;"><i class="fa fa-power-off" style="font-size:20px;"></i></button>
                                                                        @else
                                                                            <button type="button" onclick="destroyDataPaymentMethod({{$dataDaftarJenisPembayaranLapangan[$counter]->daftar_jenis_pembayaran_id}})" class="btn btn-outline-danger pull-right" style="width: 37px; padding-top: 5px; padding-left: 0px; padding-right: 0px; padding-bottom: 4px;"><i class="fa fa-trash" style="font-size:20px;"></i></button>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="jenis_pembayaran_metode_id[]" value="{{$dataDaftarJenisPembayaranLapangan[$counter]->daftar_jenis_pembayaran_id}}  @if($dataDaftarJenisPembayaranLapangan[$counter]->status_delete === 1) disabled @endif">
                                                        </div>
                                                    @endif
                                                @endfor
                                                <div class="row g-2 mb-2 button-div">
                                                    <div class="col-md-12" style="text-align: right;">
                                                        <button type="button" id="add-payment-method" class="btn btn-outline-info btn-sm">Tambah Metode Pembayaran</button>
                                                        <button type="button" onclick="storeDataPaymentMethod()" style="border-radius: 0.2rem;" class="btn btn-square btn-success btn-sm">Simpan</button>
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
    $(document).ready(function() {
        $('#add-payment-method').on("click", function(e){
            e.stopPropagation();
            var totalPaymentMethod = $('.payment-method').length+1;
            console.log($('.payment-method'))
            $(this).parent().parent().parent().find('.form-payment-method').last().after('\
            <div class="row g-2 mb-2 form-payment-method">\
                <div class="col-md-4 payment-method-name-div">\
                    <label class="form-label payment-method-name-label" for="">Nama Metode Pembayaran '+totalPaymentMethod+'</label>\
                    <input class="form-control payment-method" type="text" name="nama_metode_pembayaran[]" placeholder="..." required="">\
                </div>\
                <div class="col-md-4 atas-nama-div">\
                    <label class="form-label atas-nama-label" for="">Atas Nama '+totalPaymentMethod+'</label>\
                    <input class="form-control" type="text" name="atas_nama[]" placeholder="..." required="">\
                </div>\
                <div class="col-md-4 mt-2 px-0 ps-1 no-rek-virtual-account-div">\
                    <label class="form-label no-rek-virtual-account-label">Nomor Rekening / Virtual Account / E-Wallet '+totalPaymentMethod+'</label>\
                    <div class="row">\
                        <div class="col-md-11 pe-3">\
                            <input class="form-control no-rek-virtual-account" name="no_rek_virtual_account[]" type="number" value="" placeholder="..." required="">\
                        </div>\
                        <div class="col-md-1">\
                            <button type="button" class="btn btn-outline-danger pull-right delete-row-payment-method" style="width: 37px; padding-top: 5px; padding-left: 0px; padding-right: 0px; padding-bottom: 4px;" data-bs-original-title="" title=""><i class="fa fa-trash" style="font-size:20px;"></i></button>\                    </div>\
                        </div>\
                    </div>\
                </div>\
                <input type="hidden" name="jenis_pembayaran_metode_id[]" value="">\
            </div>\
            ')
        });

        $('#data-payment-method').on("click", ".delete-row-payment-method", function(e){
            e.stopPropagation();
            var prevElementLength = $(this).parent().parent().parent().parent().prevUntil('.payment-method-name-div').length;
            var nextElementLength = $(this).parent().parent().parent().parent().nextUntil('.button-div').length;
            var nextElement = $(this).parent().parent().parent().parent().nextUntil('.button-div');

            for(let totalPaymentMethod= 0; totalPaymentMethod< nextElementLength; totalPaymentMethod++){
                var totalPaymentMethodNext = totalPaymentMethod + prevElementLength;

                $(nextElement[totalPaymentMethod]).find('.payment-method-name-label').text('Nama Metode Pembayaran '+ totalPaymentMethodNext)
                $(nextElement[totalPaymentMethod]).find('.atas-nama-label').text('Atas Nama '+ totalPaymentMethodNext)
                $(nextElement[totalPaymentMethod]).find('.no-rek-virtual-account-label').text('Nomor Rekening / Virtual Account / E-Wallet '+ totalPaymentMethodNext)
            }

            $(this).parent().parent().parent().parent().remove();
        });
    });

    function storeDataPaymentMethod(){
        swal.fire({
            title: "Tambah Data Metode Pembayaran",
            text: "Tambahkan data metode pembayaran? ",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                var formData = new FormData($("#data-payment-method").get(0));
                formData.append('_token', '{{ csrf_token() }}');
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.updatePaymentMethodPemilikLapangan')}}",
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
                                swal.fire({title:"Tambah Data Metode Pembayaran Error!", icon:"error"});
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
            swal.fire({title:"Data Metode Pembayaran Berhasil Ditambahkan!", icon:"success"})
            .then(function(){
                window.location.reload(true);
            });
            }
        })
    }

    function restoreDataPaymentMethod(dataPaymentMethodId){
        swal.fire({
            title: "Pulihkan Data Metode Pembayaran?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Pulihkan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.restorePaymentMethodPemilikLapangan')}}",
                    data: {'_token': '{{ csrf_token() }}', 'data_payment_method_id': dataPaymentMethodId},
                    success: function(data) {
                        var request = 'success';
                    },
                    error: function(xhr, status, error){
                        if(xhr.responseText.search("Call to a member function getRealPath() on null")){
                            $(document).ready(function (){
                                // console.log(xhr.responseJSON)
                                swal.fire({title:"Pulihkan Data Metode Pembayaran Error!", icon:"error"});
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
            swal.fire({title:"Data Metode Pembayaran Berhasil Dipulihkan!", icon:"success"})
            .then(function(){
                window.location.reload(true);
            });
            }
        })
    }

    function destroyDataPaymentMethod(dataPaymentMethodId){
        swal.fire({
            title: "Hapus Data Metode Pembayaran?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Hapus",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.destroyPaymentMethodPemilikLapangan')}}",
                    data: {'_token': '{{ csrf_token() }}', 'data_payment_method_id': dataPaymentMethodId},
                    success: function(data) {
                        var request = 'success';
                    },
                    error: function(xhr, status, error){
                        if(xhr.responseText.search("Call to a member function getRealPath() on null")){
                            $(document).ready(function (){
                                // console.log(xhr.responseJSON)
                                swal.fire({title:"Hapus Data Metode Pembayaran Error!", icon:"error"});
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
            swal.fire({title:"Data Metode Pembayaran Berhasil Dihapus!", icon:"success"})
            .then(function(){
                window.location.reload(true);
            });
            }
        })
    }
</script>
@endsection
