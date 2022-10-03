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
                                        <h5 class="pull-left">Tambah Metode Pembayaran</h5>
                                    </div>
                                    <div class="card-body">
                                        <fieldset>
                                            <form class="f1" id="data-payment-method" action="" method="POST">
                                                @csrf
                                                <div class="row g-2 mb-2 form-payment-method">
                                                    <div class="col-md-4 payment-method-name-div">
                                                        <label class="form-label" for="">Nama Metode Pembayaran 1</label>
                                                        <input class="form-control payment-method" type="text" name="nama_metode_pembayaran[]" placeholder="..." required="">                                                        
                                                    </div>
                                                    <div class="col-md-4 atas-nama-div">
                                                        <label class="form-label">Atas Nama 1</label>
                                                        <input class="form-control" name="atas_nama[]" type="text" value="" placeholder="..." required="">
                                                    </div>
                                                    <div class="col-md-4 no-rek-virtual-account">
                                                        <label class="form-label">Nomor Rekening / Virtual Account / E-Wallet 1</label>
                                                        <input class="form-control" name="no_rek_virtual_account[]" type="number" value="" placeholder="..." required="">
                                                    </div>
                                                </div>
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
            </div>\
            ')
        });

        $('#data-payment-method').on("click", ".delete-row-payment-method", function(e){
            e.stopPropagation();
            var prevElementLength = $(this).parent().parent().parent().parent().prevUntil('.form-group').length;
            var nextElementLength = $(this).parent().parent().parent().parent().nextUntil('.button-div').length;
            var nextElement = $(this).parent().parent().parent().parent().nextUntil('.button-div');

            for(let totalPaymentMethod= 0; totalPaymentMethod< nextElementLength; totalPaymentMethod++){
                var totalPaymentMethodNext = totalPaymentMethod + prevElementLength;
                
                $(nextElement[totalPaymentMethod]).find('.payment-method-name-label').text('Nama Metode Pembayaran '+ totalPaymentMethod)
                $(nextElement[totalPaymentMethod]).find('.atas-nama-label').text('Atas Nama '+ totalPaymentMethod)
                $(nextElement[totalPaymentMethod]).find('.no-rek-virtual-account-label').text('Nomor Rekening / Virtual Account / E-Wallet '+ totalPaymentMethod)
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
                    url: "",
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
                                    // if(i === 'nama_periode'){
                                    //     $("#error-msg-nama-periode").remove();
                                    //     $("#nama-periode").addClass("is-invalid");
                                    //     $('#nama-periode-div').append('<div id="error-msg-nama-periode" class="text-danger">Input nama periode tidak boleh kosong!</div>');
                                    // }
                                    // if(i === 'excelFile'){
                                    //     $("#error-msg-excel-file").remove();
                                    //     $("#excel-file").addClass("is-invalid");
                                    //     $('#excel-file-div').append('<div id="error-msg-excel-file" class="text-danger">Input file excel tidak boleh kosong!</div>');
                                    // }
                                    if(i.substr(0, i.indexOf(".")) === 'nama_metode_pembayaran'){
                                        var elementHadiahNameDiv = $('.hadiah-name-div')[i.substr(i.indexOf(".") + 1)];
                                        var indexElementHadiah = Number(i.substr(i.indexOf(".") + 1)) + 1;

                                        $(elementHadiahNameDiv).find(".error-msg-hadiah-name").remove();
                                        $(elementHadiahNameDiv).find('input').addClass("is-invalid");
                                        $(elementHadiahNameDiv).append('<div class="text-danger error-msg-hadiah-name">Input hadiah '+indexElementHadiah+' tidak boleh kosong!</div>');
                                        
                                    }
                                    if(i.substr(0, i.indexOf(".")) === 'atas_nama'){
                                        var elementHadiahQtyDiv = $('.hadiah-qty-div')[i.substr(i.indexOf(".") + 1)];
                                        var indexElementQtyHadiah = Number(i.substr(i.indexOf(".") + 1)) + 1;

                                        $(elementHadiahQtyDiv).find(".error-msg-hadiah-qty").remove();
                                        $(elementHadiahQtyDiv).find('input').addClass("is-invalid");
                                        $(elementHadiahQtyDiv).append('<div class="text-danger error-msg-hadiah-qty">Input qty hadiah '+indexElementQtyHadiah+' tidak boleh kosong!</div>');
                                    }
                                    if(i.substr(0, i.indexOf(".")) === 'no_rek_virtual_account'){
                                        var elementHadiahQtyDiv = $('.hadiah-qty-div')[i.substr(i.indexOf(".") + 1)];
                                        var indexElementQtyHadiah = Number(i.substr(i.indexOf(".") + 1)) + 1;

                                        $(elementHadiahQtyDiv).find(".error-msg-hadiah-qty").remove();
                                        $(elementHadiahQtyDiv).find('input').addClass("is-invalid");
                                        $(elementHadiahQtyDiv).append('<div class="text-danger error-msg-hadiah-qty">Input qty hadiah '+indexElementQtyHadiah+' tidak boleh kosong!</div>');
                                    }
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
</script>
@endsection
