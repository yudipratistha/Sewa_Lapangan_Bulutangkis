@extends('layouts.app')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/jquery-ui.css')}}">
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
                                        <h5 class="pull-left">Manajemen Paket Bulanan Normal</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="length-data-courts" class="dataTables_wrapper"></div>
                                        <div class="table-responsive">
                                            <table class="display datatables" id="data-paket-bulanan-normal">
                                                <thead>
                                                    <tr>
                                                        <th>Total Durasi Waktu</th>
                                                        <th>Harga Normal</th>
                                                        <th>Harga Paket Berlaku Mulai Tanggal</th>
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

    <!-- Modal Add Paket Bulanan Normal-->
    <div class="modal fade" id="modal-add-paket-bulanan-normal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header text-center d-block">
                    <h4 class="modal-title ">Tambah Paket Bulanan Normal</h3>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body">
                    <form id="data-paket-sewa-bulanan-normal" action="" method="POST">
                        @csrf
                        <div class="form-payment-method">
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Total Durasi Waktu</label>
                                <input class="form-control payment-method" type="text" name="total_durasi_waktu_jam" placeholder="..." value="" required="">
                            </div>
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Harga Normal</label>
                                <input class="form-control payment-method" type="number" name="harga_normal" placeholder="..." value="" required="">
                            </div>
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Tanggal Mulai Berlaku Dari</label>
                                <div class="input-group date">
                                    <input class="form-control digits" id="tanggal" name="tanggal_mulai_berlaku_dari" type="text" placeholder="dd-mm-yyyy" readonly>
                                    <div class="input-group-text"><i class="fa fa-calendar"> </i></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" onClick="addPaketBulananNormal()" class="btn btn-success">Tambah Data Paket Bulanan Normal</button>
                    <button type="button" class="btn btn-square btn-outline-light txt-dark" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Paket Bulanan Normal-->
    <div class="modal fade" id="modal-edit-paket-bulanan-normal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header text-center d-block">
                    <h4 class="modal-title ">Edit Paket Bulanan Normal</h3>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body">
                    <form id="data-paket-sewa-bulanan-normal-edit" action="" method="POST">
                        @csrf
                        <div class="form-payment-method">
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Edit Total Durasi Waktu</label>
                                <input id="edit-total-durasi-waktu-jam" class="form-control payment-method" type="text" name="edit_total_durasi_waktu_jam" placeholder="..." value="" required="">
                            </div>
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Edit Harga Normal</label>
                                <input id="edit-harga-normal" class="form-control payment-method" type="number" name="edit_harga_normal" placeholder="..." value="" required="">
                            </div>
                            <div class="col-md-12 payment-method-name-div">
                                <label class="form-label" for="">Edit Tanggal Mulai Berlaku Dari</label>
                                <div class="input-group date">
                                    <input class="form-control digits" id="edit-tanggal" name="edit_tanggal_mulai_berlaku_dari" type="text" placeholder="dd-mm-yyyy" value="" readonly>
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
<script src="{{url('/assets/js/datepicker/date-picker-jquery-ui/jquery-ui.js')}}"></script>
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
     $('#tanggal').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        autoclose: true,
        beforeShow: function() {
            setTimeout(function(){
            $('.ui-datepicker').css('z-index', 99999999999999);
            }, 0);
        }
     });

     $('#edit-tanggal').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        autoclose: true,
        beforeShow: function() {
            setTimeout(function(){
            $('.ui-datepicker').css('z-index', 99999999999999);
            }, 0);
        }
     });

    var tableDataPaketBulananNormal = $('#data-paket-bulanan-normal').DataTable({
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
            url: "{{route('pemilikLapangan.getPaketBulananNormal')}}",
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
            { data: 'total_durasi_jam_normal' },
            { data: 'harga_normal' },
            { data: 'tgl_harga_normal_bulanan_berlaku_mulai' },
            { orderable: false,
                defaultContent:'',
                render: function (data, type, row) {
                    console.log(row)
                    if(row.status_delete === 1) return 'Nonaktif';
                    if(row.status_delete === 0) return 'Aktif';
                }
            },
            { orderable: false, defaultContent: '',
                render: function (data, type, row) {
                    if(row.status_delete === 1){
                        button = '<button type="button" class="btn btn-outline-primary" id="restore-data-paket-sewa-bulanan" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="fa fa-edit" style="font-size:20px;"></i></button>\
                            <button type="button" class="btn btn-outline-danger" id="destroy-data-paket-sewa-bulanan" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="fa fa-trash" style="font-size:20px;"></i></button>';
                        return button;
                    }
                    if(row.status_delete === 0){
                        button = '<button type="button" class="btn btn-outline-primary" id="edit-data-paket-sewa-bulanan" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="icon-pencil-alt" style="font-size:20px;"></i></button>\
                            <button type="button" class="btn btn-outline-danger" id="delete-data-paket-sewa-bulanan" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="fa fa-trash" style="font-size:20px;"></i></button>';
                        return button;
                    }
                }
            },
        ],
        initComplete:function( settings, json){

            $('#data-paket-bulanan-normal tbody').on('click', "#restore-data-paket-sewa-bulanan", function() {
                let row = $(this).parents('tr')[0];
                restoreDataPaketSewaBulanan(tableDataPaketBulananNormal.row(row).data().paket_sewa_bulanan_id);
            });

            $('#data-paket-bulanan-normal tbody').on('click', "#edit-data-paket-sewa-bulanan", function() {
                let row = $(this).parents('tr')[0];
                editDataPaketSewaBulanan(tableDataPaketBulananNormal.row(row).data().paket_sewa_bulanan_id);
            });

            $('#data-paket-bulanan-normal tbody').on('click', "#delete-data-paket-sewa-bulanan", function() {
                let row = $(this).parents('tr')[0];
                deleteDataPaketSewaBulanan(tableDataPaketBulananNormal.row(row).data().paket_sewa_bulanan_id);
            });

            $('#data-paket-bulanan-normal tbody').on('click', "#destroy-data-paket-sewa-bulanan", function() {
                let row = $(this).parents('tr')[0];
                destroyDataPaketSewaBulanan(tableDataPaketBulananNormal.row(row).data().paket_sewa_bulanan_id);
            });
        }
    });

    function addPaketBulananNormal(){
        swal.fire({
            title: "Tambah Paket Bulanan Normal?",
            text: "Apakah anda ingin menambah Paket Bulanan Normal?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.createPaketBulananNormal')}}",
                    data: $('#data-paket-sewa-bulanan-normal').serialize(),
                    success: function(data) {
                        var request = 'success';
                    },
                    error: function(xhr, status, error){
                        if(xhr.responseText.search("Call to a member function getRealPath() on null")){
                            $(document).ready(function (){
                                // console.log(xhr.responseJSON.errors)
                                swal.fire({title:"Tambah Data Paket Sewa Bulanan Normal Gagal!", icon:"error"});
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
            swal.fire({title:"Menambahkan Paket Bulanan Normal Berhasil!", icon:"success"})
            .then(function(){
                window.location.reload();
            });
            }
        })
    }

    function restoreDataPaketSewaBulanan(paketSewaBulananId){
        swal.fire({
            title: "Memulihkan Data Paket Bulanan Normal?",
            text: "Apakah anda ingin memulihkan data paket bulanan normal?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.restorePaketBulananNormal')}}",
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
            swal.fire({title:"Memulihkan Paket Bulanan Normal Berhasil!", icon:"success"})
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
                    url: "{{route('pemilikLapangan.deletePaketBulananNormal')}}",
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
                    url: "{{route('pemilikLapangan.destroyPaketBulananNormal')}}",
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
            url: "{{route('pemilikLapangan.editPaketBulananNormal')}}",
            data: {"_token": "{{ csrf_token() }}", "paket_sewa_bulanan_id" : paketSewaBulananId},
            success: function(data) {
                $('#edit-total-durasi-waktu-jam').val(data.total_durasi_jam_normal);
                $('#edit-harga-normal').val(data.harga_normal);
                $('#edit-tanggal').val(data.tgl_harga_normal_bulanan_berlaku_mulai.split('-').reverse().join('-'));
                $('#paket-sewa-bulanan-id').val(data.paket_sewa_bulanan_id);
                $('#modal-edit-paket-bulanan-normal').modal('show');
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
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.updatePaketBulananNormal')}}",
                    data: $('#data-paket-sewa-bulanan-normal-edit').serialize(),
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
