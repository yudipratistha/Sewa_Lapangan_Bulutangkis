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
                    <div class="container-fluid">
                        <div class="page-header">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h3>Menunggu Pembayaran</h3>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{route('penyewaLapangan.dashboard')}}" data-bs-original-title="" title="">Home</a></li>
                                        <li class="breadcrumb-item active"><a href="{{route('penyewaLapangan.riwayatPenyewaan')}}" data-bs-original-title="" title="">Riwayat Penyewaan</a></li>
                                        <li class="breadcrumb-item active">Menunggu Pembayaran</li>
                                    </ol>
                                </div>
                                <div class="col-sm-6">
                                    <!-- Bookmark Start-->
                                    <!-- <div class="bookmark">
                                        <ul>
                                            <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover" data-placement="top" title="" data-original-title="Tables"><i data-feather="inbox"></i></a></li>
                                            <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover" data-placement="top" title="" data-original-title="Chat"><i data-feather="message-square"></i></a></li>
                                            <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover" data-placement="top" title="" data-original-title="Icons"><i data-feather="command"></i></a></li>
                                            <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover" data-placement="top" title="" data-original-title="Learning"><i data-feather="layers"></i></a></li>
                                            <li><a href="javascript:void(0)"><i class="bookmark-search" data-feather="star"></i></a>
                                            <form class="form-inline search-form">
                                                <div class="form-group form-control-search">
                                                    <input type="text" placeholder="Search..">
                                                </div>
                                            </form>
                                            </li>
                                        </ul>
                                    </div> -->
                                    <!-- Bookmark Ends-->
                                </div>
                            </div>
                        </div>
                        <!-- Container-fluid starts-->
                        <div class="container-fluid">
                            <div class="row">
                                @if(isset($dataMenungguPembayaran))
                                    <div class="col-xl-12 box-col-8">
                                        <div class="card" style="border-radius: 8px; -webkit-box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);">
                                            <div class="ticket-list">
                                                <div class="card-body">
                                                    <div class="media b-b-light"><img class="img-40 img-fluid m-r-20" src="{!!url(Storage::url($dataMenungguPembayaran->foto_lapangan_1))!!}" alt="">
                                                        <div class="media-body">
                                                            <h6 class="f-w-600">
                                                                {{$dataMenungguPembayaran->nama_lapangan}}
                                                                <span class="pull-right" style="padding-top: 8px;padding-bottom: 8px;margin-right: 5px;">Bayar Sebelum <i class="icofont icofont-clock-time" style="color: #ff8b00;"></i> <p style="display: inline-block; color: #ff8b00;">{{date("d F Y, H:i", strtotime($dataMenungguPembayaran->pembayaran_created_at .'+ 1 hour'))}}</p></span>
                                                            </h6>
                                                            <i class="fa fa-map-marker" style="margin-right: 5px;"></i><p style="display: inline-block;">{{$dataMenungguPembayaran->alamat_lapangan}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="info-widget-card mt-4 mb-4">
                                                        <div class="row">
                                                            <div class="col-sm-3 b-r-light"><span>Transfer Ke Bank</span>
                                                                <h4>{{$dataMenungguPembayaran->nama_jenis_pembayaran}}</h4>
                                                            </div>
                                                            <div class="col-sm-5 b-r-light"><span>Nomor Rekening</span>
                                                                <h4>{{$dataMenungguPembayaran->no_rekening}}</h4>
                                                            </div><div class="col-sm-4"><span>Total Bayar</span>
                                                                <h4>Rp{{$dataMenungguPembayaran->total_biaya}}</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="pull-right mb-3">
                                                        <button type="button" class="btn btn-outline-primary" onclick='getPembayaranDetail()'></i>Lihat Detail</button>
                                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#data-upload-bukti-transfer"></i>Upload Bukti Transfer</button>
                                                        <button class="btn btn-light dropdown-toggle p-2" id="btnGroupDrop1" type="button" data-bs-toggle="dropdown" >. . .</button>
                                                        <div class="dropdown-menu p-0" aria-labelledby="btnGroupDrop1">
                                                            <button class="dropdown-item btn-outline-danger pt-2 pb-2" onclick='batalkanPemesanan({{$dataMenungguPembayaran->pembayaran_id}})'><i class="icon-trash"></i></i> Batalkan Penyewaan</a>
                                                        </div>
                                                        <!-- <button type="button" class="btn btn-outline-danger"></i>...</button> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
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

<!-- Modal Detail Penyewaan-->
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

@if(isset($dataMenungguPembayaran))
    <!-- Modal Upload Bukti Transfer-->
    <div class="modal fade" id="data-upload-bukti-transfer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header text-center d-block">
                    <h4 class="modal-title ">Upload Bukti Transfer</h3>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-sm-12 mb-3">
                                <span style="font-size: 0.857143rem;line-height: 18px;color: var(--N700,rgba(49,53,59,0.68));">Limit Waktu Tersisa Upload Bukti Transfer</span>
                                <h6 class="countdown"></h6>
                            </div>
                            <div class="col-sm-12">
                                <span style="font-size: 0.857143rem;line-height: 18px;color: var(--N700,rgba(49,53,59,0.68));">Transfer Ke Bank</span>
                                <h6>{{$dataMenungguPembayaran->nama_jenis_pembayaran}}</h6>
                            </div>
                            <div class="col-sm-12">
                                <span style="font-size: 0.857143rem;line-height: 18px;color: var(--N700,rgba(49,53,59,0.68));">Atas Nama</span>
                                <h6>{{$dataMenungguPembayaran->atas_nama}}</h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-sm-10">
                                    <span style="font-size: 0.857143rem;line-height: 18px;color: var(--N700,rgba(49,53,59,0.68));">Nomor Rekening</span>
                                    <h6>{{$dataMenungguPembayaran->no_rekening}}</h6>
                                </div>
                                <div class="col-sm-2" style="display: flex;align-items: center;">
                                    <span style="font-size: 0.857143rem;line-height: 18px;color: var(--N700,rgba(49,53,59,0.68));">Salin <i class="icofont icofont-ui-copy"></i></span>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-sm-10 mb-3">
                                    <span style="font-size: 0.857143rem;line-height: 18px;color: var(--N700,rgba(49,53,59,0.68));">Total Bayar</span>
                                    <h6>Rp{{$dataMenungguPembayaran->total_biaya}}</h6>
                                </div>
                                <div class="col-sm-2 mb-3" style="display: flex;align-items: center;">
                                    <span style="font-size: 0.857143rem;line-height: 18px;color: var(--N700,rgba(49,53,59,0.68));">Salin <i class="icofont icofont-ui-copy"></i></span>
                                </div>
                            </div>
                            <hr/>
                            <form method="POST" id="simpan-bukti-pembayaran" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label class="mb-2">Foto Bukti Pembayaran</label>
                                    <div class="col-sm-12 img-up">
                                        <div class="image-preview" id="image-preview-foto-bukti-bayar"></div>
                                        <label class="btn btn-primary">Pilih
                                            <input type="file" class="upload-file img" name="foto_bukti_bayar" value="foto_bukti_bayar" style="width: 0px;height: 0px;overflow: hidden;">
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-square btn-outline-light txt-dark" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="update-court-button" class="btn btn-square btn-outline-secondary" onclick="simpanBuktiTransfer(1)">Save</button>
                    <!-- <button type="button" id="pay-button" class="btn btn-square btn-outline-secondary">Save</button> -->
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@section('plugin_js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{url('/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>

{{-- <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
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
</script> --}}

<script>

    $(".img-add").click(function(){
        $(this).closest(".row").find('.img-add').before('<div class="col-sm-2 imgUp"><div class="imagePreview"></div><label class="btn btn-primary">Upload<input type="file" class="uploadFile img" value="Upload Photo" style="width:0px;height:0px;overflow:hidden;"></label><i class="fa fa-times del"></i></div>');
    });
    $(document).on("click", "i.del" , function() {
    // 	to remove card
        $(this).parent().remove();
    // to clear image
    // $(this).parent().find('.imagePreview').css("background-image","url('')");
    });

    $(document).on("change",".upload-file", function(){
        var uploadFile = $(this);
        var files = !!this.files ? this.files : [];

        $('#image-preview-foto-bukti-bayar').removeAttr('style');
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
            if (/^image/.test( files[0].type)){ // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file

                reader.onloadend = function(){ // set image data as background of div
                    //alert(uploadFile.closest(".upimage").find('.imagePreview').length);
                uploadFile.closest(".img-up").find('.image-preview').css("background-image", "url("+this.result+")");
            }
        }
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

    function getPembayaranDetail(){
        $.ajax({
            type: "GET",
            url: "{{route('penyewaLapangan.getPembayaranDetail')}}",
            datatype : "json",
            success: function(orderData){
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

                    console.log(bookingArr)
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
                    $('#modal-booking-counting').modal('show');
                }


                // $("#nama-penyewa").val(data.nama_penyewa);
                // $("#tanggal-penyewaan").val(data.tgl_penyewaan);
                // $("#pilihan-court-penyewa").val(data.total_court);
                // $("#pilihan-waktu-penyewa").val(data.waktu_book);
                // $("#total-penyewaan").val('Rp'+data.total_biaya);

                // $("#data-detail-penyewaan").modal('show');
            },
            error: function(data){
            }
        })
    }

    function batalkanPemesanan(pembayaranId){
		swal.fire({
			title: "Batalkan Penyewaan?",
			text: "Penyewaan akan dibatalkan!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn-danger",
			confirmButtonText: "Yakin",
            cancelButtonText: "Batal",
            closeOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST",
                    url: "{{route('penyewaLapangan.batalkanPembayaran')}}",
                    datatype : "json",
                    data:{
                        "_token": "{{ csrf_token() }}",
                        "pembayaran_id": pembayaranId
                    },
                    success: function(data){

                    },
                    error: function(data){
                        swal.fire({title:"Terjadi Kesalahan Batalkan Pesanan!", icon:"error"});
                    }
                });
            }
		}).then((result) => {
            if(result.value){
                swal.fire({title:"Penyewaan Berhasil Dibatalkan!", icon:"success"})
                .then(function(){
                    window.location.href = "";
                });
            }
        });
    }

    function simpanBuktiTransfer(pembayaranId){

		swal.fire({
			title: "Simpan Bukti Transfer?",
			text: "Bukti transfer penyewaan lapangan akan disimpan!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Yakin",
            cancelButtonText: "Batal",
            closeOnConfirm: true,
            preConfirm: (login) => {
                var formData = new FormData($("#simpan-bukti-pembayaran").get(0));
                formData.append("pembayaran_id", pembayaranId)
                return $.ajax({
                    type: "POST",
                    url: "{{route('penyewaLapangan.simpanBuktiPembayaran')}}",
                    datatype : "json",
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
                    success: function(data){

                    },
                    error: function(data){
                        console.log(data)
                        if(data.responseJSON.error_bukti_trx.trim()){
                            $('#image-preview-foto-bukti-bayar').css({"border": "1px solid #f00"});
                        }

                        swal.fire({title:"Gagal Menyimpan Bukti Transfer!", icon:"error"});
                    }
                });
            }
		}).then((result) => {
            if(result.value){
                swal.fire({title:"Bukti Transfer Berhasil Disimpan!", icon:"success"})
                .then(function(){
                    window.location.href = "{{route('penyewaLapangan.riwayatPenyewaan')}}";
                });
            }
        });
    }

    @if(isset($dataMenungguPembayaran))
        console.log("{{$limitWaktuUploadBuktiTrx}}")
        var countDownDate = new Date("{{$limitWaktuUploadBuktiTrx}}").getTime();
        var interval = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            seconds = (seconds < 0) ? 59 : seconds;
            seconds = (seconds < 10) ? '0' + seconds : seconds;
            // console.log(minutes +":"+ seconds)
            $('.countdown').html(minutes + ':' + seconds);
            if (distance < 0) {
                clearInterval(interval);
                $('.countdown').html("EXPIRED");
            }
        }, 1000);
    @endif
</script>
@endsection
