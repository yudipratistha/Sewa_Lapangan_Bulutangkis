@extends('layouts.app')

@section('title', 'Pemilik Lapangan Dashboard')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/photoswipe.css')}}">

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
                        <div class="card-header">
                            <div class="mb-3 row g-3">
                                <label class="col-xl-1 col-sm-3 col-lg-1 col-form-label">Pilih Tanggal</label>
                                <div class="col-xl-3 col-sm-5 col-lg-7">
                                    <div class="input-group date">
                                        <input class="form-control digits" id="tanggal" name="tanggal" type="text" data-bs-original-title="" title="">
                                        <div class="input-group-text"><i class="fa fa-calendar"> </i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tabbed-card">
                                <ul class="pull-right nav nav-tabs border-tab nav-success" id="top-tabdanger" role="tablist">
                                @for ($court= 1; $court <= $dataLapangan[0]->jumlah_court; $court++)
                                    <li class="nav-item"><a class="nav-link @if($court === 1) active @endif" id="top-home-danger" data-bs-toggle="tab" href="#court-{{$court}}" role="tab" aria-controls="top-homedanger" aria-selected="true"><i class="icofont icofont-badminton-birdie"></i>Court {{$court}}</a>
                                        <div class="material-border"></div>
                                    </li>
                                @endfor
                                </ul>
                                
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
                                        <option value="Available">Tersedia</option>
                                        <option value="Unavailable">Tidak Tersedia</option>
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
            <div class="modal-header">
                <h3 class="modal-title">Data Profil Penyewa</h3>
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
                        <div class="form-group">
                            <label>Foto Bukti Pembayaran</label>
                            <div class="gallery my-gallery card-body" itemscope="">
                                <figure class="col-md-12" itemprop="associatedMedia" itemscope="">
                                    <a href="{{url('/assets/images/buktibayar/bni-5.jpg')}}" itemprop="contentUrl" data-size="1600x950">
                                        <img class="img-thumbnail" src="{{url('/assets/images/buktibayar/bni-5.jpg')}}" itemprop="thumbnail" alt="Image description">
                                    </a>
                                    <figcaption itemprop="caption description">Image caption  1</figcaption>
                                </figure>
                            </div>
                        </div>
                        <div class="form-group" id="update-status-pembayaran-div">
                            <label>Update Status Pembayaran</label>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-square btn-outline-light txt-dark" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('plugin_js')
<script src="{{url('/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{url('/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-time-picker/moment.min.js')}}"></script>
<script src="{{url('/assets/js/photoswipe/photoswipe.min.js')}}"></script>
<script src="{{url('/assets/js/photoswipe/photoswipe-ui-default.min.js')}}"></script>
<script src="{{url('/assets/js/photoswipe/photoswipe.js')}}"></script>

<script>
    var jumlah_court = {!! json_encode($dataLapangan[0]->jumlah_court) !!}

    for (let court= 1; court<= jumlah_court; court++){
        $("#table-court-"+court).dataTable({
            "columns": [
                { "orderable": true, "width": "10%" },
                null,
                { "orderable": false, "width": "16%" },
                { "orderable": false, "width": "13%" },
                
            ],
        });

    }

    $('body').on('hidden.bs.modal', '.modal', function () {
        $('#data-profil-penyewa-modal').find('.modal-footer').children('button').slice(-2).remove();
    });

    var date; 

    // for(let courtCount= 1; courtCount<= jumlah_court; courtCount++){
    //     $('#table-court-'+courtCount).DataTable({
    //         "processing": true,
    //         bFilter: false,
    //         dom: 'tip',
    //         order: [1, "asc"],
    //         columns: [
    //             { "orderable": false, "width": "5%" },
    //             { "orderable": true, "width": "10%" },
    //             null
    //         ]
    //     });
    // }

    $('#tanggal').datepicker({
        language: 'en',
        dateFormat: 'dd-mm-yyyy',
        minDate: new Date(),
        autoclose: true,
        onSelect: function(dateText) {
            $('#tgl-booking').empty().append(dateText);
            date = dateText;
            $.ajax({
                url: "{{route('pemilikLapangan.getDataLapanganPemilik', $dataLapangan[0]->lapangan_id)}}",
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
                var jamBooking= '';
                var totalCourt= '';

                data.forEach(function(item, index){
                    var tglBooking = item.tgl_booking.split('-');
                    var jamMulai = item.jam_mulai.split(':');
                    var jamSelesai = item.jam_selesai.split(':');
                    var punctuation= '';
                    
                    if(data.length > index+1 && data.length === 2){
                        punctuation= ' & ';
                    }else if(data.length >= index+1 && data.length-3 !== index-1 && data.length !== index+1){
                        punctuation= ', ';
                    }else if(data.length >= 2 && data.length-3 === index-1){
                        punctuation= ' & ';
                    }
                    
                    jamBooking += jamMulai[0]+":"+jamMulai[1] +" - "+ jamSelesai[0]+":"+jamSelesai[1] + punctuation;
                    totalCourt += item.court;
                    
                    $('#nama-penyewa').val(item.name);
                    $('#tanggal-penyewaan').val(tglBooking[2]+"-"+tglBooking[1]+"-"+tglBooking[0]);
                    $('#total-penyewaan').val('Rp'+item.total_biaya);
                    $('#id-pengguna-penyewa').val(item.id);
                });
                
                totalCourt = totalCourt.replace(/(.)\1+/g, '$1')

                $('#pilihan-court-penyewa').val(totalCourt.match(/\d/g).join(", ").replace(/,([^,]*)$/, ' &$1'));
                $('#pilihan-waktu-penyewa').val(jamBooking);

                console.log(data[0].status_pembayaran)
                if(data[0].status_pembayaran === 'DP' || data[0].status_pembayaran === 'Lunas'){
                    $("#update-status-pembayaran").val(data[0].status_pembayaran).change();
                }
                $('#data-profil-penyewa-modal').find('.modal-footer').children('button').after('\
                    <button type="button" onclick="tolakPenyewaan('+data[0].pembayaran_id+')" class="btn btn-square btn-outline-warning">Tolak</button>\
                    <button type="button" onclick="terimaPenyewaan('+data[0].pembayaran_id+')" class="btn btn-square btn-outline-primary">Terima</button>'
                )
                
                $('#data-profil-penyewa-modal').modal('show');
            },
            error: function(data){
                console.log("asdsad", data)
            }
        });
    }

    function editCourt(idLapangan, court, waktuLapangan){
        link = "{{route('pemilikLapangan.statusCourtLapanganStatus', [':idLapangan',':court'])}}";
        link = link.replace(":idLapangan", idLapangan);
        link = link.replace(":court", court);
        $.ajax({
            url: link,
            method: "GET",
            dataType: 'json',
            success: function(data){
                data.forEach(function(item, index){
                    var jamStatusBerlaku = item.jam_status_berlaku_dari.split(':');
                    var waktuLapanganSplit = waktuLapangan.split(' - ');

                    if(waktuLapanganSplit[0] === jamStatusBerlaku[0]+":"+jamStatusBerlaku[1]){
                        $('#edit-court-status').find('option').removeAttr('selected');
                        $('#edit-court-status').find('option[value="'+item.status+'"]').prop('selected',true);
                        $('#edit-court-alasan').val(item.detail_status);
                        $('#update-court-button').attr("onclick", "updateCourt("+item.id+")")
                    }
                });
                $('#edit-court-modal').modal('show');
            },
            error: function(data){
                console.log("asdsad", data);
            }
        });
    }

    function updateCourt(court_id){
        link = "{{route('pemilikLapangan.updateCourtLapanganStatus', ':id')}}";
        link = link.replace(':id', court_id);
        
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
                title: "Terima Pesanan?",
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
                        data: {'pembayaranId':pembayaranId, "_token": "{{ csrf_token() }}"},
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
        
    }
</script>

@endsection
