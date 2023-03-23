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
                                        <button class="btn btn-pill btn-outline-primary btn-air-primary filter-status active" id="filter-semua" value="semua" type="button">Semua</button>
                                        <button class="btn btn-pill btn-outline-primary btn-air-primary filter-status" id="filter-diproses" value="diproses" type="button">Diproses</button>
                                        <button class="btn btn-pill btn-outline-primary btn-air-primary filter-status" id="filter-berhasil" value="berhasil" type="button">Berhasil</button>
                                        <!-- <button class="btn btn-pill btn-outline-primary btn-air-primary filter-status" id="filter-berhasil" value="berhasil" type="button">Belum Lunas</button> -->
                                        <button class="btn btn-pill btn-outline-primary btn-air-primary filter-status" id="filter-tidak-berhasil" value="tidak berhasil" type="button">Tidak Berhasil</button>
                                        <p class="reset-filter filter-status" value="reset">Reset Filter</p>
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

<!-- Modal Booking Counting-->
<div class="modal fade" id="modal-booking-counting" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header text-center d-block">
                <h4 class="modal-title ">Invoice</h3>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="card-body">
                <div class="col-sm-12">
                    <div class="card pilih-pembayaran-card" style="border: 0;">
                        <div class="card-header pt-2 pb-2 mb-3" style="-webkit-box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5)">
                            <h5 id='nama-lapangan-invc'></h5>
                            <i class="fa fa-map-marker" style="margin-right: 5px;"></i><p id='alamat-lapangan-invc' style="display: inline-block;"></p>
                        </div>
                        <div class="card-body" style="-webkit-box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5)">
                            <h6 class="mb-0">Jadwal Booking</h6>
                            <hr style="border-top: 1px dashed;"/>
                            <div id="booking-counting">
                            </div>
                        </div>

                        <div class="card-body mt-4" style="-webkit-box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5)">
                            <h6 class="mb-0">Ringkasan Pembayaran</h6>
                            <hr style="border-top: 1px dashed;"/>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card" style="border: 0;margin-bottom: 7px;">
                                        <div class="media">
                                            <div class="media-body">
                                                <p>Jenis Sewa</p>
                                            </div>
                                            <div>
                                                <p id="jenis-sewa">-</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card" style="border: 0;margin-bottom: 7px;">
                                        <div class="media">
                                            <div class="media-body">
                                                <p>Cara Pembayaran</p>
                                            </div>
                                            <div>
                                                <p id="cara-pembayaran">-</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card" style="border: 0;margin-bottom: 7px;">
                                        <div class="media">
                                            <div class="media-body">
                                                <p>Status Pembayaran</p>
                                            </div>
                                            <div>
                                                <p id="status-pembayaran">-</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card" style="border: 0;margin-bottom: 7px;">
                                        <div class="media">
                                            <div class="media-body">
                                                <p>Biaya Sewa</p>
                                            </div>
                                            <div>
                                                <p><span id="biaya-sewa">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card" style="border: 0;margin-bottom: 7px;">
                                        <div class="media">
                                            <div class="media-body">
                                                <p>Total</p>
                                            </div>
                                            <div>
                                                <p><span id="total-biaya-sewa">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card" style="border: 0;margin-bottom: 7px;">
                                        <div class="media">
                                            <div class="media-body">
                                                <p>Potongan Diskon</p>
                                            </div>
                                            <div>
                                                <p><span id="potongan-diskon">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="card" style="border: 0;margin-bottom: 7px;">
                                        <div class="media">
                                            <div class="media-body">
                                                <p>Total Harga Setelah Diskon</p>
                                            </div>
                                            <div>
                                                <p><span id="total-harga-setelah-diskon">-</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-square btn-outline-light txt-dark" data-bs-dismiss="modal">Close</button>
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
    var pembayaranId;

    $( document ).ready(function() {
        var filterDate = $('#filter-tanggal').daterangepicker({
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

        console.log(moment().format("DD-MM-YYYY"));


        if(location.search !== ''){
            var params = new URLSearchParams(window.location.search);
            getInvoice(params.get('pembayaranId'));
            $('#filter-tanggal').data('daterangepicker').setStartDate(params.get('tglPenyewaan')); //date now
            $('#filter-tanggal').data('daterangepicker').setEndDate(params.get('tglPenyewaan'));
            $('#filter-tanggal').click();
            $('.applyBtn').click();

            if (location.href.includes('?')) {
                history.pushState({}, null, location.href.split('?')[0]);
            }
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

    function getInvoice(pembayaranId){
        link = "{{route('penyewaLapangan.getInvoice', ':pembayaranId')}}";
        link = link.replace(":pembayaranId", pembayaranId);

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
    }


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
                var info = { "_token": "{{ csrf_token() }}", "start": api.page.info().start, "length": api.page.info().length, "draw": api.page.info().draw, "filterTanggalStart" : filterDateStart, "filterTanggalEnd" : filterDateEnd, "filterStatusTrx": filterTrx};
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
                { "defaultContent": "", "orderable": false, "width": "10%",
                    render: function (data, type, row) {
                        // if(row.status_pembayaran === "Lunas" || row.status_pembayaran === "DP"){
                            return '<button type="button" class="btn btn-outline-primary" id="view-data-penyewaan-invoice" style="width: 30px; padding-top: 5px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="icofont icofont-ui-note" style="font-size:20px;"></i></button>'
                        // }
                    }
                },
        ],
        order: [[ 0, "asc" ]],
        fixedColumns:{left: 1},
        initComplete:function( settings, json){
            $('#data-riwayat-penyewa_length').appendTo('#length-data-riwayat-penyewa');
            $('#data-riwayat-penyewa_filter').appendTo('#length-data-riwayat-penyewa');
            $('#data-riwayat-penyewa_info').appendTo('#pagination-data-riwayat-penyewa');
            $('#data-riwayat-penyewa_paginate').appendTo('#pagination-data-riwayat-penyewa');
            $('#data-riwayat-penyewa tbody').on('click', "#view-data-penyewaan-invoice", function() {
                let row = $(this).parents('tr')[0];
                console.log(table.row(row).data());
                pembayaranId = table.row(row).data().pembayaran_id;
                getInvoice(pembayaranId)
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

    function bubbleSort(arr) {
        var len = arr.length;

        for (var i = 0; i < len ; i++) {
            for(var j = 0 ; j < len - i - 1; j++){
                if (arr[j] > arr[j + 1]) {
                    // swap
                    var temp = arr[j];
                    arr[j] = arr[j+1];
                    arr[j + 1] = temp;
                }
            }
        }
        return arr;
    }

    function dynamicSort(property) {
        var sortOrder = 1;
        if(property[0] === "-") {
            sortOrder = -1;
            property = property.substr(1);
        }
        return function (a,b) {
            var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
            return result * sortOrder;
        }
    }

    const formatter = new Intl.NumberFormat('id', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    });

    $('body').on('hidden.bs.modal', '#modal-booking-counting', function () {
        $('#booking-counting').children().remove();
    });

    function bookingCounting(orderData){
        var courtStatus= false;
        var bookingArr = {};
        var hargaPerJamArr = {};
        const orderDataSort = Object.keys(orderData).sort().reduce((obj, key) => {
                obj[key] = orderData[key];
                return obj;
            },
            {}
        );

        if(Object.keys(orderDataSort).length !== 0){
            for(let index = 0; index < Object.keys(orderDataSort).length; ++index){
                orderDataSort[Object.keys(orderDataSort)[index]].sort(dynamicSort("court"))
                for(let index2 = 0; index2 < orderDataSort[Object.keys(orderDataSort)[index]].length; ++index2){
                    var orderDataArr = orderDataSort[Object.keys(orderDataSort)[index]][index2];
                    var orderJam = orderDataArr.jam_mulai.substring(0, 5) +' - '+ orderDataArr.jam_selesai.substring(0, 5);
                    var hargaPerJam = orderDataArr.harga_per_jam;
                    var jenisBooking = orderDataArr.jenis_booking;
                    var caraPembayaran = orderDataArr.nama_jenis_pembayaran;
                    var statusPembayaran = orderDataArr.status_pembayaran;
                    var totalBiaya = orderDataArr.total_biaya;
                    var namaLapangan = orderDataArr.nama_lapangan;
                    var alamatLapangan = orderDataArr.alamat_lapangan;
                    var namaPenyewa = orderDataArr.nama_penyewa;
                    var nomorTeleponPenyewa = orderDataArr.nomor_telepon_penyewa;
                    var totalBiayaDiskon = orderDataArr.total_biaya_diskon;
                    var totalDiskonPersen = orderDataArr.total_diskon_persen;
                    var kodeKupon = orderDataArr.kode_kupon;

                    if(index2 === 0 || Object.keys(bookingArr).includes((orderDataArr.nomor_court+'-'+Object.keys(orderDataSort)[index]).toString()) === false){
                        courtStatus = true;
                    }else{
                        courtStatus = false;
                    }

                    if(bookingArr[orderDataArr.nomor_court+'-'+Object.keys(orderDataSort)[index]] === undefined){
                        bookingArr[orderDataArr.nomor_court+'-'+Object.keys(orderDataSort)[index]]= {};
                        bookingArr[orderDataArr.nomor_court+'-'+Object.keys(orderDataSort)[index]]['booking_time']= [];
                        bookingArr[orderDataArr.nomor_court+'-'+Object.keys(orderDataSort)[index]]['harga_per_jam']= [];
                    }

                    bookingArr[orderDataArr.nomor_court+'-'+Object.keys(orderDataSort)[index]]['booking_time'].push(orderJam);
                    bookingArr[orderDataArr.nomor_court+'-'+Object.keys(orderDataSort)[index]]['harga_per_jam'].push(hargaPerJam);
                    bubbleSort(bookingArr[orderDataArr.nomor_court+'-'+Object.keys(orderDataSort)[index]]);

                    // hargaPerJamArr[orderDataArr.nomor_court+'-'+Object.keys(orderDataSort)[index]].push(orderJam);

                    if(courtStatus === true){
                        let dateConvert = new Date(Object.keys(orderDataSort)[index].split('-')[0] + '/' + Object.keys(orderDataSort)[index].split('-')[1] + '/' + Object.keys(orderDataSort)[index].split('-')[2]);
                        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

                        // console.log(orderDataArr.nomor_court)
                        // console.log(dateConvert.toLocaleDateString('id', options))

                        $("#booking-counting").append('\
                            <span style="font-size: 15px;font-weight: bold;">Court '+orderDataArr.nomor_court+'</span>\
                            <p style="margin-top: 10px;">'+dateConvert.toLocaleDateString('id', options)+'</p>\
                            <div id="booking-hour-counting-'+orderDataArr.nomor_court+'-'+Object.keys(orderDataSort)[index]+'" class="row booking-hour-counting">\
                            </div>\
                        ');
                    }
                }
            }

            $.each(bookingArr, function(bookingArrIndex, bookingArrValue) {
                for(let index = 0; index < bookingArrValue.booking_time.length; ++index){
                        console.log(bookingArrValue.booking_time[index]);
                        $('#booking-hour-counting-'+bookingArrIndex).append('\
                        <div class="col-sm-12">\
                            <div class="card" style="border: 0;margin-bottom: 7px;">\
                                <div class="media" style="background-color: azure;border-radius: 5px;border-left: 5px gray solid;padding: 3px 5px 0px 5px;">\
                                    <div class="media-body">\
                                        <p>'+bookingArrValue.booking_time[index]+'</p>\
                                    </div>\
                                    <div>\
                                        <p>'+((jenisBooking === 'per_jam') ? formatter.format(bookingArrValue.harga_per_jam[index]) : 'Harga Sudah Disesuaikan!')+'</p>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                    ');
                }
                $('#booking-hour-counting-'+bookingArrIndex).children().last().append('<hr/>');
            });

            $('#nama-lapangan-invc').empty().append(namaLapangan);
            $('#alamat-lapangan-invc').empty().append(alamatLapangan);
            $('#jenis-sewa').empty().append(((jenisBooking === 'per_jam') ? 'Per Jam' : 'Bulanan'));
            $('#cara-pembayaran').empty().append(caraPembayaran);
            $('#status-pembayaran').empty().append(statusPembayaran);
            $('#biaya-sewa').empty().append(formatter.format(totalBiaya));
            $('#total-biaya-sewa').empty().append(formatter.format(totalBiaya));
            $('#kode-promo').removeAttr('style');
            $('#invalid-promo').remove();
            if(kodeKupon !== ''){
                $('#potongan-diskon').empty().append('('+kodeKupon+') '+ totalDiskonPersen + '%');
                $('#total-biaya-sewa').css('text-decoration', ' line-through');
                $('#total-harga-setelah-diskon').empty().append(formatter.format(totalBiayaDiskon));
            }

            $('#modal-booking-counting').modal('show');
        }
    }
</script>
@endsection
