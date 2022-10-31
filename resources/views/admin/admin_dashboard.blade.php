@extends('layouts.app')

@section('title', 'Dashboard Pendaftaran Lapangan Baru')

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
                        <h3>Dashboard Pendaftaran Lapangan Baru</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="" data-bs-original-title="" title="">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard Pendaftaran Lapangan Baru</li>
                        </ol>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display datatables" id="data-new-registration-field">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Lapangan</th>
                                            <th>Nama Pemilik</th>
                                            <th>Status Verifikasi</th>
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

            $('#data-new-registration-field').DataTable().ajax.reload();
        }else{
            $('.btn-showcase').find('.active').removeClass('active');
            $(this).addClass('active');
            filterTrx = $(this).val();
            console.log(filterTrx)
            $('#data-new-registration-field').DataTable().ajax.reload();
        }
    });


    table = $('#data-new-registration-field').DataTable({
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
            url: "{{ route('administrator.getDaftarLapanganBaru') }}",
            dataType: "json",
            contentType: 'application/json',
            data: function (data) {
                var form = {};
                // Add options used by Datatables
                var info = { "_token": "{{ csrf_token() }}", "start": api.page.info().start, "length": api.page.info().length, "draw": api.page.info().draw, "filterTanggalStart" : filterDateStart, "filterTanggalEnd" : filterDateEnd, "filterStatusTrx": filterTrx};
                $.extend(form, info);
                return JSON.stringify(form);
            },
            "complete": function(response) {

            }
        },
        "columns": [
                { "defaultContent": "", "orderable": true, "width": "7%", render: function (data, type, row, meta){ return meta.row + meta.settings._iDisplayStart + 1; } },
                { "data": "nama_lapangan", "orderable": true},
                { "data": "nama_pemilik_lapangan" },
                { "data": "status_verifikasi", "orderable": true, "width": "13%",
                    render: function (data, type, row) {
                        if(row.status_verifikasi === "belum diverifikasi"){
                            return 'Belum Diverifikasi'
                        }else if(row.status_verifikasi === "ditolak"){
                            return 'Ditolak'
                        }else if(row.status_verifikasi === "diverifikasi"){
                            return 'Sudah Diverifikasi'
                        }
                    }
                },
                { "defaultContent": "", "orderable": false, "width": "10%",
                    render: function (data, type, row) {
                        return '<button type="button" class="btn btn-outline-primary" id="view-data-new-registration-field" style="width: 30px; padding-top: 5px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="icofont icofont-ui-note" style="font-size:20px;"></i></button>'
                    }
                },
        ],
        order: [[ 0, "asc" ]],
        fixedColumns:{left: 1},
        initComplete:function( settings, json){
            $('#data-new-registration-field_length').appendTo('#length-data-new-registration-field');
            $('#data-new-registration-field_filter').appendTo('#length-data-new-registration-field');
            $('#data-new-registration-field_info').appendTo('#pagination-data-new-registration-field');
            $('#data-new-registration-field_paginate').appendTo('#pagination-data-new-registration-field');
            $('#data-new-registration-field tbody').on('click', "#view-data-new-registration-field", function() {
                let row = $(this).parents('tr')[0];
                console.log(table.row(row).data());

                linkLapangan = '{{route("administrator.viewProfilLapangan", [":idLapangan", ":namaLapangan"])}}';
                linkLapangan = linkLapangan.replace(":idLapangan", table.row(row).data().lapangan_id);
                linkLapangan = linkLapangan.replace(":namaLapangan", table.row(row).data().nama_lapangan.replace(/\s+/g, '-').toLowerCase());

                Swal.fire({
                    title: 'Lihat Profil Lapangan?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Lihat',
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        window.location.href = linkLapangan;
                    }
                });
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
        // $('#data-new-registration-field').DataTable().destroy();
        $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
        filterDateStart= picker.startDate.format('DD-MM-YYYY');
        filterDateEnd= picker.endDate.format('DD-MM-YYYY');

        $('#data-new-registration-field').DataTable().ajax.reload();
    });

    $('#filter-tanggal').on('cancel.daterangepicker', function(ev, picker) {
        filterDateStart= null;
        filterDateEnd= null;

        $(this).val('');
        $(this).data('daterangepicker').setStartDate(moment().format("DD-MM-YYYY")); //date now
        $(this).data('daterangepicker').setEndDate(moment().format("DD-MM-YYYY"));//date
        $(this).data('daterangepicker').show();
        $('#data-new-registration-field').DataTable().ajax.reload();
    });

</script>
@endsection
