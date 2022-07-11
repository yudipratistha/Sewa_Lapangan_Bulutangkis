@extends('layouts.app')

@section('title', 'Riwayat Penyewaan')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                    <div class="col-sm-6">
                        <h3>Riwayat Penyewaan</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="" data-bs-original-title="" title="">Home</a></li>
                            <li class="breadcrumb-item active">Riwayat Penyewaan</li>
                        </ol>
                    </div>
                    <div class="card" style="margin-bottom: 10px;">
                        <div class="card-body">
                            <div class="mb-3 row g-3">
                                <label class="col-xl-1 col-sm-3 col-lg-1 col-form-label">Pilih Tanggal</label>
                                <div class="col-xl-3 col-sm-5 col-lg-7">
                                    <div class="input-group date">
                                        <input class="form-control digits" id="filter-tanggal" name="filterTanggal" type="text" autocomplete="off">
                                        <div class="input-group-text"><i class="fa fa-calendar"> </i></div>
                                    </div>
                                </div>
                            </div>      
                            <div class="mb-3 row g-3">
                                <label class="col-xl-1 col-sm-3 col-lg-1 col-form-label">Status</label>
                                <div class="col-xl-11 col-sm-9 col-lg-11 filter-group">
                                    <div class="btn-showcase">
                                        <button class="btn btn-pill btn-outline-primary btn-air-primary active" id="filter-semua" type="button">Semua</button>
                                        <button class="btn btn-pill btn-outline-primary btn-air-primary" id="filter-diproses" type="button">Diproses</button>
                                        <button class="btn btn-pill btn-outline-primary btn-air-primary" id="filter-berhasil" type="button">Berhasil</button>
                                        <button class="btn btn-pill btn-outline-primary btn-air-primary" id="filter-tidak-berhasil" type="button">Tidak Berhasil</button>
                                        <p class="reset-filter">Reset Filter</p>
                                    </div>
                                </div>
                            </div>  
                            <div class="row g-3">  
                                <button class="btn btn-square btn-outline-waiting-payment waiting-payment txt-dark" type="button" onclick='location.href="{{route('penyewaLapangan.menungguPembayaranPenyewaIndex')}}"' data-bs-original-title="" title="" style="text-align: left;padding-left: 0px;border-radius: 8px;">
                                    <i class="icofont icofont-time" style="margin: 0 8px 0 12px; font-size: 15px; color: #24695c; font-weight: bold;"></i> 
                                    <span>Menunggu Pembayaran</span>
                                    <!-- <i class="icofont icofont-double-right" style="text-align: right; margin: 0 8px 0 12px;"></i>  -->
                                </button>      
                            </div>     
                        </div>
                    </div>
                    <div class="card">
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{url('/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>

<script>
    var filterDateStart; 
    var filterDateEnd;

    $('#filter-tanggal').daterangepicker({
        autoUpdateInput: false,
        // maxDate: moment(), 
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
                // Add options used by Datatables
                var info = { "_token": "{{ csrf_token() }}", "start": api.page.info().start, "length": api.page.info().length, "draw": api.page.info().draw, "filterTanggalStart" : filterDateStart, "filterTanggalEnd" : filterDateEnd };
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
                { "data": "status_pembayaran", "orderable": true, "width": "13%" },
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

    $('#filter-tanggal').on('apply.daterangepicker', function(ev, picker) {
        // $('#data-riwayat-penyewa').DataTable().destroy();
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        filterDateStart= picker.startDate.format('DD-MM-YYYY');
        filterDateEnd= picker.endDate.format('DD-MM-YYYY');
        
        $('#data-riwayat-penyewa').DataTable().ajax.reload();
    });

    $('#filter-tanggal').on('cancel.daterangepicker', function(ev, picker) {
        filterDateStart= null;
        filterDateEnd= null;
        
        $(this).val('');
        $(this).data('daterangepicker').setStartDate(moment().format("DD-MM-YYYY")); //date now
        $(this).data('daterangepicker').setEndDate(moment().format("DD-MM-YYYY"));//date
        $(this).data('daterangepicker').show();
        $('#data-riwayat-penyewa').DataTable().ajax.reload();
    });

</script>
@endsection