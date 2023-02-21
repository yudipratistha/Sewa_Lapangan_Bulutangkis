@extends('layouts.app')

@section('title', 'Pemilik Lapangan Dashboard')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/jquery-ui.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/photoswipe.css')}}">

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
                    <div class="col-sm-6 mb-3">
                        <h3 class="pull-left">Dashboard Pemilik Lapangan</h3>
                    </div>
                    <div class="card">
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
                                    @foreach ($dataLapangan as $dataLapanganValue)
                                        <li class="nav-item"><a class="nav-link @if($dataLapanganValue->nomor_court === 1) active @endif" id="top-home-danger" data-bs-toggle="tab" href="#court-{{$dataLapanganValue->nomor_court}}" role="tab" aria-controls="top-homedanger" aria-selected="true"><i class="icofont icofont-badminton-birdie"></i>Court {{$dataLapanganValue->nomor_court}}</a>
                                            <div class="material-border"></div>
                                        </li>
                                    @endforeach
                                    {{-- @for ($court= 1; $court <= $dataLapangan[0]->jumlah_court; $court++)
                                        <li class="nav-item"><a class="nav-link @if($court === 1) active @endif" id="top-home-danger" data-bs-toggle="tab" href="#court-{{$court}}" role="tab" aria-controls="top-homedanger" aria-selected="true"><i class="icofont icofont-badminton-birdie"></i>Court {{$court}}</a>
                                            <div class="material-border"></div>
                                        </li>
                                    @endfor --}}
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tabbed-card">

                                <div class="tab-content" id="top-tabContentdanger">
                                    @for ($court= 1; $court <= $dataLapangan[0]->jumlah_court; $court++)
                                        <div class="tab-pane fade @if($court === 1) active show @endif" id="court-{{$court}}" role="tabpanel" aria-labelledby="top-home-tab">
                                            <div class="table-responsive">
                                                <table class="display datatables hover-table-admin" id="table-court-{{$court}}">
                                                    <thead>
                                                        <tr>
                                                            <th>Jam</th>
                                                            <th>Penyewa</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
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

<!-- Modal Edit Court-->
<div class="modal fade" id="edit-court-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Court</h3>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="theme-form" id="editCourt" action="" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="edit-court-status">Status</label>
                                <div class="input-group"><span class="input-group-text"><i class="icofont icofont-tick-mark"></i></span>
                                    <select class="form-select" id="edit-court-status" name="edit_court_status" required="">
                                        <option selected="" disabled="" value="">Pilih Status...</option>
                                        @foreach ($dataTipeStatusCourt as $tipeStatusCourt)
                                            <option value="{{ $tipeStatusCourt->tipe_status_court_id }}">{{ $tipeStatusCourt->tipe_status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="job-description-div">
                                <label class="form-label">Alasan</label>
                                <div class="input-group"><span class="input-group-text"><i class="icofont icofont-pencil-alt-5"></i></span>
                                    <textarea class="form-control" id="edit-court-alasan" name="edit_court_alasan" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-square btn-outline-light txt-dark" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="update-court-button" class="btn btn-square btn-outline-secondary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Data Profil Penyewa-->
<div class="modal fade" id="data-profil-penyewa-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header text-center d-block">
                <h3 class="modal-title">Rincian Penyewaan</h3>
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
<script src="{{url('/assets/js/datepicker/date-picker-jquery-ui/jquery-ui.js')}}"></script>
<script src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{url('/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>
<script src="{{url('/assets/js/photoswipe/photoswipe.min.js')}}"></script>
<script src="{{url('/assets/js/photoswipe/photoswipe-ui-default.min.js')}}"></script>
<script src="{{url('/assets/js/photoswipe/photoswipe.js')}}"></script>

<script>
    $( document ).ready(function() {
        if(location.search !== ''){
            var params = new URLSearchParams(window.location.search);

            $('a[href="#court-'+params.get('court')+'"]').tab('show');
            $('#tanggal').datepicker("setDate", new Date(params.get('tanggalSewa'))).trigger('click');
            $('.ui-datepicker-current-day').click();
            getPenyewa(params.get('penggunaPenyewaId'), params.get('court'), params.get('pembayaranId'))

            if (location.href.includes('?')) {
                history.pushState({}, null, location.href.split('?')[0]);
            }
        }
    });

    $.each({!! $dataLapangan !!}, function (key, value) {
        $("#table-court-"+value.nomor_court).dataTable({
            "columns": [
                { "orderable": true, "width": "10%" },
                null,
                { "orderable": false, "width": "16%" },
                { "orderable": false, "width": "13%" },

            ],
        });
    });

    $('body').on('hidden.bs.modal', '.modal', function () {
        $('#data-profil-penyewa-modal').find('.modal-footer').children('button').slice(-2).remove();

        if($('#data-profil-penyewa-modal').find('#update-status-pembayaran').val() !== null){
            $('#data-profil-penyewa-modal').find('#update-status-pembayaran').val('')
        }
    });

    var date;

    $('#tanggal').datepicker({
        dateFormat: 'dd-mm-yy',
        minDate: new Date(),
        autoclose: true,
        onSelect: function(dateText) {
            $('#tgl-booking').empty().append(dateText);
            date = dateText;
            console.log('tess')
            $.ajax({
                url: "{{route('pemilikLapangan.getDataLapanganPemilik', $dataLapangan[0]->lapangan_id)}}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "tanggal" : date
                },
                dataType: "json",
                success:function(data){
                    $.each({!! $dataLapangan !!}, function (key, value) {
                        $('#table-court-'+value.nomor_court).DataTable().clear().draw();
                        $('#table-court-'+value.nomor_court).DataTable().rows.add(data['court_'+value.nomor_court]);
                        $('#table-court-'+value.nomor_court).DataTable().columns.adjust().draw();
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

    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true}).columns.adjust();
    });

    function getPenyewa(penggunaPenyewaId, court, pembayaranId){
        link = "{{route('pemilikLapangan.getPenyewaProfil', [':penggunaPenyewaId',':date', ':pembayaranId'])}}";
        link = link.replace(":penggunaPenyewaId", penggunaPenyewaId);
        link = link.replace(":date", date);
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

            linkFotoBuktiBayar = "{{route('pemilikLapangan.getFileBuktiPembayaran', ':pembayaran_id')}}";
            linkFotoBuktiBayar = linkFotoBuktiBayar.replace(":pembayaran_id", pembayaranId);

            $.ajax({
                url:linkFotoBuktiBayar,
                success: function () {
                    $('.photo-proof-payment-not-found').remove();
                    $("#foto-bukti-pembayaran-full").show();
                    $("#foto-bukti-pembayaran-full").attr("href", linkFotoBuktiBayar);
                    $("#foto-bukti-pembayaran-thumbnail").attr("src", linkFotoBuktiBayar);
                },
                error: function (jqXHR, status, er) {
                    if (jqXHR.status === 404) {
                        $("#foto-bukti-pembayaran-full").attr("href", '');
                        $("#foto-bukti-pembayaran-thumbnail").attr("src", '');
                        $('.photo-proof-payment-not-found').remove();
                        $("#foto-bukti-pembayaran-full").hide();
                        $("#foto-bukti-pembayaran-full").after('<p class="photo-proof-payment-not-found">Belum Memasukan Foto Bukti Pembayaran</p>')
                    }
                }
            });

            $('#data-profil-penyewa-modal').find('.modal-footer').children('button').after('\
                <button type="button" onclick="tolakPenyewaan('+pembayaranId+')" class="btn btn-square btn-outline-warning">Tolak</button>\
                <button type="button" onclick="terimaPenyewaan('+pembayaranId+')" class="btn btn-square btn-outline-primary">Terima</button>'
            )
            $('#data-profil-penyewa-modal').modal('show');
        }
    }

    function editCourt(idLapangan, status_court_id, waktuLapangan){

        link = "{{route('pemilikLapangan.statusCourtLapanganStatus', [':idLapangan',':status_court_id'])}}";
        link = link.replace(":idLapangan", idLapangan);
        link = link.replace(":status_court_id", status_court_id);
        $.ajax({
            url: link,
            method: "GET",
            dataType: 'json',
            success: function(data){
                console.log(data)
                data.forEach(function(item, index){

                    var jamStatusBerlaku = item.jam_status_berlaku_dari.split(':');
                    var waktuLapanganSplit = waktuLapangan.split(' - ');
                    if(waktuLapanganSplit[0] === jamStatusBerlaku[0]+":"+jamStatusBerlaku[1]){

                        $('#edit-court-status').find('option').removeAttr('selected');
                        $('#edit-court-status').find('option[value="'+item.tipe_status+'"]').prop('selected',true);
                        $('#edit-court-alasan').val(item.detail_status);
                        $('#update-court-button').attr("onclick", "updateCourt("+item.status_court_id+")");
                    }
                });
                $('#edit-court-modal').modal('show');
            },
            error: function(data){
                console.log("asdsad", data);
            }
        });
    }

    function updateCourt(status_court_id){
        link = "{{route('pemilikLapangan.updateCourtLapanganStatus', ':id')}}";
        link = link.replace(':id', status_court_id);

		swal.fire({
			title: "Edit Court?",
			text: "Court will be edited on court list!",
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
                    data: $("#editCourt").serialize() + "&lapangan_id={{ $dataLapangan[0]->lapangan_id }}",
                    success: function(data){

                    },
                    error: function(data){
                        swal.fire({title:"Ticket Failed to Approved!", text:"This ticket was not approved successfully", icon:"error"});
                    }
                });
            }
		}).then((result) => {
            if(result.value){
                swal.fire({title:"Ticket Approved!", text:"This ticket has been approved on tickets list", icon:"success"})
                .then(function(){
                    window.location.href = "";
                });
            }
        });
    }

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
