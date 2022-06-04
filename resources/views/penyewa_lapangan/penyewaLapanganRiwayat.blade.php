@extends('layouts.app')

@section('title', 'Riwayat Penyewaan')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/datatables.css')}}">
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
                        <div class="card-header">
                            <h5 class="pull-left">Riwayat Penyewaan</h5>
                        </div>
                        <div class="card-body">                    
                            <div class="table-responsive">
                                <table class="display datatables" id="data-riwayat-penyewa">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Tanggal Penyewaan</th>
                                            <th>Nama Lapangan</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

<script>
    // $("#data-riwayat-penyewa").dataTable({
    //     "columns": [
    //         { "orderable": true, "width": "7%" },
    //         null,
    //         { "orderable": true, "width": "16%" },
    //         { "orderable": true, "width": "13%" },
    //         { "orderable": false, "width": "10%" }
    //     ]
    // });

    $('#tanggal').datepicker({
        language: 'en',
        dateFormat: 'dd-mm-yyyy',
        minDate: new Date() // Now can select only dates, which goes after today
    });

    table = $('#data-riwayat-penyewa').DataTable({
        bFilter: true,
        processing: true,
        serverSide: true,
        // scrollY: true,
        // scrollX: true,
        // paging: true,
        // searching: { "regex": true },
        preDrawCallback: function(settings) {
            api = new $.fn.dataTable.Api(settings);
        },
        ajax: {
            type: "POST",
            url: "{{route('penyewaLapangan.getDataRiwayatPenyewaLapangan')}}",
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
        "columns": [
                { "defaultContent": "", "orderable": true, "width": "7%", render: function (data, type, row, meta){ return meta.row + meta.settings._iDisplayStart + 1; } },
                { "data": "tgl_booking", "orderable": true, "width": "14%" },
                { "data": "nama_lapangan" },
                { "defaultContent": "", "orderable": true, "width": "13%" },
                { "defaultContent": "", "orderable": false, "width": "10%" }
        ],
        order: [[ 0, "asc" ]],
        fixedColumns:{left: 1},
        initComplete:function( settings, json){
            // $("div.dataTables_length").append('&nbsp<span onclick="approveTicket()" class="btn btn-pill btn-outline-secondary btn-air-secondary btn-sm">Approve Ticket</span>');
            // $('#data-ergonomic_length').appendTo('#length-data-ergonomic');
            // $('#data-ergonomic_info').appendTo('#pagination-data-ergonomic');
            // $('#data-ergonomic_paginate').appendTo('#pagination-data-ergonomic');
            // $('#data-ergonomic tbody').on('click', "#edit-data-ergonomic", function() {
            //     let row = $(this).parents('tr')[0];
            //     console.log(table.row(row).data().ssp_time_id);
                
            //     $('#edit-body-data-ergonomic').append('<input type="hidden" id="ticket-id" name="ticket_id" value="'+table.row(row).data().ssp_ticket_id+'">\
            //         <input type="hidden" id="time-id" name="time_id" value="'+table.row(row).data().ssp_time_id+'">');

            //     Object.keys(table.row(row).data()).forEach(function(item, index) {
            //         if(index >= 6){
            //             $('#edit-body-data-ergonomic').append('\
            //                 <div class="form-group row" id="job-analyst-div">\
            //                     <label class="col-xl-3 col-sm-4 col-form-label">'+ucwords(item.replace(/_/g, " "))+'</label>\
            //                     <div class="col-xl-9 col-sm-8">\
            //                         <input type="text" class="form-control" id="'+item.replace(/_/g, "-")+'" name="'+item+'" placeholder="'+ucwords(item.replace(/_/g, " "))+'..." value="'+table.row(row).data()[item]+'">\
            //                     </div>\
            //                 </div>');
            //         }
            //     })
            //     $('#editDataErgonomic').modal('show');
            // });

            // $('#data-ergonomic tbody').on('click', "#delete-data-ergonomic", function() {
            //     let row = $(this).parents('tr')[0];
            //     deleteDataErgonomic(table.row(row).data().ssp_time_id);
            // });
        }
    });
    table.on('order.dt search.dt', function () {
        let i = 1;
 
        table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            this.data(i++);
        });
    }).draw();

</script>
@endsection