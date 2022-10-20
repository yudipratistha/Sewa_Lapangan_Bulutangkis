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
                        <h3>Riwayat Total Pemasukan</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="" data-bs-original-title="" title="">Home</a></li>
                            <li class="breadcrumb-item active">Riwayat Total Pemasukan</li>
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
<script src="{{url('/assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{url('/assets/js/chart/echarts/echarts.min.js')}}"></script>

<script>
    const formatter = new Intl.NumberFormat('id', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    });

    $.ajax({
        type: "POST",
        url: "{{route('pemilikLapangan.getDataRiwayatTotalPemasukanPemilikLapangan')}}",
        data: {"_token": "{{ csrf_token() }}"},
        success: function(data) {
            var dataLabels = data.map(function(e) {
                return e.weekly_start_end;
            });
            var dataCharts = data.map(function(e) {
                return e.total_booking;
            });

            var dom = document.getElementById('chart-container');
            var myChart = echarts.init(dom, null, {
            renderer: 'canvas',
            useDirtyRect: false
            });
            var app = {};

            var option;

            option = {
                height: '75%',
                title: {
                    text: 'Riwayat Total Pemasukan',
                    left: 'center',
                },
                tooltip: {
                    trigger: 'axis',
                    formatter: function (params) {
                        console.log(params)
                        return '<div style="margin: 0px 0 0;line-height:1;">\
                                <div style="font-size:14px;color:#666;font-weight:400;line-height:1;">Total Pemasukan Per Bulan</div>\
                                <div style="margin: 10px 0 0;line-height:1;">\
                                    <div style="margin: 0px 0 0;line-height:1;">\
                                        '+params[0].marker+'\
                                        <span style="font-size:14px;color:#666;font-weight:400;margin-left:2px">Rentang: '+params[0].data.weekly_start_end+'</span><br/>\
                                        <span style="margin-top:10px; display: inline-block; margin-left:20px;font-size:14px;color:#666;font-weight:900">Total Pemasukan: '+formatter.format(params[0].data.value)+'</span>\
                                        <div style="clear:both"></div>\
                                    </div>\
                                    <div style="clear:both"></div>\
                                </div>\
                                <div style="clear:both"></div>\
                            </div>';
                    }
                },
                
                xAxis: {
                    type: "category",
                    boundaryGap: false,
                    axisLabel: {
                        rotate: 25,
                    },
                    name: 'Per Minggu',
                    // nameLocation: 'middle',
                    // nameGap: 70,
                    data: dataLabels,
                },
                yAxis: {
                    type: 'value',
                    // name: 'Total Booking Per Minggu',
                    // nameLocation: 'middle',
                    // nameGap: 60,
                    // splitNumber:4,
                    axisLabel: {
                        interval: 0,
                        formatter: function (value) {
                            if (Math.floor(value) === value) {
                                return formatter.format(value);
                            }
                        }
                    }
                },
                series: [
                    {
                        data: data,
                        type: 'line',
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