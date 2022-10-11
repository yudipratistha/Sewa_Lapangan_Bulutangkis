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
                            <li class="breadcrumb-item"><a href="{{route('index')}}">Home</a></li>
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
                                                <span class="fw-bold" id="jenis-booking">Per Jam</span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="width: 145px;padding-left: 0px;padding-right: 0px;">Tanggal Booking <span class="pull-right">:&nbsp;</span></td>
                                            <th style="padding-left: 0px;"><span class="fw-bold" id="tgl-booking">-</span></th>
                                        </tr>
                                        <tr>
                                            <td scope="row" style="width: 145px;padding-left: 0px;padding-right: 0px;">Total <span class="pull-right">:&nbsp;</span></td>
                                            <th style="padding-left: 0px;">Rp<span class="fw-bold" id="total-harga">-</span></th>
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
                                    <button type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modal-metode-pembayaran" data-bs-original-title="" title="" class="btn btn-square btn-outline-blue">Pilih Pembayaran</button>
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
                                    @for ($court= 1; $court <= $dataLapangan->jumlah_court; $court++)
                                        <li class="nav-item"><a class="nav-link @if($court === 1) active @endif" id="top-home-danger" data-bs-toggle="tab" href="#court-{{$court}}" role="tab" aria-controls="top-homedanger" aria-selected="true"><i class="icofont icofont-badminton-birdie"></i>Court {{$court}}</a>
                                            <div class="material-border"></div>
                                        </li>
                                    @endfor
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tabbed-card">
                                <form id="check-book-time">
                                    @csrf
                                    <div class="tab-content" id="top-tabContentdanger">
                                        @for ($court= 1; $court <= $dataLapangan->jumlah_court; $court++)
                                            <div class="tab-pane fade @if($court === 1) active show @endif" id="court-{{$court}}" role="tabpanel" aria-labelledby="top-home-tab">
                                                <div class="table-responsive">
                                                    <table class="display datatables hover-table-court-profile" id="table-court-{{$court}}">
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
                                        @endfor
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

<!-- Modal Metode Pembayaran-->
<div class="modal fade" id="modal-metode-pembayaran" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    var jumlah_court = {!! json_encode($dataLapangan->jumlah_court) !!};
    
    var harga_per_jam = {!! json_encode($dataLapangan->harga_per_jam) !!};

    var date; 
    var availableDates = [];
    var orderData = {};
    var total_biaya = 0;

    for(let courtCount= 1; courtCount<= jumlah_court; courtCount++){
        $('#table-court-'+courtCount).DataTable({
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
    }
   
    $('input[type="checkbox"]').prop('checked', false);

    $('#tanggal').datepicker({
        dateFormat: 'dd-mm-yy',
        showOtherMonths: true,
        minDate: new Date(),
        autoclose: true,
        onSelect: function(dateText) {
            $('#tgl-booking').empty().append(dateText);
            date = dateText;

            $.ajax({
                url: "{{route('penyewaLapangan.getAllDataLapangan', $dataLapangan->lapangan_id)}}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "tanggal" : date
                },
                dataType: "json",
                success:function(data){
                    for(let courtCount= 1; courtCount<= jumlah_court; courtCount++){
                        $('#table-court-'+courtCount).DataTable().clear().draw();
                        $('#table-court-'+courtCount).DataTable().rows.add(data['court_'+courtCount]);
                        $('#table-court-'+courtCount).DataTable().columns.adjust().draw();
                        // 
                        // $('#table-court-'+courtCount).rows().nodes().to$().find('input[type="checkbox"]').each(function(){
                        //     
                        // });
                    }

                    $(document).on('draw.dt', function () {
                        if(orderData[date] !== undefined){
                            for(let index = 0; index < Object.keys(orderData).length; ++index){
                                for(let index2 = 0; index2 < orderData[date].length; ++index2){
                                    var orderDataArr = orderData[date][index2];
                                    if(Object.keys(orderData)[index] === date){
                                        $("input[value*='"+JSON.stringify(orderDataArr)+"']").prop('checked', true);
                                    }
                                }
                            }
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
            if(orderData[date] === undefined){
                orderData[date]= [];
            }

            // Object.assign(orderData[date], JSON.parse($(this).val()));
            orderData[date].push(JSON.parse($(this).val()));
            total_biaya += harga_per_jam;
        }else{
            var orderDataCancel = JSON.parse($(this).val());
            
            if (orderData[date].length === 1) {
                delete orderData[date];
            }
            if(orderData[date] !== undefined){
                for(let index = 0; index < Object.keys(orderData).length; ++index){
                    
                    for(let index2 = 0; index2 < orderData[date].length; ++index2){
                        var orderDataArr = orderData[date][index2];

                        if(Object.keys(orderData)[index] === date && orderDataArr.court === orderDataCancel.court && orderDataArr.jam === orderDataCancel.jam){
                            orderData[date].splice(index2, 1);
                        }
                    }
                }
            }
            total_biaya -= harga_per_jam;
        }

        $('#total-harga').empty().append(total_biaya);
        
        for(let courtCount= 1; courtCount<= jumlah_court; courtCount++){
            $('#table-court-'+courtCount).children().children().children().first().removeAttr('style');
            $('#table-court-'+courtCount).children('tbody').children().find("td:first").removeAttr('style');
            $('#table-court-'+courtCount).children('tbody').children('tr:last').find("td:first").removeAttr('style');
        }
    });
    
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true}).columns.adjust();
    });

    
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
                    url: "{{route('penyewaLapangan.storeBookingLapanganPerJam')}}",
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
                            
                            for(let courtCount= 1; courtCount<= jumlah_court; courtCount++){
                                $('#table-court-'+courtCount).children().children().children().first().css({'border-top': '1px solid red', 'border-left': '1px solid red', 'border-right': '1px solid red'});
                                $('#table-court-'+courtCount).children('tbody').children().find("td:first").css({'border-top': '1px solid red', 'border-left': '1px solid red', 'border-right': '1px solid red'});
                                $('#table-court-'+courtCount).children('tbody').children('tr:last').find("td:first").css({'border-left': '1px solid red', 'border-right': '1px solid red', 'border-bottom': '1px solid red'});
                            }
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
