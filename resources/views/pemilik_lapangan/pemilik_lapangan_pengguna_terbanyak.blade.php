@extends('layouts.app')

@section('title', 'Riwayat Penyewaan')

@section('plugin_css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{url('/assets/css/datatables.css')}}">
<style>
#chart-container {
  position: relative;
  height: 100vh;
  overflow: hidden;
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
                        <h3>Riwayat Pengguna Terbanyak</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="" data-bs-original-title="" title="">Home</a></li>
                            <li class="breadcrumb-item active">Riwayat Pengguna Terbanyak</li>
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
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div id="chart-container"></div>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript" src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script type="text/javascript" src="{{url('/assets/js/chart/echarts/echarts.min.js')}}"></script>

<script type="text/javascript">
    $.ajax({
        type: "POST",
        url: "{{route('pemilikLapangan.getDataRiwayatPenggunaBookingTerbanyakPemilikLapangan')}}",
        data: {"_token": "{{ csrf_token() }}"},
        success: function(data) {
            console.log(data)
            var dom = document.getElementById('chart-container');
            var myChart = echarts.init(dom, null, {
            renderer: 'canvas',
            useDirtyRect: false
            });
            var app = {};

            var option;

            option = {
            title: {
                text: 'Total Pengguna Booking Terbanyak',
                subtext: 'Per Tiga Bulan',
                left: 'center'
            },
            tooltip: {
                trigger: 'item',
                // valueFormatter: (nama_pengguna) => 'Total Booking ' + nama_pengguna.toFixed(0)
                formatter: function(d) {
                    console.log(d)
                    return '<div style="margin: 0px 0 0;line-height:1;">\
                            <div style="font-size:14px;color:#666;font-weight:400;line-height:1;">Total Pengguna Booking Terbanyak Per Bulan</div>\
                            <div style="margin: 10px 0 0;line-height:1;">\
                                <div style="margin: 0px 0 0;line-height:1;">\
                                    <span style="display:inline-block;margin-right:4px;border-radius:10px;width:10px;height:10px;background-color:#91cc75;"></span>\
                                    <span style="font-size:14px;color:#666;font-weight:400;margin-left:2px">Atas Nama '+d.data.nama_pengguna+'</span>\
                                    <span style="float:right;margin-left:20px;font-size:14px;color:#666;font-weight:900">Total Booking '+d.data.value+'</span>\
                                    <div style="clear:both"></div>\
                                </div>\
                                <div style="clear:both"></div>\
                            </div>\
                            <div style="clear:both"></div>\
                        </div>';
                }
            },
            //   legend: {
            //     orient: 'vertical',
            //     left: 'left'
            //   },
            series: [
                {
                name: 'Total Pemasukan Per Bulan',
                type: 'pie',
                radius: '50%',
                left: 'center',
                width: 800,
                data: data,
                // tooltip:{
                //     show:false
                // },
                label: {
                    alignTo: 'edge',
                    formatter: '{name|Total Booking {c}}\n{time|Bulan {b}}',
                    minMargin: 5,
                    edgeDistance: 10,
                    lineHeight: 15,
                    rich: {
                    time: {
                        fontSize: 10,
                        color: '#999'
                    }
                    }
                },
                labelLine: {
                    length: 15,
                    length2: 0,
                    maxSurfaceAngle: 80
                },
                labelLayout: function (params) {
                    const isLeft = params.labelRect.x < myChart.getWidth() / 2;
                    const points = params.labelLinePoints;
                    // Update the end point.
                    points[2][0] = isLeft
                    ? params.labelRect.x
                    : params.labelRect.x + params.labelRect.width;
                    return {
                    labelLinePoints: points
                    };
                },
                }
            ]
            };

            if (option && typeof option === 'object') {
            myChart.setOption(option);
            }

            window.addEventListener('resize', myChart.resize);
        }
    });
</script>
@endsection