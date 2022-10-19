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

    $.ajax({
        type: "POST",
        url: "{{route('pemilikLapangan.getDataRiwayatPenggunaBookingTerbanyakPemilikLapangan')}}",
        data: {"_token": "{{ csrf_token() }}"},
        success: function(data) {
            data.sort(dynamicSort("value"))

            var dataLabels = data.map(function(e) {
                return e.name;
            });

            var dom = document.getElementById('chart-container');
            var myChart = echarts.init(dom, null, {
            renderer: 'canvas',
            useDirtyRect: false
            });
            var app = {};

            var option;

            option = {
                height: '80%',
            title: {
                text: 'Riwayat Pengguna Terbanyak',
                left: 'center',
            },
            grid: { containLabel: true },
            xAxis: { 
                name: 'Total Booking', 
                nameLocation: 'middle',
                nameGap: 40, 
            },
            yAxis: { 
                type: 'category', 
                
                nameGap: 10,
                data: dataLabels,
            },
            visualMap: {
                show: false,
                min: 0,
                max: 100,
                // Map the score column to color
                dimension: 0,
                inRange: {
                color: ['#65B581', '#FFCE34', '#FD665F']
                }
            },
            series: [
                {
                type: 'bar',
                data: data,
                barMinWidth: 10,
                barMaxWidth: 70,
                label: {
                    show: true,
                    position: 'right',
                    formatter: 'Total Booking: {@amount}'
                }
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