@extends('layouts.app')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/datatables.css')}}">
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
                                        <h5 class="pull-left">Manajemen Kupon Diskon Lapangan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="length-data-libur-lapangan" class="dataTables_wrapper"></div>
                                        <div class="table-responsive">
                                            <table class="display datatables" id="data-harga-promo-per-jam">
                                                <thead>
                                                    <tr>
                                                        <th>Kode Kupon</th>
                                                        <th>Diskon Persen</th>
                                                        <th>Kupon Berlaku Dari Tanggal</th>
                                                        <th>Kupon Berlaku Sampai Tanggal</th>
                                                        <th style="min-width: 90px">Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div id="pagination-data-libur-lapangan" class="dataTables_wrapper"></div>
                                    </div>
                                </div>
                            </div>
                            <button class="float btn btn-add btn btn-outline-primary mt-1 mb-1" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modal-add-libur-lapangan" data-bs-original-title="" data-tooltip="tooltip" data-placement="left" title="" data-original-title="">
                                <span class="btn-inner--icon"><i class="icon-plus" style="font-weight: bold;font-size: 20px;"></i></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Libur Lapangan-->
    <div class="modal fade" id="modal-add-libur-lapangan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header text-center d-block">
                    <h4 class="modal-title ">Tambah Kupon Lapangan</h3>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body">
                    <form id="data-harga-promo-perjam" action="" method="POST">
                        @csrf
                        <div class="form-kupon">
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Kode Kupon</label>
                                <div class="input-group kode-kupon">
                                    <input class="form-control" id="kode-kupon" name="kode_kupon" type="text" placeholder="Kode Kupon">
                                    <div class="input-group-text"><i class="fa fa-gift"> </i></div>
                                </div>
                            </div>
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Diskon Persen</label>
                                <div class="input-group date">
                                    <input class="form-control digits" id="diskon-persen" name="diskon_persen" type="text" placeholder="Diskon Persen">
                                    <div class="input-group-text"><i class="fa fa-percent"> </i></div>
                                </div>
                            </div>
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Tanggal Kupon Berlaku Dari & Sampai</label>
                                <div class="input-group date">
                                    <input class="form-control digits" id="tanggal" name="tanggal" type="text" placeholder="dd-mm-yyyy" readonly>
                                    <div class="input-group-text"><i class="fa fa-calendar"> </i></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onClick="addKuponLapangan()" class="btn btn-success">Tambah Data Kupon Lapangan</button>
                    <button type="button" class="btn btn-square btn-outline-light txt-dark" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Libur Lapangan-->
    <div class="modal fade" id="modal-edit-libur-lapangan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header text-center d-block">
                    <h4 class="modal-title ">Edit Libur Lapangan</h3>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body">
                    <form id="data-harga-promo-perjam-edit" action="" method="POST">
                        @csrf
                        <div class="form-kupon">
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Edit Kode Kupon</label>
                                <div class="input-group kode-kupon">
                                    <input class="form-control" id="edit-kode-kupon" name="kode_kupon" type="text" placeholder="Kode Kupon">
                                    <div class="input-group-text"><i class="fa fa-calendar"> </i></div>
                                </div>
                            </div>
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Edit Diskon Persen</label>
                                <div class="input-group date">
                                    <input class="form-control digits" id="edit-diskon-persen" name="diskon_persen" type="text" placeholder="Diskon Persen">
                                    <div class="input-group-text"><i class="fa fa-calendar"> </i></div>
                                </div>
                            </div>
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Edit Tanggal Libur Berlaku Dari & Sampai</label>
                                <div class="input-group date">
                                    <input class="form-control digits" id="edit-tanggal" name="tangggal" type="text" placeholder="dd-mm-yyyy" readonly>
                                    <div class="input-group-text"><i class="fa fa-calendar"> </i></div>
                                </div>
                            </div>
                            <input id="libur-lapangan-id" type="hidden" name="kupon_id" value="" >
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onClick="updateDataHargaPerJam()" class="btn btn-success">Edit Data Libur Lapangan</button>
                    <button type="button" class="btn btn-square btn-outline-light txt-dark" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('plugin_js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{url('/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
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
    var tglKuponDari;
    var tglKuponSampai;

     $('#tanggal').daterangepicker({
        autoUpdateInput: false,
        minDate: moment(),
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "alwaysShowCalendars": true,
        locale: {
            format: 'DD-MM-YYYY',
            cancelLabel: 'Clear'
        }
    });

    $('#tanggal').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        tglKuponDari= picker.startDate.format('DD-MM-YYYY');
        tglKuponSampai= picker.endDate.format('DD-MM-YYYY');
    });

    $('#tanggal').on('cancel.daterangepicker', function(ev, picker) {
        tglKuponDari= null;
        tglKuponSampai= null;

        $(this).val('');
        $(this).data('daterangepicker').setStartDate(moment().format("DD-MM-YYYY")); //date now
        $(this).data('daterangepicker').setEndDate(moment().format("DD-MM-YYYY"));//date
        $(this).data('daterangepicker').show();
    });

    $('#edit-tanggal').daterangepicker({
        autoUpdateInput: false,
        minDate: moment(),
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "alwaysShowCalendars": true,
        locale: {
            format: 'DD-MM-YYYY',
            cancelLabel: 'Clear'
        }
    });

    $('#edit-tanggal').on('apply.daterangepicker', function(ev, picker) {

        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        tglKuponDari= picker.startDate.format('DD-MM-YYYY');
        tglKuponSampai= picker.endDate.format('DD-MM-YYYY');
    });

    $('#edit-tanggal').on('cancel.daterangepicker', function(ev, picker) {
        tglKuponDari= null;
        tglKuponSampai= null;

        $(this).val('');
        $(this).data('daterangepicker').setStartDate(moment().format("DD-MM-YYYY")); //date now
        $(this).data('daterangepicker').setEndDate(moment().format("DD-MM-YYYY"));//date
        $(this).data('daterangepicker').show();
    });

    var tableDataHargaPromoPerJam = $('#data-harga-promo-per-jam').DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        ordering: false,
        // scrollY: true,
        // scrollX: true,
        // paging: true,
        // searching: { "regex": true },
        preDrawCallback: function(settings) {
            api = new $.fn.dataTable.Api(settings);
        },
        ajax: {
            type: "POST",
            url: "{{route('pemilikLapangan.getDataKuponLapangan')}}",
            dataType: "json",
            contentType: 'application/json',
            data: function (data) {
                var form = {};
                $.each($("form").serializeArray(), function (i, field) {
                    form[field.name] = field.value || "";
                });
                // Add options used by Datatables
                var info = { "start": api.page.info().start, "length": api.page.info().length, "draw": api.page.info().draw };
                $.extend(form, info);
                return JSON.stringify(form);
            },
            "complete": function(response) {

            }
        },
        columns: [
            { data: 'kode_kupon' },
            { data: 'total_diskon_persen' },
            { data: 'tgl_berlaku_dari' },
            { data: 'tgl_berlaku_sampai' },
            // { orderable: false,
            //     defaultContent:'',
            //     render: function (data, type, row) {
            //         console.log(row)
            //         if(row.status_delete === 0) return 'Nonaktif';
            //         if(row.status_delete === 1) return 'Aktif';
            //     }
            // },
            { orderable: false, defaultContent: '',
                render: function (data, type, row) {
                    if(row.status_delete === 1) {
                        button = '<button type="button" class="btn btn-outline-primary" id="restore-data-harga-per-jam" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="fa fa-edit" style="font-size:20px;"></i></button>\
                            <button type="button" class="btn btn-outline-danger" id="delete-data-harga-per-jam" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="fa fa-trash" style="font-size:20px;"></i></button>';
                        return button;
                    }
                    if(row.status_delete === 0) {
                        button = '<button type="button" class="btn btn-outline-primary" id="edit-data-kupon" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="icon-pencil-alt" style="font-size:20px;"></i></button>\
                            <button type="button" class="btn btn-outline-danger" id="destroy-data-harga-per-jam" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="fa fa-trash" style="font-size:20px;"></i></button>';
                        return button;
                    }
                }
            },
        ],
        initComplete:function( settings, json){

            $('#data-harga-promo-per-jam tbody').on('click', "#restore-data-harga-per-jam", function() {
                let row = $(this).parents('tr')[0];
                restoreDataHargaPerJam(tableDataHargaPromoPerJam.row(row).data().kupon_id);
            });

            $('#data-harga-promo-per-jam tbody').on('click', "#edit-data-kupon", function() {
                let row = $(this).parents('tr')[0];
                editDataHargaPerJam(tableDataHargaPromoPerJam.row(row).data().kupon_id);
            });

            $('#data-harga-promo-per-jam tbody').on('click', "#delete-data-harga-per-jam", function() {
                let row = $(this).parents('tr')[0];
                deleteDataHargaPromoPerJam(tableDataHargaPromoPerJam.row(row).data().kupon_id);
            });

            $('#data-harga-promo-per-jam tbody').on('click', "#destroy-data-harga-per-jam", function() {
                let row = $(this).parents('tr')[0];
                destroyDataHargaPromoPerJam(tableDataHargaPromoPerJam.row(row).data().kupon_id);
            });
        }
    });

    function addKuponLapangan(){
        swal.fire({
            title: "Tambah Data Kupon?",
            text: "Apakah anda ingin menambah data kupon?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.createKuponLapangan')}}",
                    data: {"_token": "{{ csrf_token() }}",
                        'kode_kupon' : $('#kode-kupon').val(),
                        'diskon_persen': $('#diskon-persen').val(),
                        'tgl_kupon_dari': tglKuponDari,
                        'tgl_kupon_sampai': tglKuponSampai
                    },
                    success: function(data) {
                        var request = 'success';
                    },
                    error: function(xhr, status, error){
                        if(xhr.responseText.search("Call to a member function getRealPath() on null")){
                            $(document).ready(function (){
                                // console.log(xhr.responseJSON.errors)
                                swal.fire({title:"Tambah Data Kupon Gagal!", icon:"error"});
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
            swal.fire({title:"Menambahkan Harga Promo Per Jam Berhasil!", icon:"success"})
            .then(function(){
                window.location.reload();
            });
            }
        })
    }

    function restoreDataHargaPerJam(liburLapanganId){
        swal.fire({
            title: "Memulihkan Data Harga Promo Per Jam?",
            text: "Apakah anda ingin memulihkan data harga promo per jam?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.restoreHargaPromoPerJam')}}",
                    data: {"_token": "{{ csrf_token() }}", "kupon_id" : liburLapanganId},
                    success: function(data) {
                        var request = 'success';
                    },
                    error: function(xhr, status, error){
                        if(xhr.responseText.search("Call to a member function getRealPath() on null")){
                            $(document).ready(function (){
                                // console.log(xhr.responseJSON.errors)
                                swal.fire({title:"Pulihkan Data Harga Promo Per Jam Gagal!", icon:"error"});
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
            swal.fire({title:"Memulihkan Harga Promo Per Jam Berhasil!", icon:"success"})
            .then(function(){
                window.location.reload();
            });
            }
        })
    }

    function destroyDataHargaPromoPerJam(liburLapanganId){
        swal.fire({
            title: "Hapus Permanen Harga Promo Per Jam?",
            text: "Apakah anda ingin menghapus secara permanen harga promo per jam?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.destroyKuponLapangan')}}",
                    data: {"_token": "{{ csrf_token() }}", "kupon_id" : liburLapanganId},
                    success: function(data) {
                        var request = 'success';
                    },
                    error: function(xhr, status, error){
                        if(xhr.responseText.search("Call to a member function getRealPath() on null")){
                            $(document).ready(function (){
                                // console.log(xhr.responseJSON.errors)
                                swal.fire({title:"Hapus Permanen Harga Promo Per Jam Gagal!", icon:"error"});
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
            swal.fire({title:"Hapus Permanen Harga Promo Per Jam Berhasil!", icon:"success"})
            .then(function(){
                window.location.reload();
            });
            }
        })
    }

    function editDataHargaPerJam(kupon_id) {
        $.ajax({
            type: "POST",
            url: "{{route('pemilikLapangan.editKuponLapangan')}}",
            data: {"_token": "{{ csrf_token() }}", "kupon_id" : kupon_id},
            success: function(data) {
                tglKuponDari = data.tgl_berlaku_dari;
                tglKuponSampai = data.tgl_berlaku_sampai;

                $('#edit-kode-kupon').val(data.kode_kupon);
                $('#edit-diskon-persen').val(data.total_diskon_persen);
                $('#edit-tanggal').val(data.tgl_berlaku_dari.split('-').reverse().join('-')+' - '+data.tgl_berlaku_sampai.split('-').reverse().join('-'));
                $("#edit-tanggal").data('daterangepicker').setStartDate(data.tgl_berlaku_dari.split('-').reverse().join('-'));
                $("#edit-tanggal").data('daterangepicker').setEndDate(data.tgl_berlaku_sampai.split('-').reverse().join('-'));
                $('#libur-lapangan-id').val(data.kupon_id);
                $('#modal-edit-libur-lapangan').modal('show');
            },
            error: function(xhr, status, error){
                if(xhr.responseText.search("Call to a member function getRealPath() on null")){
                    $(document).ready(function (){
                        // console.log(xhr.responseJSON.errors)
                        swal.fire({title:"Get Data Harga Promo Per Jam Gagal!", icon:"error"});
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

    function updateDataHargaPerJam(){
        swal.fire({
            title: "Perbarui Data Harga Promo Per Jam",
            text: "Memperbarui data harga promo per jam? ",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                console.log($('#libur-lapangan-id').val())
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.updateKuponLapangan')}}",
                    data: {"_token": "{{ csrf_token() }}", 'kupon_id': $('#libur-lapangan-id').val(), 'tgl_berlaku_dari': tglKuponDari, 'tgl_berlaku_sampai': tglKuponSampai},
                    success: function(data) {
                        var request = 'success';
                        tglKuponDari= null;
                        tglKuponSampai= null;
                    },
                    error: function(xhr, status, error){
                        if(xhr.responseText.search("Call to a member function getRealPath() on null")){
                            $(document).ready(function (){
                                // console.log(xhr.responseJSON)
                                swal.fire({title:"Perbarui Data Harga Promo Per Jam Error!", icon:"error"});
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
            swal.fire({title:"Memperbarui Data Harga Promo Per Jam Berhasil!", icon:"success"})
            .then(function(){
                window.location.reload(true);
            });
            }
        });
    }
</script>
@endsection
