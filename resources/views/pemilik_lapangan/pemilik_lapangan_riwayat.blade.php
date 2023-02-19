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
                                            <th>Tanggal Pembayaran</th>
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

<!-- Modal Data Profil Penyewa-->
<div class="modal fade" id="data-profil-penyewa-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Data Profil Penyewa</h3>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card pilih-pembayaran-card" style="border: 0;">
                            <div class="card-header pb-2 mb-3" style="-webkit-box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5)">
                                <h6 class="mb-0">Data Penyewa</h6>
                                <hr style="border-top: 1px dashed;"/>
                                <div class="form-group">
                                    <label>Nama Penyewa</label>
                                    <div class="input-group"><span class="input-group-text"><i class="icon-user"></i></span>
                                        <input type="text" disabled class="form-control" id="nama-penyewa" name="nama_penyewa" placeholder="Nama..." value="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Nomor Telepon Penyewa</label>
                                    <div class="input-group"><span class="input-group-text"><i data-feather="phone" style="width: 17px;"></i></span>
                                        <input type="text" disabled class="form-control" id="nomor-telepon-penyewa" name="nomor_telepon_penyewa" placeholder="08x..." value="">
                                    </div>
                                </div>
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
                                </div>
                            </div>
                            <div class="card-body mt-4" style="-webkit-box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5)">
                                <h6 class="mb-0">Foto Bukti Pembayaran:</h6>
                                <div class="gallery my-gallery card-body p-0 mt-3" itemscope="">
                                    <figure class="col-md-12" itemprop="associatedMedia" itemscope="">
                                        <a id="foto-bukti-pembayaran-full" href="" itemprop="contentUrl" data-size="1600x950">
                                            <img id="foto-bukti-pembayaran-thumbnail" class="img-thumbnail" src="" itemprop="thumbnail" alt="Image description">
                                        </a>
                                    </figure>
                                </div>
                            </div>
                            <div class="card-body mt-4" style="-webkit-box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5)">
                                <h6 class="mb-3">Update Status Pembayaran:</h6>
                                <div class="form-group" id="update-status-pembayaran-div">
                                    <div class="input-group"><span class="input-group-text"><i class="icofont icofont-paperclip"></i></span>
                                        <select class="form-select" id="update-status-pembayaran" name="status_pembayaran" required="">
                                            <option selected="" disabled="" value="">Pilih Status Pembayaran...</option>
                                            <option value="Lunas">Lunas</option>
                                            <option value="DP">DP</option>
                                            <option value="Batal">Batal</option>
                                        </select>
                                    </div>
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

    $(".filter-status").click(function() {
        if($(this).hasClass('reset-filter')){
            filterDateStart= null;
            filterDateEnd= null;
            filterTrx = null;

            $('#filter-tanggal').val('');
            $('#filter-tanggal').data('daterangepicker').setStartDate(moment().format("DD-MM-YYYY"));
            $('#filter-tanggal').data('daterangepicker').setEndDate(moment().format("DD-MM-YYYY"));

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

                link = "{{route('pemilikLapangan.getPenyewaProfil', [':penggunaPenyewaId',':date', ':pembayaranId'])}}";
                link = link.replace(":penggunaPenyewaId", table.row(row).data().id_pengguna);
                link = link.replace(":date", table.row(row).data().tgl_booking);
                link = link.replace(":pembayaranId", table.row(row).data().id_pembayaran);
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

    $('body').on('hidden.bs.modal', '#data-profil-penyewa-modal', function () {
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
                    var pembayaranId = orderDataArr.pembayaran_id;

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

            $('#nama-penyewa').val(namaPenyewa);
            $('#nomor-telepon-penyewa').val(nomorTeleponPenyewa);
            $('#nama-lapangan-invc').empty().append(namaLapangan);
            $('#alamat-lapangan-invc').empty().append(alamatLapangan);
            $('#jenis-sewa').empty().append(((jenisBooking === 'per_jam') ? 'Per Jam' : 'Bulanan'));
            $('#cara-pembayaran').empty().append(caraPembayaran);
            $('#status-pembayaran').empty().append(statusPembayaran);
            $('#biaya-sewa').empty().append(formatter.format(totalBiaya));
            $('#total-biaya-sewa').empty().append(formatter.format(totalBiaya));

            if(statusPembayaran === 'DP' || statusPembayaran === 'Lunas'){
                $("#update-status-pembayaran").val(statusPembayaran).change();
            }

            if(statusPembayaran === 'Batal'){
                $("#update-status-pembayaran").val(statusPembayaran).change();
            }

            if(statusPembayaran === 'Proses' || statusPembayaran === 'DP' || statusPembayaran === 'Lunas'){
                linkFotoBuktiBayar = "{{route('pemilikLapangan.getFileBuktiPembayaran', ':pembayaran_id')}}";
                linkFotoBuktiBayar = linkFotoBuktiBayar.replace(":pembayaran_id", pembayaranId);
                $('.photo-proof-payment-not-found').remove();
                $("#foto-bukti-pembayaran-full").show();
                $("#foto-bukti-pembayaran-full").attr("href", linkFotoBuktiBayar);
                $("#foto-bukti-pembayaran-thumbnail").attr("src", linkFotoBuktiBayar);
            }

            if(statusPembayaran === 'Belum Lunas'){
                $('.photo-proof-payment-not-found').remove();
                $("#foto-bukti-pembayaran-full").hide();
                $("#foto-bukti-pembayaran-full").after('<p class="photo-proof-payment-not-found">Belum Memasukan Foto Bukti Pembayaran</p>')
            }

            $('#data-profil-penyewa-modal').find('.modal-footer').children('button').after('\
                <button type="button" onclick="tolakPenyewaan('+pembayaranId+')" class="btn btn-square btn-outline-warning">Tolak</button>\
                <button type="button" onclick="terimaPenyewaan('+pembayaranId+')" class="btn btn-square btn-outline-primary">Terima</button>'
            )
            $('#data-profil-penyewa-modal').modal('show');
        }
    }

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

    function terimaPenyewaan(pembayaranId){
        if($("#update-status-pembayaran").val() === 'Batal' || $("#update-status-pembayaran").val() === null){
            $("#error-msg-update-status-pembayaran").remove();
            $("#update-status-pembayaran").addClass("is-invalid");
            $('#update-status-pembayaran-div').append('<div id="error-msg-update-status-pembayaran" class="text-danger">Update status terima pesanan, hanya bisa dipilih "Lunas atau DP".</div>');
        }else{
            $("#update-status-pembayaran").removeClass("is-invalid");
            $("#error-msg-update-status-pembayaran").remove();
            $("#update-status-pembayaran").addClass("is-valid");

            link = "{{route('pemilikLapangan.updateStatusPembayaran', ':id')}}";
            link = link.replace(':id', pembayaranId);

            swal.fire({
                title: "Terima Penyewaan?",
                text: "Status pembayaran penyewa akan diperbaharui!",
                icon: "warning",
                showCancelButton: true,
                // confirmButtonClass: "btn-danger",
                confirmButtonText: "Save",
                closeOnConfirm: true,
                preConfirm: (login) => {
                    return $.ajax({
                        type: "POST",
                        url: link,
                        datatype : "json",
                        data: {'pembayaranId':pembayaranId, "_token": "{{ csrf_token() }}", 'statusPembayaran': $("#update-status-pembayaran").val()},
                        success: function(data){

                        },
                        error: function(data){
                            swal.fire({title:"Terima Penyewaan Gagal!", text:"Terima penyewaan gagal di proses.", icon:"error"});
                        }
                    });
                }
            }).then((result) => {
                if(result.value){
                    swal.fire({title:"Terima Penyewaan Berhasil!", text:"Status penyewaan telah berhasil di perbarui.", icon:"success"})
                    .then(function(){
                        window.location.href = "";
                    });
                }
            });
        }
        console.log($("#update-status-pembayaran").val())
    }

    function tolakPenyewaan(pembayaranId){
        if($("#update-status-pembayaran").val() === 'Lunas' || $("#update-status-pembayaran").val() === 'DP'){
            $("#error-msg-update-status-pembayaran").remove();
            $("#update-status-pembayaran").addClass("is-invalid");
            $('#update-status-pembayaran-div').append('<div id="error-msg-update-status-pembayaran" class="text-danger">Update status tolak pesanan, hanya bisa dipilih "Batal".</div>');
        }else{
            $("#update-status-pembayaran").removeClass("is-invalid");
            $("#error-msg-update-status-pembayaran").remove();
            $("#update-status-pembayaran").addClass("is-valid");
            // $("#update-status-pembayaran").val("Batal").change();

            link = "{{route('pemilikLapangan.updateStatusPembayaran', ':id')}}";
            link = link.replace(':id', pembayaranId);

            swal.fire({
                title: "Tolak Penyewaan?",
                text: "Status pembayaran penyewa akan diperbaharui!",
                icon: "warning",
                showCancelButton: true,
                // confirmButtonClass: "btn-danger",
                confirmButtonText: "Save",
                closeOnConfirm: true,
                preConfirm: (login) => {
                    return $.ajax({
                        type: "POST",
                        url: link,
                        datatype : "json",
                        data: {'pembayaranId':pembayaranId, "_token": "{{ csrf_token() }}", 'statusPembayaran': $("#update-status-pembayaran").val()},
                        success: function(data){

                        },
                        error: function(data){
                            swal.fire({title:"Tolak Penyewaan Gagal!", text:"Tolak penyewaan gagal di proses.", icon:"error"});
                        }
                    });
                }
            }).then((result) => {
                if(result.value){
                    swal.fire({title:"Tolak Penyewaan Berhasil!", text:"Status penyewaan telah berhasil di perbarui.", icon:"success"})
                    .then(function(){
                        window.location.href = "";
                    });
                }
            });
        }

    }

</script>
@endsection
