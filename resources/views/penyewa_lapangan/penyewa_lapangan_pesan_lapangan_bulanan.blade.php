@extends('layouts.app')

@section('title', 'Pemilik Lapangan Dashboard')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/jquery-ui.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/photoswipe.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/leaflet.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/leaflet-gesture-handling.min.css')}}">

<style>
.nav-tabs {
    white-space: nowrap !important;
    flex-wrap: nowrap !important;
    max-width: 85% !important;
    overflow-x: scroll !important;
    overflow-y: hidden !important;
    -webkit-overflow-scrolling: touch !important;
}
.nav-item>li {
    display: inline-block !important;
}
</style>

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
                        <h3>{{$dataLapangan->nama_lapangan}}</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('penyewaLapangan.dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('penyewaLapangan.profilLapangan', [$dataLapangan->lapangan_id, str_replace(' ', '-', strtolower($dataLapangan->nama_lapangan))])}}">Profil Lapangan</a></li>
                            <li class="breadcrumb-item active">Pesan Lapangan</li>
                        </ol>
                    </div>
                    <div class="card" style="margin-bottom: 10px;">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-borderless table-sm">
                                    <tbody>
                                        <tr>
                                            <td scope="row" style="width: 145px;padding-left: 0px;padding-right: 0px;">Nama Lapangan <span class="pull-right">:&nbsp;</span></td>
                                            <th style="padding-left: 0px;">{{$dataLapangan->nama_lapangan}}</th>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="width: 145px;padding-left: 0px;padding-right: 0px;">Jenis Booking <span class="pull-right">:&nbsp;</span></td>
                                            <th style="padding-left: 0px;">
                                                <span class="fw-bold" id="jenis-booking">Bulanan</span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="width: 145px;padding-left: 0px;padding-right: 0px;">Total Durasi (Jam) <span class="pull-right">:&nbsp;</span></td>
                                            <th style="padding-left: 0px;"><span class="fw-bold" id="total-durasi">-</span>&nbsp;<span class="fw-bold">Jam</span></th>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="width: 145px;padding-left: 0px;padding-right: 0px;">Sisa Durasi (Jam) <span class="pull-right">:&nbsp;</span></td>
                                            <th style="padding-left: 0px;"><span class="fw-bold" id="sisa-durasi">-</span>&nbsp;<span class="fw-bold">Jam</span></th>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="width: 145px;padding-left: 0px;padding-right: 0px;">Tanggal Booking <span class="pull-right">:&nbsp;</span></td>
                                            <th style="padding-left: 0px;"><span class="fw-bold" id="tgl-booking">-</span></th>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="width: 145px;padding-left: 0px;padding-right: 0px;">Total <span class="pull-right">:&nbsp;</span></td>
                                            <th style="padding-left: 0px;"><span class="fw-bold" id="total-harga">-</span></th>
                                        </tr>
                                        <!-- <tr>
                                            <td scope="row" style="width: 145px;padding-left: 0px;padding-right: 0px;">Pilih Pembayaran <span class="pull-right">:&nbsp;</span></td>
                                            <th style="padding-left: 0px;">
                                                <span class="btn input-air-primary fw-bold" style="display: initial;padding: 3px 3px 3px 3px;background-color: #e6edef !important;border-color: #e6edef !important;text-align: center;color: #212529;font-weight: bold;cursor: pointer;font-size: inherit;" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modal-metode-pembayaran" data-bs-original-title="" title="">Belum Dipilih</span>
                                            </th>
                                        </tr> -->
                                    </tbody>
                                </table>
                                <hr/>
                                @if($dataLapangan->status_pembayaran === "Belum Lunas" || isset($dataBookUser) && $dataBookUser->status_pembayaran === "Belum Lunas")
                                    <span class="btn btn-secondary" style="cursor: not-allowed;background-color: #90b4cd !important;border-color: #90b4cd !important;"></i>Ada pembayaran yang belum lunas!</button>
                                    <!-- <button type="button" onClick="pesanLapangan()" class="btn btn-square btn-outline-blue">Konfirmasi Sewa</button> -->
                                @else
                                    <button type="button" onClick="bookingCounting()" class="btn btn-square btn-outline-blue">Checkout</button>
                                    <!-- <button type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modal-metode-pembayaran" data-bs-original-title="" title="" class="btn btn-square btn-outline-blue">Pilih Pembayaran</button> -->
                                    <!-- <button type="button" id="pay-button" class="btn btn-square btn-outline-blue">Pilih Pembayaran</button> -->
                                @endif
                                <!-- <button type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modal-metode-pembayaran" data-bs-original-title="" title="" class="btn btn-square btn-outline-blue">Pilih Pembayaran</button> -->

                            </div>
                        </div>
                    </div>
                    <div class="card" style="margin-bottom: 10px;">
                        <div class="col-md-12 card-header row pb-0 pe-0">
                            <div class="col-md-4 mb-3 mt-0 row g-3">
                                <label class="col-md-3 mt-2 col-form-label">Pilih Tanggal</label>
                                <div class="col-md-9 mt-2">
                                    <div class="input-group date">
                                        <input class="form-control digits" id="tanggal" name="tanggal" type="text" placeholder="dd-mm-yyyy" readonly>
                                        <div class="input-group-text"><i class="fa fa-calendar"> </i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <ul class="pull-right nav nav-tabs border-tab nav-success" id="top-tabdanger" role="tablist">
                                    @foreach ($dataLapanganCourt as $dataLapanganCourtValue)
                                        <li class="nav-item"><a class="nav-link @if($dataLapanganCourtValue->nomor_court === 1) active @endif" id="top-home-danger" data-bs-toggle="tab" href="#court-{{$dataLapanganCourtValue->nomor_court}}" role="tab" aria-controls="top-homedanger" aria-selected="true"><i class="icofont icofont-badminton-birdie"></i>Court {{$dataLapanganCourtValue->nomor_court}}</a>
                                            <div class="material-border"></div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tabbed-card">
                                <form id="check-book-time">
                                    @csrf
                                    <div class="tab-content" id="top-tabContentdanger">
                                        @foreach ($dataLapanganCourt as $dataLapanganCourtValue)
                                            <div class="tab-pane fade @if($dataLapanganCourtValue->nomor_court === 1) active show @endif" id="court-{{$dataLapanganCourtValue->nomor_court}}" role="tabpanel" aria-labelledby="top-home-tab">
                                                <div class="table-responsive">
                                                    <table class="display datatables hover-table-court-profile" id="table-court-{{$dataLapanganCourtValue->nomor_court}}">
                                                        <thead>
                                                            <tr>
                                                                <th>Pilih</th>
                                                                <th>Jam</th>
                                                                <th>Penyewa</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </form>
                            </div>
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

<!-- Modal Booking Counting-->
<div class="modal fade" id="modal-booking-counting" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header text-center d-block">
                <h4 class="modal-title ">Review Booking</h3>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="card-body">
                <div class="col-sm-12">
                    <div class="card pilih-pembayaran-card" style="border: 0;">
                        <div class="card-header pt-2 pb-2 mb-3" style="-webkit-box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5)">
                            <h5>Lapangan Bulu Tangkis Pak Tomo</h5>
                            <i class="fa fa-map-marker" style="margin-right: 5px;"></i><p style="display: inline-block;">Jl. Bung Tomo 1</p>
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
                                                <p>Bulanan</p>
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
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-square btn-outline-light txt-dark" data-bs-dismiss="modal">Close</button>
                <button type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modal-metode-pembayaran" data-bs-original-title="" title="" class="btn btn-square btn-outline-blue">Pilih Pembayaran</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Metode Pembayaran-->
<div class="modal fade" id="modal-metode-pembayaran" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background: rgba(98, 98, 98, 0.7);">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header text-center d-block">
                <h4 class="modal-title ">Pembayaran</h3>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($dataDaftarJenisPembayaranLapangan as $keyDaftarJenisPembayaran => $valueDaftarJenisPembayaran)
                        <div class="col-sm-12">
                            <div class="card pilih-pembayaran-card">
                                <div class="pilih-pembayaran media p-20" style="-webkit-box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);cursor: pointer;">
                                    <div class="media-body">
                                        <h6 class="mt-0">{{$valueDaftarJenisPembayaran->nama_jenis_pembayaran}}</h6>
                                    </div>
                                    <div class="radio radio-primary me-3" style="display: contents;">
                                        <input id="radio30" type="radio" name="pilih_pembayaran" value="{{$valueDaftarJenisPembayaran->daftar_jenis_pembayaran_id}}">
                                        <label for="radio30"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-square btn-outline-light txt-dark" data-bs-dismiss="modal">Close</button>
                <button type="button" onClick="pesanLapangan()" class="btn btn-square btn-outline-blue">Konfirmasi Sewa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('plugin_js')
<script src="{{url('/assets/js/datepicker/date-picker-jquery-ui/jquery-ui.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-picker-jquery-ui/datepicker.idn.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{url('/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>
<!-- <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script> -->

<!-- <script>
    const payButton = document.querySelector('#pay-button');
    payButton.addEventListener('click', function(e) {
        e.preventDefault();

        snap.pay('', {
            // Optional
            onSuccess: function(result) {
                /* You may add your own js here, this is just example */
                // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                console.log(result)
            },
            // Optional
            onPending: function(result) {
                /* You may add your own js here, this is just example */
                // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                console.log(result)
            },
            // Optional
            onError: function(result) {
                /* You may add your own js here, this is just example */
                // document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                console.log(result)
            }
        });
    });
</script> -->

<script>
    var harga_per_jam = {!! json_encode($dataLapangan->harga_per_jam) !!};
    var dataPaketSewaBulanan = {!! json_encode($dataPaketSewaBulanan[0]) !!};

    var date;
    var availableDates = [];
    var orderData = {};
    var sisaDurasi = dataPaketSewaBulanan.total_durasi_jam;

    const formatter = new Intl.NumberFormat('id', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    });

    $.datepicker.setDefaults(
        $.extend(
            {'dateFormat':'dd-mm-yy'},
            $.datepicker.regional['id']
        )
    );

    console.log(dataPaketSewaBulanan.total_durasi_jam)
    $('#total-durasi').empty().append(dataPaketSewaBulanan.total_durasi_jam);
    $('#sisa-durasi').empty().append(dataPaketSewaBulanan.total_durasi_jam);
    $('#total-harga').empty().append(formatter.format(dataPaketSewaBulanan.total_harga));

    $.each({!! $dataLapanganCourt !!}, function (key, value) {
        $('#table-court-'+value.nomor_court).DataTable({
            processing: true,
            bFilter: false,
            dom: 'tip',
            order: [1, "asc"],
            columns: [
                { "orderable": false, "width": "5%" },
                { "orderable": true, "width": "10%" },
                null
            ]
        });
    });

    $('input[type="checkbox"]').prop('checked', false);
    $('#tanggal').datepicker({
        dateFormat: 'dd-mm-yy',
        showOtherMonths: true,
        minDate: new Date(),
        autoclose: true,
        beforeShowDay: function(d) {

            // console.log(d.getDate())
            var dmy = d.getDate()

            if(d.getDate()<10) dmy="0"+dmy;
            dmy+= "-";


            if(d.getMonth()<9) dmy+="0";
            dmy+= (d.getMonth()+1) + "-" + d.getFullYear();


            if(Object.keys(orderData).length >= 30){
                if ($.inArray(dmy, Object.keys(orderData)) != -1) {
                    // console.log(dmy+' : '+($.inArray(dmy, Object.keys(orderData))));
                    return [true, "","Tersedia"];
                } else{
                    return [false,"","Limit Booking Bulanan"];
                }
            }else{
                return [true, "","Tersedia"];
            }

        },
        onSelect: function(dateText) {
            $('#tgl-booking').empty().append(dateText);
            date = dateText.split('-').reverse().join('-');

            $.ajax({
                url: "{{route('penyewaLapangan.getAllDataLapangan', $dataLapangan->lapangan_id)}}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "tanggal" : date
                },
                dataType: "json",
                success:function(data){
                    $.each({!! $dataLapanganCourt !!}, function (key, value) {
                        var openHoursData = Object.keys(data['court_'+value.nomor_court]).map(function (key) { return data['court_'+value.nomor_court][key]; });

                        $('#table-court-'+value.nomor_court).DataTable().clear().draw();
                        $('#table-court-'+value.nomor_court).DataTable().rows.add(openHoursData);
                        $('#table-court-'+value.nomor_court).DataTable().columns.adjust().draw();
                    });

                    $(document).on('draw.dt', function () {
                        if(orderData[date] !== undefined){
                            for(let counterDate = 0; counterDate < Object.keys(orderData).length; ++counterDate){
                                for(let counterCourt = 0; counterCourt < Object.keys(orderData[date]).length; ++counterCourt){
                                    let court = Object.keys(orderData[date])[counterCourt];
                                    for(let counterData = 0; counterData < orderData[date][court].length; ++counterData){
                                        var orderDataArr = orderData[date][court][counterData];
                                        if(Object.keys(orderData)[counterDate] === date){
                                            $("input[value*='"+JSON.stringify(orderDataArr)+"']").prop('checked', true);
                                        }
                                    }
                                }
                            }
                        }

                        if(sisaDurasi === 0){
                            $('input[type="checkbox"]:not(:checked)').prop('disabled', true);
                            $('input[type="checkbox"]:not(:checked)').css('cursor', 'not-allowed');
                        }
                    });
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
            return $('#tanggal').trigger('change');
        }
    }).datepicker('setDate', new Date());
    $('.ui-datepicker-current-day').click();

    $('.table-responsive').on('change', 'input[type="checkbox"]', function() {
        if($(this).prop("checked") === true){
            sisaDurasi -= 1;
            court = JSON.parse($(this).val()).court;

            if(orderData[date] === undefined){
                orderData[date]= {};
            }

            if(orderData[date][court] === undefined){
                orderData[date][court] = [];
            }

            // Object.assign(orderData[date], JSON.parse($(this).val()));
            orderData[date][court].push(JSON.parse($(this).val()));
            var in30Days = new Date(Object.keys(orderData)[0].split('-')[0] + '/' + Object.keys(orderData)[0].split('-')[1] + '/' + Object.keys(orderData)[0].split('-')[2]);

            in30Days.setDate(in30Days.getDate() + 30);
            $("#tanggal").datepicker("option", "minDate", Object.keys(orderData)[0].split('-')[2] + '-' + Object.keys(orderData)[0].split('-')[1] + '-' + Object.keys(orderData)[0].split('-')[0]);
            $("#tanggal").datepicker("option", "maxDate", in30Days);

            if(sisaDurasi === 0){
                $('input[type="checkbox"]:not(:checked)').prop('disabled', true);
                $('input[type="checkbox"]:not(:checked)').css('cursor', 'not-allowed');
            }

        }else{
            var orderDataCancel = JSON.parse($(this).val());
            sisaDurasi += 1;

            if(orderData[date] !== undefined){
                for(let counterDate = 0; counterDate < Object.keys(orderData).length; ++counterDate){
                    var dateKey = Object.keys(orderData)[counterDate];
                    for(let counterCourt = 0; counterCourt < Object.keys(orderData[Object.keys(orderData)[counterDate]]).length; ++counterCourt){
                        var courtKey = Object.keys(orderData[Object.keys(orderData)[counterDate]])[counterCourt];
                        for(let counterData = 0; counterData < orderData[Object.keys(orderData)[counterDate]][courtKey].length; ++counterData){
                            var orderDataArr = orderData[dateKey][courtKey][counterData];
                            if(Object.keys(orderData)[counterDate] === dateKey && orderDataArr.court === orderDataCancel.court && orderDataArr.jam === orderDataCancel.jam){
                                orderData[dateKey][courtKey].splice(counterData, 1);
                            }
                        }
                        if (Object.keys(orderData[dateKey][courtKey]).length === 0) {
                            delete orderData[dateKey][courtKey];
                        }
                    }
                }
            }

            if (Object.keys(orderData[date]).length === 0) {
                delete orderData[date];

                if(Object.keys(orderData).length === 0){
                    $("#tanggal").datepicker("option", "minDate", new Date());
                }else{
                    var in30Days = new Date(Object.keys(orderData)[0].split('-')[2] + '/' + Object.keys(orderData)[0].split('-')[1] + '/' + Object.keys(orderData)[0].split('-')[0]);
                    in30Days.setDate(in30Days.getDate() + 30);
                    $("#tanggal").datepicker("option", "minDate", Object.keys(orderData)[0]);
                    $("#tanggal").datepicker("option", "maxDate", in30Days);
                    $('.ui-datepicker-current-day').click();
                }
            }

            if(sisaDurasi !== 0){
                $('input[type="checkbox"]:not(:checked)').filter(function(){return $(this).val() !== ''}).prop('disabled', false);
                $('input[type="checkbox"]:not(:checked)').filter(function(){return $(this).val() !== ''}).removeAttr('style');
            }
            console.log(orderData)
            $.each({!! $dataLapanganCourt !!}, function (key, value) {
                $('#table-court-'+value.nomor_court).children().children().children().first().removeAttr('style');
                $('#table-court-'+value.nomor_court).children('tbody').children().find("td:first").removeAttr('style');
                $('#table-court-'+value.nomor_court).children('tbody').children('tr:last').find("td:first").removeAttr('style');
            });
        }
        $('#sisa-durasi').empty().append(sisaDurasi);
    });

    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true}).columns.adjust();
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

    $('body').on('hidden.bs.modal', '#modal-booking-counting', function () {
        $('#booking-counting').children().remove();
    });

    function bookingCounting(){
        var courtStatus= false;
        var bookingTime = {};
        console.log(orderData)
        const orderDataSort = Object.keys(orderData).sort().reduce((obj, key) => {
                obj[key] = orderData[key];
                return obj;
            },
            {}
        );

        if(Object.keys(orderDataSort).length !== 0){
            for(let index = 0; index < Object.keys(orderDataSort).length; ++index){

                for(let courtIndex = 0; courtIndex < Object.keys(orderDataSort[Object.keys(orderDataSort)[index]]).length; ++courtIndex){
                    var courtKey = Object.keys(orderDataSort[Object.keys(orderDataSort)[index]])[courtIndex];

                    orderDataSort[Object.keys(orderDataSort)[index]][courtKey].sort(dynamicSort('court'));
                    for(let orderIndex = 0; orderIndex < orderDataSort[Object.keys(orderDataSort)[index]][courtKey].length; ++orderIndex){
                        var orderDataArr = orderDataSort[Object.keys(orderDataSort)[index]][courtKey][orderIndex];

                        if(orderIndex === 0 || Object.keys(bookingTime).includes((orderDataArr.court+'-'+Object.keys(orderDataSort)[index]).toString()) === false){
                            courtStatus = true;
                        }else{
                            courtStatus = false;
                        }

                        if(bookingTime[orderDataArr.court+'-'+Object.keys(orderDataSort)[index]] === undefined){
                            bookingTime[orderDataArr.court+'-'+Object.keys(orderDataSort)[index]]= [];
                        }

                        bookingTime[orderDataArr.court+'-'+Object.keys(orderDataSort)[index]].push(orderDataArr.jam)
                        bubbleSort(bookingTime[orderDataArr.court+'-'+Object.keys(orderDataSort)[index]])

                        if(courtStatus === true){
                            let dateConvert = new Date(Object.keys(orderDataSort)[index].split('-')[0] + '/' + Object.keys(orderDataSort)[index].split('-')[1] + '/' + Object.keys(orderDataSort)[index].split('-')[2]);
                            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

                            $("#booking-counting").append('\
                                    <span style="font-size: 15px;font-weight: bold;">Court '+orderDataArr.court+'</span>\
                                    <p style="margin-top: 10px;">'+dateConvert.toLocaleDateString('id', options)+'</p>\
                                    <div id="booking-hour-counting-'+orderDataArr.court+'-'+Object.keys(orderDataSort)[index]+'" class="row booking-hour-counting">\
                                    </div>\
                            ');
                        }
                    }
                }
            }

            console.log(bookingTime)
            $.each(bookingTime, function(index, value) {
                // console.log(value);
                $.each(value, function(bookingTimeIndex, bookingTimeValue){
                    // console.log(bookingTimeValue);
                        $('#booking-hour-counting-'+index).append('\
                        <div class="col-sm-12">\
                            <div class="card" style="border: 0;margin-bottom: 7px;">\
                                <div class="media" style="background-color: azure;border-radius: 5px;border-left: 5px gray solid;padding: 3px 5px 0px 5px;">\
                                    <div class="media-body">\
                                        <p>'+bookingTimeValue+'</p>\
                                    </div>\
                                    <div>\
                                        <p>Harga Sudah Disesuaikan!</p>\
                                    </div>\
                                </div>\
                            </div>\
                        </div>\
                    ');
                });
                $('#booking-hour-counting-'+index).children().last().append('<hr/>');
            });

            $('#biaya-sewa').empty().append(formatter.format(dataPaketSewaBulanan.total_harga));
            $('#total-biaya-sewa').empty().append(formatter.format(dataPaketSewaBulanan.total_harga));
            $('#modal-booking-counting').modal('show');
        }
    }

    function pesanLapangan(){
        var pilihPembayaran = $(".pilih-pembayaran").children('.radio').children('input').filter(":checked").val();
        var url =
		swal.fire({
			title: "Konfirmasi Sewa Lapangan?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Save",
            closeOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('penyewaLapangan.storeBookingLapanganBulanan')}}",
                    datatype : "json",

                    data: {
                        "_token": "{{ csrf_token() }}",
                        "orderData": orderData,
                        "tglBooking": date,
                        "pilihPembayaran": pilihPembayaran,
                        "lapanganId": {{$dataLapangan->lapangan_id}}
                    },
                    success: function(data){

                    },
                    error: function(data){
                        var responseErrTxt = '';

                        if(data.responseJSON.errorTextJamBooking.trim()){
                            responseErrTxt = data.responseJSON.errorTextJamBooking+'<br>';

                            $.each({!! $dataLapanganCourt !!}, function (key, value) {
                                $('#table-court-'+value.nomor_court).children().children().children().first().css({'border-top': '1px solid red', 'border-left': '1px solid red', 'border-right': '1px solid red'});
                                $('#table-court-'+value.nomor_court).children('tbody').children().find("td:first").css({'border-top': '1px solid red', 'border-left': '1px solid red', 'border-right': '1px solid red'});
                                $('#table-court-'+value.nomor_court).children('tbody').children('tr:last').find("td:first").css({'border-left': '1px solid red', 'border-right': '1px solid red', 'border-bottom': '1px solid red'});
                            });
                        }
                        if(data.responseJSON.errorTextPembayaran.trim()){
                            responseErrTxt += data.responseJSON.errorTextPembayaran;
                            $(".pilih-pembayaran-card").addClass("invalid-pilih-pembayaran-card");
                            $(".pilih-pembayaran").addClass("invalid-pilih-pembayaran");
                        }

                        swal.fire({title:"Konfirmasi Sewa Lapangan Gagal Tersimpan!", icon:"error", html: responseErrTxt});
                    }
                });
            }
		}).then((result) => {
            if(result.value){
                swal.fire({title:"Konfirmasi Sewa Lapangan Berhasil Tersimpan!", text:"Segera lunasi pembayaran sewa lapangan!", icon:"success"})
                .then(function(){
                    window.location.href = "{{route('penyewaLapangan.menungguPembayaranPenyewaIndex')}}";
                });
            }
        });
    }

    $(".pilih-pembayaran").on("click", function(){
        $(this).children('.radio').children('input').prop('checked', true);

        $(".pilih-pembayaran-card").removeClass("invalid-pilih-pembayaran-card");
        $(".pilih-pembayaran").removeClass("invalid-pilih-pembayaran");
    });

    // $.ajax({
    //     type: "GET",
    //     url: "{{route('penyewaLapangan.getDaftarJenisPembayaran', $dataLapangan->lapangan_id)}}",
    //     datatype : "json",
    //     success: function(data){
    //         // console.log(data)
    //     }
    // });

    console.log(window.location.pathname);

</script>

@endsection
