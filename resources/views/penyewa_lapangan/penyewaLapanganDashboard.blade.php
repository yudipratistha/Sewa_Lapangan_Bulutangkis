@extends('layouts.app')

@section('title', 'Pemilik Lapangan Dashboard')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/date-picker.css')}}">
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
              <div class="card" style="margin-bottom: 10px;">
                <div class="card-body">
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
              </div>
              <div class="card">
                <div class="card-header">
                  <h5 class="pull-left">Dashboard Pemilik Lapangan</h5>
                </div>
                <div class="card-body">
                  <div class="tabbed-card">
                    <ul class="pull-right nav nav-tabs border-tab nav-success" id="top-tabdanger" role="tablist">
                      <li class="nav-item"><a class="nav-link active" id="top-home-danger" data-bs-toggle="tab" href="#top-homedanger" role="tab" aria-controls="top-homedanger" aria-selected="true"><i class="icofont icofont-badminton-birdie"></i>Court 1</a>
                        <div class="material-border"></div>
                      </li>
                      <li class="nav-item"><a class="nav-link" id="profile-top-danger" data-bs-toggle="tab" href="#top-profiledanger" role="tab" aria-controls="top-profiledanger" aria-selected="false"><i class="icofont icofont-badminton-birdie"></i>Court 2</a>
                        <div class="material-border"></div>
                      </li>
                      <li class="nav-item"><a class="nav-link" id="contact-top-danger" data-bs-toggle="tab" href="#top-contactdanger" role="tab" aria-controls="top-contactdanger" aria-selected="false"><i class="icofont icofont-badminton-birdie"></i>Court 3</a>
                        <div class="material-border"></div>
                      </li>
                    </ul>
                    <div class="tab-content" id="top-tabContentdanger">
                      <div class="tab-pane fade active show" id="top-homedanger" role="tabpanel" aria-labelledby="top-home-tab">
                        <div class="table-responsive">
                          <table class="display datatables" id="ajax-data-array">
                            <thead>
                              <tr>
                                <th>Jam</th>
                                <th>Penyewa</th>
                                <th>Edit Court</th>
                              </tr>
                            </thead>
                          </table>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="top-profiledanger" role="tabpanel" aria-labelledby="profile-top-tab">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
                      </div>
                      <div class="tab-pane fade" id="top-contactdanger" role="tabpanel" aria-labelledby="contact-top-tab">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
                      </div>
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
@endsection

@section('plugin_js')
<script src="{{url('/assets/js/datepicker/date-picker/datepicker.js')}}"></script>
<script src="{{url('/assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
<script src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{url('/assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>

<script>
  $("#ajax-data-array").dataTable({
    "columns": [
        { "orderable": true, "width": "10%" },
        null,
        { "orderable": false, "width": "13%" }
    ]
  });

  $('#tanggal').datepicker({
      language: 'en',
      dateFormat: 'dd-mm-yyyy',
      minDate: new Date() // Now can select only dates, which goes after today
  });
</script>
@endsection