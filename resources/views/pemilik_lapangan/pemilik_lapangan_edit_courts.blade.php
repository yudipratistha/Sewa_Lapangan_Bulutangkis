@extends('layouts.app')

@section('plugin_css')
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
                                        <h5 class="pull-left">Edit Courts Pemilik Lapangan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div id="length-data-courts" class="dataTables_wrapper"></div>
                                        <div class="table-responsive">
                                            <table class="display datatables" id="data-courts">
                                                <thead>
                                                    <tr>
                                                        <th>Nomor Court</th>
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
                            <button class="float btn btn-add btn btn-outline-primary mt-1 mb-1" onClick="addCourt()" data-tooltip="tooltip" data-placement="left" title="" data-original-title="">
                                <span class="btn-inner--icon"><i class="icon-plus" style="font-weight: bold;font-size: 20px;"></i></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('plugin_js')
<script src="{{url('/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{url('/assets/js/time-picker/jquery.timepicker.min.js')}}"></script>
<!-- <script src="{{url('/assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-time-picker/tempusdominus-bootstrap-4.min.js')}}"></script> -->
<script src="{{url('/assets/js/datepicker/date-time-picker/jquery.datetimepicker.full.min.js')}}"></script>
<script src="{{url('/assets/js/leaflet/leaflet.js')}}"></script>
<script src="{{url('/assets/js/leaflet/leaflet-gesture-handling.min.js')}}"></script>
<script>

var tableDataCourts = $('#data-courts').DataTable({
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
            url: "{{route('pemilikLapangan.getDataCourts')}}",
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
            { data: 'nomor_court' },
            { orderable: false,
                defaultContent:'',
                render: function (data, type, row) {
                    if(row.status_court === 0) return 'Nonaktif';
                    if(row.status_court === 1) return 'Aktif';
                }
            },
            { orderable: false, defaultContent: '',
                render: function (data, type, row) {
                    if(row.status_court === 0) return '<button type="button" class="btn btn-outline-primary" id="restore-data-courts" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="fa fa-edit" style="font-size:20px;"></i></button>';
                    if(row.status_court === 1) return '<button type="button" class="btn btn-outline-danger" id="delete-data-courts" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="fa fa-trash" style="font-size:20px;"></i></button>';
                }
            },
        ],
        initComplete:function( settings, json){
            // $("div.dataTables_length").append('&nbsp<span onclick="approveTicket()" class="btn btn-pill btn-outline-secondary btn-air-secondary btn-sm">Approve Ticket</span>');
            // $('#data-courts_length').appendTo('#length-data-ssp-rula');
            // $('#data-courts_info').appendTo('#pagination-data-ssp-rula');
            // $('#data-courts_paginate').appendTo('#pagination-data-ssp-rula');
            // $('#data-courts tbody').on('click', "#restore-data-court", function() {
            //     let row = $(this).parents('tr')[0];
            //     console.log(tableDataCourts.row(row).data());

            //     $('#edit-body-data-ssp-rula').append('<input type="hidden" id="ticket-id" name="ticket_id" value="'+tableDataCourts.row(row).data().ssp_ticket_id+'">\
            //         <input type="hidden" id="time-id" name="time_id" value="'+tableDataCourts.row(row).data().ssp_time_id+'">');

            //     Object.keys(tableDataCourts.row(row).data()).forEach(function(item, index) {
            //         console.log(item)
            //         if(index >= 7){

            //             $('#edit-body-data-ssp-rula').append('\
            //                 <div class="form-group row" id="job-analyst-div">\
            //                     <label class="col-xl-3 col-sm-4 col-form-label">'+ucwords(item.replace('ssp_rula_','').replace(/_/g, " "))+'</label>\
            //                     <div class="col-xl-9 col-sm-8">\
            //                         <input type="text" class="form-control" id="'+item.replace(/_/g, "-")+'" name="'+item.replace('ssp_rula_','')+'" placeholder="'+ucwords(item.replace('ssp_rula_','').replace(/_/g, " "))+'..." value="'+tableDataSspRula.row(row).data()[item]+'">\
            //                     </div>\
            //                 </div>');
            //         }
            //     })
            //     $('#modal-edit-data-ssp-rula').modal('show');
            // });

            $('#data-courts tbody').on('click', "#restore-data-courts", function() {
                let row = $(this).parents('tr')[0];
                restoreDataCourt(tableDataCourts.row(row).data().court_id, tableDataCourts.row(row).data().nomor_court);
            });

            $('#data-courts tbody').on('click', "#delete-data-courts", function() {
                let row = $(this).parents('tr')[0];
                deleteDataCourt(tableDataCourts.row(row).data().court_id, tableDataCourts.row(row).data().nomor_court);
            });
        }
    });

    function addCourt(){
        swal.fire({
            title: "Tambah Court?",
            text: "Apakah anda ingin menambah court?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.addCourt')}}",
                    data: {"_token": "{{ csrf_token() }}"},
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
            swal.fire({title:"Perbauri Profil Lapangan Berhasil!", icon:"success"})
            .then(function(){
                window.location.reload();
            });
            }
        })
    }

    function restoreDataCourt(courtId, nomor_court){
        swal.fire({
            title: "Memulihkan Court "+nomor_court+"?",
            text: "Apakah anda ingin memulihkan status court "+nomor_court+"?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.restoreCourt')}}",
                    data: {"_token": "{{ csrf_token() }}", "court_id" : courtId},
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
            swal.fire({title:"Perbauri Profil Lapangan Berhasil!", icon:"success"})
            .then(function(){
                window.location.reload();
            });
            }
        })
    }

    function deleteDataCourt(courtId, nomor_court){
        swal.fire({
            title: "Nonaktifkan Court "+nomor_court+"?",
            text: "Apakah anda ingin nonakftifkan court "+nomor_court+"?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Simpan",
            showLoaderOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('pemilikLapangan.deleteCourt')}}",
                    data: {"_token": "{{ csrf_token() }}", "court_id" : courtId},
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
            swal.fire({title:"Perbauri Profil Lapangan Berhasil!", icon:"success"})
            .then(function(){
                window.location.reload();
            });
            }
        })
    }
</script>
@endsection
