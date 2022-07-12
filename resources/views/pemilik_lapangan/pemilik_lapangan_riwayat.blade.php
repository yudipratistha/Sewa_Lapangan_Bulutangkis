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
                                <div class="mb-3 row g-3">
                                    <label class="col-xl-1 col-sm-3 col-lg-1 col-form-label">Status</label>
                                    <div class="col-xl-11 col-sm-9 col-lg-11 filter-group">
                                        <div class="btn-showcase">
                                            <button class="btn btn-pill btn-outline-primary btn-air-primary filter-status active" id="filter-semua" value="semua" type="button">Semua</button>
                                            <button class="btn btn-pill btn-outline-primary btn-air-primary filter-status" id="filter-diproses" value="diproses" type="button">Diproses</button>
                                            <button class="btn btn-pill btn-outline-primary btn-air-primary filter-status" id="filter-berhasil" value="berhasil" type="button">Berhasil</button>
                                            <!-- <button class="btn btn-pill btn-outline-primary btn-air-primary filter-status" id="filter-berhasil" value="berhasil" type="button">Belum Lunas</button> -->
                                            <button class="btn btn-pill btn-outline-primary btn-air-primary filter-status" id="filter-tidak-berhasil" value="tidak berhasil" type="button">Tidak Berhasil</button>
                                            <p class="reset-filter filter-status" value="reset">Reset Filter</p>
                                        </div>
                                    </div>
                                </div>     
                            </div>    
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                        <div id="length-data-riwayat-penyewa" class="dataTables_wrapper"></div>
                            <div class="table-responsive">
                                <table class="display datatables" id="data-riwayat-penyewa">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Penyewa</th>
                                            <th>Tanggal Penyewaan</th>
                                            <th>Status Pembayaran</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="pagination-data-riwayat-penyewa" class="dataTables_wrapper"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Container-fluid Ends-->
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
    var filterTrx;

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

    $(".filter-status").click(function() {
        if($(this).hasClass('reset-filter')){
            filterDateStart= null;
            filterDateEnd= null;
            filterTrx = null;
            
            $('#filter-tanggal').val('');
            $('#filter-tanggal').data('daterangepicker').setStartDate(moment().format("DD-MM-YYYY")); //date now
            $('#filter-tanggal').data('daterangepicker').setEndDate(moment().format("DD-MM-YYYY"));//date
            
            $('.btn-showcase').find('.active').removeClass('active');
            $("#filter-semua").addClass('active');

            $('#data-riwayat-penyewa').DataTable().ajax.reload();
        }else{
            $('.btn-showcase').find('.active').removeClass('active');
            $(this).addClass('active');
            filterTrx = $(this).val();
            console.log(filterTrx)
            $('#data-riwayat-penyewa').DataTable().ajax.reload();
        }
    });

    var table = $('#data-riwayat-penyewa').DataTable({
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
            url: "{{route('pemilikLapangan.getDataRiwayatPenyewaanPemilikLapangan')}}",
            dataType: "json",
            contentType: 'application/json',
            data: function (data) {
                var form = {};
                // Add options used by Datatables
                var info = { "_token": "{{ csrf_token() }}", "start": api.page.info().start, "length": api.page.info().length, "draw": api.page.info().draw, "filterTanggalStart" : filterDateStart, "filterTanggalEnd" : filterDateEnd, "filterStatusTrx": filterTrx };
                $.extend(form, info);
                return JSON.stringify(form);
            },
        },
        "columns": [
            { "defaultContent": "", "orderable": true, "width": "7%", render: function (data, type, row, meta){ return meta.row + meta.settings._iDisplayStart + 1; } },
            { "data": "name", "orderable": true},
            { "data": "tgl_booking", "orderable": true, "width": "16%" },
            { "data": "status_pembayaran", "orderable": true, "width": "14%" },
            { "orderable": false, "width": "10%", "defaultContent": '\
                <button type="button" class="btn btn-outline-primary" id="view-data-penyewaan" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="fa fa-edit" style="font-size:20px;"></i></button>',
                // render: function (data, type, row) { console.log(row) }
            },
        ],
        order: [[ 0, "asc" ]],
        initComplete:function( settings, json){
            $('#data-riwayat-penyewa_length').appendTo('#length-data-riwayat-penyewa');
            $('#data-riwayat-penyewa_filter').appendTo('#length-data-riwayat-penyewa');
            $('#data-riwayat-penyewa_info').appendTo('#pagination-data-riwayat-penyewa');
            $('#data-riwayat-penyewa_paginate').appendTo('#pagination-data-riwayat-penyewa');
            $('#data-riwayat-penyewa tbody').on('click', "#view-data-penyewaan", function() {
                let row = $(this).parents('tr')[0];
                console.log(table.row(row).data().id_pembayaran);
            //     $.ajax({
            //         url: "",
            //         method: "POST",
            //         data: {
            //             "_token": "{{ csrf_token() }}",
            //             "tanggal" : date
            //         },
            //         dataType: "json",
            //         success:function(data){
            //             for(let courtCount= 1; courtCount<= jumlah_court; courtCount++){
            //                 $('#table-court-'+courtCount).DataTable().clear().draw();
            //                 $('#table-court-'+courtCount).DataTable().rows.add(data['court_'+courtCount]);
            //                 $('#table-court-'+courtCount).DataTable().columns.adjust().draw();
            //             }
            //         },
            //         error: function(xhr, ajaxOptions, thrownError){
            //             console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            //         }
            //     })
            //     $('#view-data-penyewa').modal('show');
            });
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