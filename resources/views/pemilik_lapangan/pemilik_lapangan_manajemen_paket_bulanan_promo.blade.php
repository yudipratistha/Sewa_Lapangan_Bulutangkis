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
                                        <h5 class="pull-left">Manajemen Paket Bulanan Promo</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="length-data-courts" class="dataTables_wrapper"></div>
                                        <div class="table-responsive">
                                            <table class="display datatables" id="data-paket-bulanan-promo">
                                                <thead>
                                                    <tr>
                                                        <th>Total Durasi Waktu</th>
                                                        <th>Harga Promo</th>
                                                        <th>Harga Paket Berlaku Dari Tanggal</th>
                                                        <th>Harga Paket Berlaku Sampai Tanggal</th>
                                                        <th>Status</th>
                                                        <th style="min-width: 90px">Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div id="pagination-data-courts" class="dataTables_wrapper"></div>
                                    </div>
                                </div>
                            </div>
                            <button class="float btn btn-add btn btn-outline-primary mt-1 mb-1" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modal-add-paket-bulanan-normal" data-bs-original-title="" data-tooltip="tooltip" data-placement="left" title="" data-original-title="">
                                <span class="btn-inner--icon"><i class="icon-plus" style="font-weight: bold;font-size: 20px;"></i></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Paket Bulanan Promo-->
    <div class="modal fade" id="modal-add-paket-bulanan-normal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header text-center d-block">
                    <h4 class="modal-title ">Tambah Paket Bulanan Promo</h3>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body">
                    <form id="data-paket-sewa-bulanan-promo" action="" method="POST">
                        @csrf
                        <div class="form-payment-method">
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Total Durasi Waktu</label>
                                <input class="form-control payment-method" type="text" name="total_durasi_waktu_jam" placeholder="..." value="" required="">
                            </div>
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Harga Normal</label>
                                <input class="form-control payment-method" type="number" name="harga_promo" placeholder="..." value="" required="">
                            </div>
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Tanggal Promo Berlaku Dari & Sampai</label>
                                <div class="input-group date">
                                    <input class="form-control digits" id="tanggal" name="tanggal" type="text" placeholder="dd-mm-yyyy" readonly>
                                    <div class="input-group-text"><i class="fa fa-calendar"> </i></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onClick="addPaketBulananPromo()" class="btn btn-success">Tambah Data Paket Bulanan Normal</button>
                    <button type="button" class="btn btn-square btn-outline-light txt-dark" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Paket Bulanan Promo-->
    <div class="modal fade" id="modal-edit-paket-bulanan-promo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header text-center d-block">
                    <h4 class="modal-title ">Edit Paket Bulanan Promo</h3>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body">
                    <form id="data-paket-sewa-bulanan-promo-edit" action="" method="POST">
                        @csrf
                        <div class="form-payment-method">
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Edit Total Durasi Waktu</label>
                                <input id="edit-total-durasi-waktu-jam" class="form-control payment-method" type="text" name="edit_total_durasi_waktu_jam" placeholder="..." value="" required="">
                            </div>
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Edit Harga Promo</label>
                                <input id="edit-harga-promo" class="form-control payment-method" type="number" name="edit_harga_promo" placeholder="..." value="" required="">
                            </div>
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Edit Tanggal Promo Berlaku Dari & Sampai</label>
                                <div class="input-group date">
                                    <input class="form-control digits" id="edit-tanggal" name="tangggal" type="text" placeholder="dd-mm-yyyy" readonly>
                                    <div class="input-group-text"><i class="fa fa-calendar"> </i></div>
                                </div>
                            </div>
                            <input id="paket-sewa-bulanan-id" type="hidden" name="paket_sewa_bulanan_id" value="" >
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onClick="updateDataPaketSewaBulanan()" class="btn btn-success">Edit Data Paket Bulanan Normal</button>
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
    var tglPromoPaketBulananBerlakuDari;
    var tglPromoPaketBulananBerlakuSampai;

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
        tglPromoPaketBulananBerlakuDari= picker.startDate.format('DD-MM-YYYY');
        tglPromoPaketBulananBerlakuSampai= picker.endDate.format('DD-MM-YYYY');
    });

    $('#tanggal').on('cancel.daterangepicker', function(ev, picker) {
        tglPromoPaketBulananBerlakuDari= null;
        tglPromoPaketBulananBerlakuSampai= null;

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
        tglPromoPaketBulananBerlakuDari= picker.startDate.format('DD-MM-YYYY');
        tglPromoPaketBulananBerlakuSampai= picker.endDate.format('DD-MM-YYYY');
    });

    $('#edit-tanggal').on('cancel.daterangepicker', function(ev, picker) {
        tglPromoPaketBulananBerlakuDari= null;
        tglPromoPaketBulananBerlakuSampai= null;

        $(this).val('');
        $(this).data('daterangepicker').setStartDate(moment().format("DD-MM-YYYY")); //date now
        $(this).data('daterangepicker').setEndDate(moment().format("DD-MM-YYYY"));//date
        $(this).data('daterangepicker').show();
    });

    var tableDataPaketBulananPromo = $('#data-paket-bulanan-promo').DataTable({
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
            url: "{{route('pemilikLapangan.getPaketBulananPromo')}}",
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
            { data: 'total_durasi_jam_promo' },
            { data: 'harga_promo' },
            { data: 'tgl_promo_paket_bulanan_berlaku_dari' },
            { data: 'tgl_promo_paket_bulanan_berlaku_sampai' },
            { orderable: false,
                defaultContent:'',
                render: function (data, type, row) {
                    if(row.status_delete === 0) return 'Nonaktif';
                    if(row.status_delete === 1) return 'Aktif';
                }
            },
            { orderable: false, defaultContent: '',
                render: function (data, type, row) {
                    if(row.status_delete === 0){
                        button = '<button type="button" class="btn btn-outline-primary" id="restore-data-paket-sewa-bulanan" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="fa fa-edit" style="font-size:20px;"></i></button>\
                            <button type="button" class="btn btn-outline-danger" id="destroy-data-paket-sewa-bulanan" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="fa fa-trash" style="font-size:20px;"></i></button>';
                        return button;
                    }
                    if(row.status_delete === 1){
                        button = '<button type="button" class="btn btn-outline-primary" id="edit-data-paket-sewa-bulanan" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="icon-pencil-alt" style="font-size:20px;"></i></button>\
                            <button type="button" class="btn btn-outline-danger" id="delete-data-paket-sewa-bulanan" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="fa fa-trash" style="font-size:20px;"></i></button>';
                        return button;
                    }
                }
            },
        ],
        initComplete:function( settings, json){

            $('#data-paket-bulanan-promo tbody').on('click', "#restore-data-paket-sewa-bulanan", function() {
                let row = $(this).parents('tr')[0];
                restoreDataPaketSewaBulanan(tableDataPaketBulananPromo.row(row).data().paket_sewa_bulanan_id);
            });

            $('#data-paket-bulanan-promo tbody').on('click', "#edit-data-paket-sewa-bulanan", function() {
                let row = $(this).parents('tr')[0];
                editDataPaketSewaBulanan(tableDataPaketBulananPromo.row(row).data().paket_sewa_bulanan_id);
            });

            $('#data-paket-bulanan-promo tbody').on('click', "#delete-data-paket-sewa-bulanan", function() {
                let row = $(this).parents('tr')[0];
                deleteDataPaketSewaBulanan(tableDataPaketBulananPromo.row(row).data().paket_sewa_bulanan_id);
            });

            $('#data-paket-bulanan-promo tbody').on('click', "#destroy-data-paket-sewa-bulanan", function() {
                let row = $(this).parents('tr')[0];
                destroyDataPaketSewaBulanan(tableDataPaketBulananPromo.row(row).data().paket_sewa_bulanan_id);
            });
        }
    });

    function addPaketBulananPromo(){
        swal.fire({
            title: "Tambah Paket Bulanan Promo?",
            text: "Apakah anda ingin menambah Paket Bulanan Promo?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                var formData = $('#data-paket-sewa-bulanan-promo').serialize() + '&tgl_promo_paket_bulanan_berlaku_dari='+tglPromoPaketBulananBerlakuDari+'&tgl_promo_paket_bulanan_berlaku_sampai='+tglPromoPaketBulananBerlakuSampai;

                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.createPaketBulananPromo')}}",
                    data: formData,
                    success: function(data) {
                        var request = 'success';
                    },
                    error: function(xhr, status, error){
                        if(xhr.responseText.search("Call to a member function getRealPath() on null")){
                            $(document).ready(function (){
                                // console.log(xhr.responseJSON.errors)
                                swal.fire({title:"Tambah Data Paket Sewa Bulanan Promo Gagal!", icon:"error"});
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
            swal.fire({title:"Menambahkan Paket Bulanan Promo Berhasil!", icon:"success"})
            .then(function(){
                window.location.reload();
            });
            }
        })
    }

    function restoreDataPaketSewaBulanan(paketSewaBulananId){
        swal.fire({
            title: "Memulihkan Data Paket Bulanan Promo?",
            text: "Apakah anda ingin memulihkan data paket bulanan promo?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.restorePaketBulananPromo')}}",
                    data: {"_token": "{{ csrf_token() }}", "paket_sewa_bulanan_id" : paketSewaBulananId},
                    success: function(data) {
                        var request = 'success';
                    },
                    error: function(xhr, status, error){
                        if(xhr.responseText.search("Call to a member function getRealPath() on null")){
                            $(document).ready(function (){
                                // console.log(xhr.responseJSON.errors)
                                swal.fire({title:"Pulihkan Data Paket Bulanan Gagal!", icon:"error"});
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
            swal.fire({title:"Memulihkan Paket Bulanan Promo Berhasil!", icon:"success"})
            .then(function(){
                window.location.reload();
            });
            }
        })
    }

    function deleteDataPaketSewaBulanan(paketSewaBulananId){
        swal.fire({
            title: "Hapus Paket Sewa Bulanan?",
            text: "Apakah anda ingin menghapus paket bulanan?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.deletePaketBulananPromo')}}",
                    data: {"_token": "{{ csrf_token() }}", "paket_sewa_bulanan_id" : paketSewaBulananId},
                    success: function(data) {
                        var request = 'success';
                    },
                    error: function(xhr, status, error){
                        if(xhr.responseText.search("Call to a member function getRealPath() on null")){
                            $(document).ready(function (){
                                // console.log(xhr.responseJSON.errors)
                                swal.fire({title:"Delete Paket Sewa Bulanan Gagal!", icon:"error"});
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
            swal.fire({title:"Hapus Paket Bulanan Berhasil!", icon:"success"})
            .then(function(){
                window.location.reload();
            });
            }
        })
    }

    function destroyDataPaketSewaBulanan(paketSewaBulananId){
        swal.fire({
            title: "Hapus Permanen Paket Sewa Bulanan?",
            text: "Apakah anda ingin menghapus secara permanen paket bulanan?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.destroyPaketBulananPromo')}}",
                    data: {"_token": "{{ csrf_token() }}", "paket_sewa_bulanan_id" : paketSewaBulananId},
                    success: function(data) {
                        var request = 'success';
                    },
                    error: function(xhr, status, error){
                        if(xhr.responseText.search("Call to a member function getRealPath() on null")){
                            $(document).ready(function (){
                                // console.log(xhr.responseJSON.errors)
                                swal.fire({title:"Hapus Permanen Paket Bulanan Gagal!", icon:"error"});
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
            swal.fire({title:"Hapus Permanen Paket Bulanan Berhasil!", icon:"success"})
            .then(function(){
                window.location.reload();
            });
            }
        })
    }

    function editDataPaketSewaBulanan(paketSewaBulananId){
        $.ajax({
            type: "POST",
            url: "{{route('pemilikLapangan.editPaketBulananPromo')}}",
            data: {"_token": "{{ csrf_token() }}", "paket_sewa_bulanan_id" : paketSewaBulananId},
            success: function(data) {
                console.log(data)
                $('#edit-total-durasi-waktu-jam').val(data.total_durasi_jam_promo);
                $('#edit-harga-promo').val(data.harga_promo);
                $('#edit-tanggal').val(data.tgl_promo_paket_bulanan_berlaku_dari.split('-').reverse().join('-')+' - '+data.tgl_promo_paket_bulanan_berlaku_sampai.split('-').reverse().join('-'));
                $("#edit-tanggal").data('daterangepicker').setStartDate(data.tgl_promo_paket_bulanan_berlaku_dari.split('-').reverse().join('-'));
                $("#edit-tanggal").data('daterangepicker').setEndDate(data.tgl_promo_paket_bulanan_berlaku_sampai.split('-').reverse().join('-'));
                $('#paket-sewa-bulanan-id').val(data.paket_sewa_bulanan_id);
                $('#modal-edit-paket-bulanan-promo').modal('show');
            },
            error: function(xhr, status, error){
                if(xhr.responseText.search("Call to a member function getRealPath() on null")){
                    $(document).ready(function (){
                        // console.log(xhr.responseJSON.errors)
                        swal.fire({title:"Get Data Paket Sewa Bulanan Gagal!", icon:"error"});
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

    function updateDataPaketSewaBulanan(){
        swal.fire({
            title: "Perbarui Data Paket Sewa Bulanan",
            text: "Memperbarui data paket sewa bulanan? ",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                var formData = $('#data-paket-sewa-bulanan-promo-edit').serialize() + '&edit_tgl_promo_paket_bulanan_berlaku_dari='+tglPromoPaketBulananBerlakuDari+'&edit_tgl_promo_paket_bulanan_berlaku_sampai='+tglPromoPaketBulananBerlakuSampai;

                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.updatePaketBulananPromo')}}",
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
