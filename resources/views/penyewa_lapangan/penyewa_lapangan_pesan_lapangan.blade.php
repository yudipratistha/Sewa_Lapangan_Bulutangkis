@extends('layouts.app')

@section('title', 'Pemilik Lapangan Dashboard')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/dataTables.checkboxes.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/photoswipe.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/leaflet.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/leaflet-gesture-handling.min.css')}}">

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
                                            <th scope="row" style="width: 10%;padding-left: 0px;padding-right: 0px;">Nama Lapangan</th>
                                            <td style="padding-left: 0px;">: {{$dataLapangan->nama_lapangan}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row" style="width: 10%;padding-left: 0px;padding-right: 0px;">Tanggal Booking</th>
                                            <td style="padding-left: 0px;">: <span id="tgl-booking">-</span></td>
                                        </tr>
                                        <tr>
                                            <th scope="row" style="width: 10%;padding-left: 0px;padding-right: 0px;">Total</th>
                                            <td style="padding-left: 0px;">: Rp. <span id="total-harga">-</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr/>
                                <button type="button" onClick="pesanLapangan()" class="btn btn-square btn-outline-blue">Konfirmasi Sewa</button>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="margin-bottom: 10px;">
                        <div class="card-header">
                            <div class="mb-3 row g-3">
                                <label class="col-xl-1 col-sm-3 col-lg-1 col-form-label">Pilih Tanggal</label>
                                <div class="col-xl-3 col-sm-5 col-lg-7">
                                    <div class="input-group date">
                                        <input class="form-control digits" id="tanggal" name="tanggal" type="text" placeholder="dd-mm-yyyy" readonly>
                                        <div class="input-group-text"><i class="fa fa-calendar"> </i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tabbed-card">
                                <ul class="pull-right nav nav-tabs border-tab nav-success" id="top-tabdanger" role="tablist">
                                    @for ($court= 1; $court <= $dataLapangan->jumlah_court; $court++)
                                        <li class="nav-item"><a class="nav-link @if($court === 1) active @endif" id="top-home-danger" data-bs-toggle="tab" href="#court-{{$court}}" role="tab" aria-controls="top-homedanger" aria-selected="true"><i class="icofont icofont-badminton-birdie"></i>Court {{$court}}</a>
                                            <div class="material-border"></div>
                                        </li>
                                    @endfor
                                </ul>
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
@endsection

@section('plugin_js')
<script src="{{url('/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{url('/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>

<script>
    var jumlah_court = {!! json_encode($dataLapangan->jumlah_court) !!}
    var harga_per_jam = {!! json_encode($dataLapangan->harga_per_jam) !!}
    var date; 
    var total_biaya = 0;

    for(let courtCount= 1; courtCount<= jumlah_court; courtCount++){
        $('#table-court-'+courtCount).DataTable({
            "processing": true,
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
        language: 'en',
        dateFormat: 'dd-mm-yyyy',
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
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
            return $('#tanggal').trigger('change');
        }
    }).datepicker('dateFormat', 'dd-mm-yyyy').data('datepicker').selectDate(new Date());

    $('.table-responsive').on('change', 'input[type="checkbox"]', function() {
        if($(this).prop("checked") === true){
            total_biaya += harga_per_jam;
        }else{
            total_biaya -= harga_per_jam;
        }
        $('#total-harga').empty().append(total_biaya);
    });

    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true}).columns.adjust();
    });

    
    function pesanLapangan(){
		swal.fire({
			title: "Konfirmasi Sewa Lapangan?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Save",
            closeOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST", 
                    url: "{{route('penyewaLapangan.storeBookingLapangan')}}",
                    datatype : "json", 
                    data: $("#check-book-time").serialize() + "&tglBooking="+date + "&totalBiaya="+total_biaya,
                    success: function(data){
                        
                    },
                    error: function(data){
                        swal.fire({title:"Konfirmasi Sewa Lapangan Gagal Tersimpan!", icon:"error"});
                    }
                }); 
            } 
		}).then((result) => {
            if(result.value){
                swal.fire({title:"Konfirmasi Sewa Lapangan Berhasil Tersimpan!", text:"Sewa lapangan menunggu validasi admin", icon:"success"})
                .then(function(){ 
                    window.location.href = "";
                });
            }
        });
    }
</script>

@endsection
