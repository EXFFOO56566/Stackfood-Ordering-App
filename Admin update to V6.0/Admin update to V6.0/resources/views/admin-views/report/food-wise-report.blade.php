@extends('layouts.admin.app')

@section('title', translate('messages.food_wise_report'))

@push('css_or_js')
@endpush

@section('content')
{{-- {{ dd(request()->getQueryString()) }} --}}
    @php
    $from = session('from_date');
    $to = session('to_date');
    @endphp
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-filter-list"></i> {{ translate('messages.food_wise_report') }}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="card">
            <div class="card-body">
                <div class="report-card-inner">
                    <form action="{{ route('admin.report.set-date') }}" method="post">
                        @csrf
                        <div class="d-flex mb-2 flex-wrap justify-content-between align-items-center">
                            <div class="mx-1 mb-1">
                                <h4 class="form-label">
                                    {{ translate('Show Data by Date range') }}
                                </h4>
                            </div>
                            <div class="mx-1 mb-1">
                                <button type="submit" class="btn btn--primary btn-block">{{ translate('Show Data') }}</button>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <select name="zone_id" class="form-control js-select2-custom h--45px"
                                    onchange="set_zone_filter('{{ url()->full() }}',this.value)" id="zone_id">
                                    <option value="all">{{ translate('All Zones') }}</option>
                                    @foreach (\App\Models\Zone::orderBy('name')->get() as $z)
                                        <option value="{{ $z['id'] }}" {{ isset($zone) && $zone->id == $z['id'] ? 'selected' : '' }}>
                                            {{ $z['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <select name="restaurant_id" onchange="set_restaurant_filter('{{ url()->full() }}',this.value)"
                                    data-placeholder="{{ translate('messages.select') }} {{ translate('messages.restaurant') }}"
                                    class="js-data-example-ajax form-control h--45px">
                                    @if (isset($restaurant))
                                        <option value="{{ $restaurant->id }}" selected>{{ $restaurant->name }}</option>
                                    @else
                                        <option value="all" selected>{{ translate('messages.all') }} {{ translate('messages.restaurants') }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="floating-label" for="from_date">{{translate('start_date')}}</label>
                                    <input type="date" name="from" id="from_date"
                                        {{ session()->has('from_date') ? 'value=' . session('from_date') : '' }}
                                        class="form-control h--45px" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="floating-label" for="to_date">{{translate('end_date')}}</label>
                                    <input type="date" name="to" id="to_date"
                                        {{ session()->has('to_date') ? 'value=' . session('to_date') : '' }} class="form-control h--45px"
                                        required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Stats -->
        <!-- Card -->
        <div class="card mt-4">
            <!-- Header -->
            <div class="card-header py-2 border-0">
                <div class="search--button-wrapper">
                <h3 class="card-title">
                    {{ translate('Food Wise Report Table') }}
                    <span class="badge badge-soft-dark">{{ $foods ? $foods->total() : 0 }}</span>
                </h3>
                <form id="search-form">
                    @csrf
                    <!-- Search -->
                    <div class="input--group input-group input-group-merge input-group-flush">
                        <input id="datatableSearch" name="search" type="search" class="form-control"
                            placeholder="{{ translate('Search by name or restaurant...') }}"
                            aria-label="{{ translate('messages.search_here') }}">
                        <button type="submit" class="btn btn--secondary">
                            <i class="tio-search"></i>
                        </button>
                    </div>
                    <!-- End Search -->
                </form>
                <!-- Static Export Button -->
                <div class="hs-unfold ml-3">
                    <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle btn export-btn btn-outline-primary btn--primary font--sm" href="javascript:;"
                        data-hs-unfold-options='{
                            "target": "#usersExportDropdown",
                            "type": "css-animation"
                        }'>
                        <i class="tio-download-to mr-1"></i> {{translate('messages.export')}}
                    </a>

                    <div id="usersExportDropdown"
                            class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                        {{--<span class="dropdown-header">{{translate('messages.options')}}</span>
                        <a id="export-copy" class="dropdown-item" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{asset('public/assets/admin')}}/svg/illustrations/copy.svg"
                                    alt="Image Description">
                            {{translate('messages.copy')}}
                        </a>
                        <a id="export-print" class="dropdown-item" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{asset('public/assets/admin')}}/svg/illustrations/print.svg"
                                    alt="Image Description">
                            {{translate('messages.print')}}
                        </a>
                        <div class="dropdown-divider"></div>--}}
                        <span class="dropdown-header">{{translate('messages.download')}} {{translate('messages.options')}}</span>
                        {{-- <form action="{{route('admin.report.food-wise-report-export')}}" method="post">
                            @csrf
                            <input type="hidden" name="type" value="excel">
                            <button type="submit">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                alt="Image Description">
                                {{translate('messages.excel')}}
                            </button>
                        </form> --}}
                        <a id="export-excel" class="dropdown-item" href="{{route('admin.report.food-wise-report-export',['type'=>'excel',request()->getQueryString()])}}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{asset('public/assets/admin')}}/svg/components/excel.svg"
                                    alt="Image Description">
                            {{translate('messages.excel')}}
                        </a>
{{--
                        <form action="{{route('admin.report.food-wise-report-export')}}" method="post">
                            @csrf
                            <input type="hidden" name="type" value="csv">
                            <button type="submit">
                                <img class="avatar avatar-xss avatar-4by3 mr-2"
                                src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                alt="Image Description">
                                .{{translate('messages.csv')}}
                            </button>
                        </form> --}}
                        <a id="export-csv" class="dropdown-item" href="{{route('admin.report.food-wise-report-export', ['type'=>'csv',request()->getQueryString()])}}">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{asset('public/assets/admin')}}/svg/components/placeholder-csv-format.svg"
                                    alt="Image Description">
                            .{{translate('messages.csv')}}
                        </a>
                        {{--<a id="export-pdf" class="dropdown-item" href="javascript:;">
                            <img class="avatar avatar-xss avatar-4by3 mr-2"
                                    src="{{asset('public/assets/admin')}}/svg/components/pdf.svg"
                                    alt="Image Description">
                            {{translate('messages.pdf')}}
                        </a>--}}
                    </div>
                </div>
                <!-- Static Export Button -->
                </div>
            </div>
            <!-- End Header -->

            <!-- Table -->
            <div class="table-responsive datatable-custom" id="table-div">
                <table id="datatable" class="table table-borderless table-thead-bordered table-nowrap card-table"
                    data-hs-datatables-options='{
                            "columnDefs": [{
                                "targets": [],
                                "width": "5%",
                                "orderable": false
                            }],
                            "order": [],
                            "info": {
                            "totalQty": "#datatableWithPaginationInfoTotalQty"
                            },

                            "entries": "#datatableEntries",

                            "isResponsive": false,
                            "isShowPaging": false,
                            "paging":false
                        }'>
                    <thead class="thead-light">
                        <tr>
                            <th>{{ translate('messages.sl') }}</th>
                            <th>{{ translate('messages.name') }}</th>
                            <th>{{ translate('messages.restaurant') }}</th>
                            <th>{{ translate('messages.zone') }}</th>
                            <th>{{ translate('messages.order') }} {{ translate('messages.count') }}</th>
                        </tr>
                    </thead>

                    <tbody id="set-rows">
                        {{-- {{dd($foods)}} --}}

                        @foreach ($foods as $key => $food)
                        {{-- {{ dd($food) }} --}}
                            <tr>
                                <td>{{ $key + $foods->firstItem() }}</td>
                                <td>
                                    <a class="table-rest-info"
                                        href="{{ route('admin.food.view', [$food['id']]) }}">
                                        <img src="{{ asset('storage/app/public/product') }}/{{ $food['image'] }}"
                                            onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                                            alt="{{ $food->name }} image">
                                        <div class="info">
                                            <span class="d-block text-body">
                                                {{ $food['name'] }}<br/>
                                                <!-- Rating -->
                                                <span class="rating">
                                                    <i class="tio-star"></i> {{$food->avg_rating}}
                                                </span>
                                                <!-- Rating -->
                                            </span>
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    @if ($food->restaurant)
                                        <a href="#0" class="text--title text-hover-primary">
                                            {{ Str::limit($food->restaurant->name, 25, '...') }}
                                        </a>
                                    @else
                                        {{ translate('messages.restaurant') }} {{ translate('messages.deleted') }}
                                    @endif
                                </td>
                                <td>
                                    @if ($food->restaurant)
                                            {{ $food->restaurant->zone->name }}
                                    @else
                                        {{ translate('messages.not_found') }}
                                    @endif
                                </td>
                                <td>
                                    {{ $food->orders_count }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(count($foods) === 0)
            <div class="empty--data">
                <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                <h5>
                    {{translate('no_data_found')}}
                </h5>
            </div>
            @endif
            <div class="page-area px-4 pb-3">
                <div class="d-flex align-items-center justify-content-end">
                                        {{-- <div>
                        1-15 of 380
                    </div> --}}
                    <div>
                        {!! $foods->links() !!}
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script')
@endpush

@push('script_2')
    <script src="{{ asset('public/assets/admin') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('public/assets/admin') }}/vendor/chartjs-chart-matrix/dist/chartjs-chart-matrix.min.js"></script>
    <script src="{{ asset('public/assets/admin') }}/js/hs.chartjs-matrix.js"></script>

    <script>
        $(document).on('ready', function() {

            // INITIALIZATION OF FLATPICKR
            // =======================================================
            $('.js-flatpickr').each(function() {
                $.HSCore.components.HSFlatpickr.init($(this));
            });


            // INITIALIZATION OF NAV SCROLLER
            // =======================================================
            $('.js-nav-scroller').each(function() {
                new HsNavScroller($(this)).init()
            });


            // INITIALIZATION OF DATERANGEPICKER
            // =======================================================
            $('.js-daterangepicker').daterangepicker();

            $('.js-daterangepicker-times').daterangepicker({
                timePicker: true,
                startDate: moment().startOf('hour'),
                endDate: moment().startOf('hour').add(32, 'hour'),
                locale: {
                    format: 'M/DD hh:mm A'
                }
            });

            var start = moment();
            var end = moment();

            function cb(start, end) {
                $('#js-daterangepicker-predefined .js-daterangepicker-predefined-preview').html(start.format(
                    'MMM D') + ' - ' + end.format('MMM D, YYYY'));
            }

            $('#js-daterangepicker-predefined').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, cb);

            cb(start, end);


            // INITIALIZATION OF CHARTJS
            // =======================================================
            $('.js-chart').each(function() {
                $.HSCore.components.HSChartJS.init($(this));
            });

            var updatingChart = $.HSCore.components.HSChartJS.init($('#updatingData'));

            // Call when tab is clicked
            $('[data-toggle="chart"]').click(function(e) {
                let keyDataset = $(e.currentTarget).attr('data-datasets')

                // Update datasets for chart
                updatingChart.data.datasets.forEach(function(dataset, key) {
                    dataset.data = updatingChartDatasets[keyDataset][key];
                });
                updatingChart.update();
            })


            // INITIALIZATION OF MATRIX CHARTJS WITH CHARTJS MATRIX PLUGIN
            // =======================================================
            function generateHoursData() {
                var data = [];
                var dt = moment().subtract(365, 'days').startOf('day');
                var end = moment().startOf('day');
                while (dt <= end) {
                    data.push({
                        x: dt.format('YYYY-MM-DD'),
                        y: dt.format('e'),
                        d: dt.format('YYYY-MM-DD'),
                        v: Math.random() * 24
                    });
                    dt = dt.add(1, 'day');
                }
                return data;
            }

            $.HSCore.components.HSChartMatrixJS.init($('.js-chart-matrix'), {
                data: {
                    datasets: [{
                        label: 'Commits',
                        data: generateHoursData(),
                        width: function(ctx) {
                            var a = ctx.chart.chartArea;
                            return (a.right - a.left) / 70;
                        },
                        height: function(ctx) {
                            var a = ctx.chart.chartArea;
                            return (a.bottom - a.top) / 10;
                        }
                    }]
                },
                options: {
                    tooltips: {
                        callbacks: {
                            title: function() {
                                return '';
                            },
                            label: function(item, data) {
                                var v = data.datasets[item.datasetIndex].data[item.index];

                                if (v.v.toFixed() > 0) {
                                    return '<span class="font-weight-bold">' + v.v.toFixed() +
                                        ' hours</span> on ' + v.d;
                                } else {
                                    return '<span class="font-weight-bold">No time</span> on ' + v.d;
                                }
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            position: 'bottom',
                            type: 'time',
                            offset: true,
                            time: {
                                unit: 'week',
                                round: 'week',
                                displayFormats: {
                                    week: 'MMM'
                                }
                            },
                            ticks: {
                                "labelOffset": 20,
                                "maxRotation": 0,
                                "minRotation": 0,
                                "fontSize": 12,
                                "fontColor": "rgba(22, 52, 90, 0.5)",
                                "maxTicksLimit": 12,
                            },
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            type: 'time',
                            offset: true,
                            time: {
                                unit: 'day',
                                parser: 'e',
                                displayFormats: {
                                    day: 'ddd'
                                }
                            },
                            ticks: {
                                "fontSize": 12,
                                "fontColor": "rgba(22, 52, 90, 0.5)",
                                "maxTicksLimit": 2,
                            },
                            gridLines: {
                                display: false
                            }
                        }]
                    }
                }
            });


            // INITIALIZATION OF CLIPBOARD
            // =======================================================
            $('.js-clipboard').each(function() {
                var clipboard = $.HSCore.components.HSClipboard.init(this);
            });


            // INITIALIZATION OF CIRCLES
            // =======================================================
            $('.js-circle').each(function() {
                var circle = $.HSCore.components.HSCircles.init($(this));
            });

            $('.js-data-example-ajax').select2({
                ajax: {
                    url: '{{ url('/') }}/admin/restaurant/get-restaurants',
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            // all:true,
                            @if (isset($zone))
                                zone_ids: [{{ $zone->id }}],
                            @endif
                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    __port: function(params, success, failure) {
                        var $request = $.ajax(params);

                        $request.then(success);
                        $request.fail(failure);

                        return $request;
                    }
                }
            });
        });
    </script>

    <script>
        $('#from_date,#to_date').change(function() {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('Invalid date range!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }
        })

        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.report.food-wise-report-search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
