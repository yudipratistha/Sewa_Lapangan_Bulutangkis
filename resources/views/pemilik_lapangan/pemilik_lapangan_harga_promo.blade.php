@extends('layouts.app')

@section('title', 'Manajemen Harga Promo')

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
                        <h3>Manajemen Harga Promo</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="" data-bs-original-title="" title="">Home</a></li>
                            <li class="breadcrumb-item active">Manajemen Harga Promo</li>
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
                                            <th>Harga</th>
                                            <th>Tanggal Berlaku</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div id="pagination-data-riwayat-penyewa" class="dataTables_wrapper"></div>
                        </div>
                    </div>
                    <button class="float btn btn-add btn btn-outline-primary mt-1 mb-1" data-toggle="modal" data-target="#modal-add-data-harga-promo" data-tooltip="tooltip" data-placement="left" title="" data-original-title="">
                        <span class="btn-inner--icon"><i class="icon-plus" style="font-weight: bold;font-size: 20px;"></i></span>
                    </button>
                </div>
            </div>
            <!-- Container-fluid Ends-->
        </div>
        <!-- footer start-->
        @include('layouts.footer')
    </div>
</div>

<!-- Modal Add Data Harga Promo-->
<div class="modal fade" id="modal-add-data-harga-promo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header text-center d-block">
                <h4 class="modal-title ">Tambah Data Harga Promo</h3>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="card-body">
                <form id="form-add-data-harga-promo">
                    @csrf
                    <div class="mb-3">
                        <label for="harga-promo" class="form-label">Harga Promo</label>
                        <input name="harga_promo" type="number" class="form-control @error('new_password') is-invalid @enderror" id="harga-promo" placeholder="Harga Promo" required="">
                    </div>
                    <div class="mb-3">
                        <label for="tanggal-promo-berlaku-dari" class="form-label">Tanggal Promo Berlaku Dari</label>
                        <input name="tanggal_harga_promo_berlaku_dari" type="password" class="form-control" id="tanggal-promo-berlaku-dari" placeholder="Tanggal Promo Berlaku Dari" required="">
                    </div>
                    <div class="mb-3">
                        <label for="tanggal-promo-berlaku-sampai" class="form-label">Tanggal Promo Berlaku Sampai</label>
                        <input name="tanggal_harga_promo_berlaku_sampai" type="password" class="form-control" id="tanggal-promo-berlaku-sampai" placeholder="Tanggal Promo Berlaku Sampai" required="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" onClick="addPromoData()" class="btn btn-success">Tambah Data Harga Promo</button>
                <button type="button" class="btn btn-square btn-outline-light txt-dark" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
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

    $('body').on('hidden.bs.modal', '.modal', function () {
        $('#data-profil-penyewa-modal').find('.modal-footer').children('button').slice(-2).remove();

        if($('#data-profil-penyewa-modal').find('#update-status-pembayaran').val() !== null){
            $('#data-profil-penyewa-modal').find('#update-status-pembayaran').val('')
        }
    });

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
            url: "",
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
            { "data": "tanggal_pembayaran", "orderable": true, "width": "18%",
                render: function (data, type, row) {
                    if(data !== null){
                        return data;
                    }else{
                        return '-';
                    }
                 }
            },
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
                console.log(table.row(row).data().id_pengguna);

                link = "";
                // link = link.replace(":penggunaPenyewaId", table.row(row).data().id_pengguna);
                // link = link.replace(":date", table.row(row).data().tgl_booking);
                // link = link.replace(":pembayaranId", table.row(row).data().id_pembayaran);
                $.ajax({
                    url: link,
                    method: "GET",
                    dataType: 'json',
                    success: function(data){
                        bookingCounting(data)
                    },
                    error: function(data){
                        console.log("asdsad", data)
                    }
                });
                // $('#view-data-penyewa').modal('show');
            });
        }
    });
    table.on('order.dt search.dt', function () {
        let i = 1;

        table.cells(null, 0, { search: 'applied', order: 'applied' }).every(function (cell) {
            this.data(i++);
        });
    }).draw();

    const formatter = new Intl.NumberFormat('id', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    });

    $('body').on('hidden.bs.modal', '#data-profil-penyewa-modal', function () {
        $('#booking-counting').children().remove();
    });

    
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
