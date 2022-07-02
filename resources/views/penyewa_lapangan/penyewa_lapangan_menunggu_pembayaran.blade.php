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
                                <div class="col-xl-12 box-col-8">
                                    <div class="card" style="border-radius: 8px; -webkit-box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);box-shadow: 0 4px 14px rgba(174, 197, 231, 0.5);">
                                        <div class="ticket-list">
                                            <div class="card-body">
                                                <div class="media b-b-light"><img class="img-40 img-fluid m-r-20" src="http://127.0.0.1:8000/storage/file/4/Lapangan-Bulutangkis-Pak-Tomo/foto_lapangan_1.jpg" alt="">
                                                    <div class="media-body">
                                                        <h6 class="f-w-600">
                                                            Lapangan Bulutangkis Pak Tomo
                                                            <span class="pull-right" style="padding-top: 8px;padding-bottom: 8px;margin-right: 5px;">Bayar Sebelum <i class="icofont icofont-clock-time" style="color: #ff8b00;"></i> <p style="display: inline-block; color: #ff8b00;">30 Juli 2022, 19:10</p></span> 
                                                        </h6>
                                                        <i class="fa fa-map-marker" style="margin-right: 5px;"></i><p style="display: inline-block;">Jalan Bung Tomo No. 12</p>
                                                    </div>
                                                </div>
                                                <div class="info-widget-card mt-4 mb-4">
                                                    <div class="row">
                                                        <div class="col-sm-3 b-r-light"><span>Transfer Ke Bank</span>
                                                            <h4>BRI</h4>
                                                        </div>
                                                        <div class="col-sm-5 b-r-light"><span>Nomor Rekening</span>
                                                            <h4>12314423311223</h4>
                                                        </div><div class="col-sm-4"><span>Total Bayar</span>
                                                            <h4>Rp90000</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="pull-right mb-3">
                                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#data-detail-penyewaan"></i>Lihat Detail</button>
                                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#data-upload-bukti-transfer"></i>Upload Bukti Transfer</button>
                                                    <button class="btn btn-light dropdown-toggle p-2" id="btnGroupDrop1" type="button" data-bs-toggle="dropdown" >. . .</button>
                                                    <div class="dropdown-menu p-0" aria-labelledby="btnGroupDrop1">
                                                        <button class="dropdown-item btn-outline-danger pt-2 pb-2" onclick='batalkanPemesanan(1)'><i class="icon-trash"></i></i> Batalkan Penyewaan</a>   
                                                    </div>
                                                    <!-- <button type="button" class="btn btn-outline-danger"></i>...</button> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
<div class="modal fade" id="data-detail-penyewaan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header text-center d-block">
                <h4 class="modal-title ">Detail Penyewaan</h3>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                    <div class="form-group">
                            <label>Nama</label>
                            <div class="input-group"><span class="input-group-text"><i class="icon-user"></i></span>
                                <input type="text" disabled class="form-control" id="nama-penyewa" name="nama_penyewa" placeholder="Nama..." value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Penyewaan</label>
                            <div class="input-group"><span class="input-group-text"><i class="icofont icofont-calendar"></i></span>
                                <input type="text" disabled class="form-control" id="tanggal-penyewaan" name="tanggal_penyewaan" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Court</label>
                            <div class="input-group"><span class="input-group-text"><i class="icofont icofont-badminton-birdie"></i></span>
                                <input type="text" disabled class="form-control" id="pilihan-court-penyewa" name="pilihan_court_penyewa" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Waktu</label>
                            <div class="input-group"><span class="input-group-text"><i class="icofont icofont-clock-time"></i></span>
                                <input type="text" disabled class="form-control" id="pilihan-waktu-penyewa" name="pilihan_waktu_penyewa" placeholder="Waktu..." value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Total</label>
                            <div class="input-group"><span class="input-group-text"><i class="icon-receipt"></i></span>
                                <input type="text" disabled class="form-control" id="total-penyewaan" name="total_penyewaan" placeholder="Total..." value="">
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
                            <h6>BRI</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-10">
                                <span style="font-size: 0.857143rem;line-height: 18px;color: var(--N700,rgba(49,53,59,0.68));">Nomor Rekening</span>
                                <h6>12314423311223</h6>
                            </div>
                            <div class="col-sm-2" style="display: flex;align-items: center;">
                                <span style="font-size: 0.857143rem;line-height: 18px;color: var(--N700,rgba(49,53,59,0.68));">Salin <i class="icofont icofont-ui-copy"></i></span>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-10 mb-3">
                                <span style="font-size: 0.857143rem;line-height: 18px;color: var(--N700,rgba(49,53,59,0.68));">Total Bayar</span>
                                <h6>Rp90000</h6>
                            </div>
                            <div class="col-sm-2 mb-3" style="display: flex;align-items: center;">
                                <span style="font-size: 0.857143rem;line-height: 18px;color: var(--N700,rgba(49,53,59,0.68));">Salin <i class="icofont icofont-ui-copy"></i></span>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <label class="mb-2">Foto Bukti Pembayaran</label>
                            <div class="col-sm-12 img-up">
                                <div class="image-preview" id="image-preview-foto-bukti-bayar"></div>
                                <label class="btn btn-primary">Browse
                                    <input type="file" class="upload-file img" name="foto_bukti_bayar" value="foto_bukti_bayar" style="width: 0px;height: 0px;overflow: hidden;">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-square btn-outline-light txt-dark" data-bs-dismiss="modal">Close</button>
                <button type="button" id="update-court-button" class="btn btn-square btn-outline-secondary" onclick="simpanBuktiTransfer(1)">Save</button>
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
                var info = { "_token": "{{ csrf_token() }}", "start": api.page.info().start, "length": api.page.info().length, "draw": api.page.info().draw, "filterTanggalStart" : filterDateStart, "filterTanggalEnd" : filterDateEnd };
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
                { "defaultContent": "", "orderable": false, "width": "10%" }
        ],
        order: [[ 0, "asc" ]],
        fixedColumns:{left: 1},
        initComplete:function( settings, json){
            // $("div.dataTables_length").append('&nbsp<span onclick="approveTicket()" class="btn btn-pill btn-outline-secondary btn-air-secondary btn-sm">Approve Ticket</span>');
            // $('#data-ergonomic_length').appendTo('#length-data-ergonomic');
            // $('#data-ergonomic_info').appendTo('#pagination-data-ergonomic');
            // $('#data-ergonomic_paginate').appendTo('#pagination-data-ergonomic');
            // $('#data-ergonomic tbody').on('click', "#edit-data-ergonomic", function() {
            //     let row = $(this).parents('tr')[0];
            //     console.log(table.row(row).data().ssp_time_id);
                
            //     $('#edit-body-data-ergonomic').append('<input type="hidden" id="ticket-id" name="ticket_id" value="'+table.row(row).data().ssp_ticket_id+'">\
            //         <input type="hidden" id="time-id" name="time_id" value="'+table.row(row).data().ssp_time_id+'">');

            //     Object.keys(table.row(row).data()).forEach(function(item, index) {
            //         if(index >= 6){
            //             $('#edit-body-data-ergonomic').append('\
            //                 <div class="form-group row" id="job-analyst-div">\
            //                     <label class="col-xl-3 col-sm-4 col-form-label">'+ucwords(item.replace(/_/g, " "))+'</label>\
            //                     <div class="col-xl-9 col-sm-8">\
            //                         <input type="text" class="form-control" id="'+item.replace(/_/g, "-")+'" name="'+item+'" placeholder="'+ucwords(item.replace(/_/g, " "))+'..." value="'+table.row(row).data()[item]+'">\
            //                     </div>\
            //                 </div>');
            //         }
            //     })
            //     $('#editDataErgonomic').modal('show');
            // });

            // $('#data-ergonomic tbody').on('click', "#delete-data-ergonomic", function() {
            //     let row = $(this).parents('tr')[0];
            //     deleteDataErgonomic(table.row(row).data().ssp_time_id);
            // });
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

    function batalkanPemesanan(court_id){
        link = "{{route('pemilikLapangan.updateCourtLapanganStatus', ':id')}}";
        link = link.replace(':id', court_id);
        
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
                    url: link,
                    datatype : "json", 
                    data: $("#editCourt").serialize() + "&court_id="+court_id,
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

    function simpanBuktiTransfer(court_id){
        link = "{{route('pemilikLapangan.updateCourtLapanganStatus', ':id')}}";
        link = link.replace(':id', court_id);
        
		swal.fire({
			title: "Simpan Bukti Transfer?",
			text: "Bukti transfer penyewaan lapangan akan disimpan!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Yakin",
            cancelButtonText: "Batal",
            closeOnConfirm: true,
            preConfirm: (login) => {
                return $.ajax({
                    type: "POST", 
                    url: link,
                    datatype : "json", 
                    data: $("#editCourt").serialize() + "&court_id="+court_id,
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

    var timer2 = "10:00";
    var interval = setInterval(function() {


    var timer = timer2.split(':');
    //by parsing integer, I avoid all extra string processing
    var minutes = parseInt(timer[0], 10);
    var seconds = parseInt(timer[1], 10);
    --seconds;
    minutes = (seconds < 0) ? --minutes : minutes;
    seconds = (seconds < 0) ? 59 : seconds;
    seconds = (seconds < 10) ? '0' + seconds : seconds;
    //minutes = (minutes < 10) ?  minutes : minutes;
    $('.countdown').html(minutes + ':' + seconds);
    if (minutes < 0) clearInterval(interval);
    //check if both minutes and seconds are 0
    if ((seconds <= 0) && (minutes <= 0)) clearInterval(interval);
    timer2 = minutes + ':' + seconds;
    }, 1000);

</script>
@endsection