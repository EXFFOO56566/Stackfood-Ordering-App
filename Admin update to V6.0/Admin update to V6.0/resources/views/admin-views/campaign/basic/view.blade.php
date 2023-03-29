@extends('layouts.admin.app')

@section('title',translate('Campaign view'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-header-title text-break">{{$campaign->title}}</h1>
        </div>
        <!-- End Page Header -->
        <!-- Card -->
        <div class="card mb-3 mb-lg-5">
            <!-- Body -->
            <div class="card-body">
                <div class="row align-items-md-center">
                    <div class="col-md-4 mb-3 mb-md-0">
                            <img class="rounded initial-13" src="{{asset('storage/app/public/campaign')}}/{{$campaign->image}}"
                                 onerror="this.src='{{asset('/public/assets/admin/img/900x400/img1.png')}}'"
                                 alt="Image Description">
                    </div>
                    <div class="col-md-8">
                        <h4>{{translate('messages.short')}} {{translate('messages.description')}} : </h4>
                        <p>{{$campaign->description}}</p>

                        <form action="{{route('admin.campaign.addrestaurant',$campaign->id)}}" id="restaurant-add-form" method="POST">
                            @csrf
                            <!-- Search -->
                            <div class="d-flex flex-wrap g-2">
                                @php($allrestaurants=App\Models\Restaurant::all())
                                <div class="flex-grow-1">
                                    <select name="restaurant_id" id="restaurant_id" class="form-control h--45px" required>
                                        <option value="" selected disabled>{{ translate('Select Restaurant') }}</option>
                                        @forelse($allrestaurants as $restaurant)
                                        @if(!in_array($restaurant->id, $restaurant_ids))
                                            <option value="{{$restaurant->id}}" >{{$restaurant->name}}</option>
                                        @endif
                                        @empty
                                        <option value="">{{translate('no_data_found')}}</option>
                                        @endforelse
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn--primary">{{translate('messages.add')}} {{translate('messages.restaurant')}}</button>
                                </div>
                            </div>
                            <!-- End Search -->
                        </form>
                    </div>

                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Card -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <!-- Card -->
                <div class="card">
                    <!-- Table -->
                    <div class="card-header border-0 search--button-wrapper">
                        <h5 class="card-title"></h5>
                        <form action="javascript:" id="search-form">
                            <!-- Search -->
                            <div class="input--group input-group input-group-merge input-group-flush">
                                <input id="datatableSearch_" type="search" name="search" class="form-control"
                                        placeholder="{{translate('messages.search')}}" aria-label="Search" required>
                                <button type="submit" class="btn btn--secondary">
                                    <i class="tio-search"></i>
                                </button>

                            </div>
                            <!-- End Search -->
                        </form>
                    </div>
                    <div class="table-responsive datatable-custom">
                        <table id="columnSearchDatatable"
                               class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                 "order": [],
                                 "orderCellsTop": true
                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th>{{ translate('messages.sl') }}</th>
                                {{-- <th class="w-15p">{{translate('messages.logo')}}</th> --}}
                                <th class="w-15p">{{translate('messages.restaurant')}}</th>
                                <th>{{translate('messages.owner')}}</th>
                                <th>{{translate('messages.email')}}</th>
                                <th>{{translate('messages.phone')}}</th>
                                <th class="text-center">{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($restaurants as $key=>$dm)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>
                                    <a href="{{route('admin.restaurant.view', $dm->id)}}" alt="view restaurant" class="table-rest-info">
                                    <img
                                            onerror="this.src='{{asset('public/assets/admin/img/100x100/food-default-image.png')}}'"
                                            src="{{asset('storage/app/public/restaurant')}}/{{$dm['logo']}}">
                                        <div class="info">
                                            <span class="d-block text-body">
                                                {{Str::limit($dm->name,20,'...')}}<br>
                                                <!-- Rating -->
                                                <span class="rating">
                                                    @php($restaurant_rating = $dm['rating']==null ? 0 : (array_sum($dm['rating']))/5 )
                                                    <i class="tio-star"></i> {{$restaurant_rating}}
                                                </span>
                                                <!-- Rating -->
                                            </span>
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <span class="d-block owner--name text-center">
                                        {{$dm->vendor->f_name.' '.$dm->vendor->l_name}}
                                    </span>
                                    <span class="d-block font-size-sm text-center">
                                        {{$dm['phone']}}
                                    </span>
                                </td>
                                <td>
                                    {{$dm->zone?$dm->zone->name:translate('messages.zone').' '.translate('messages.deleted')}}
                                    {{--<span class="d-block font-size-sm">{{$banner['image']}}</span>--}}
                                </td>
                                <td>
                                    @if(isset($dm->vendor->status))
                                        @if($dm->vendor->status)
                                        <label class="toggle-switch toggle-switch-sm" for="stocksCheckbox{{$dm->id}}">
                                            <input type="checkbox" onclick="status_change_alert('{{route('admin.restaurant.status',[$dm->id,$dm->status?0:1])}}', '{{translate('messages.you_want_to_change_this_restaurant_status')}}', event)" class="toggle-switch-input" id="stocksCheckbox{{$dm->id}}" {{$dm->status?'checked':''}}>
                                            <span class="toggle-switch-label">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                        </label>
                                        @else
                                        <span class="badge badge-soft-danger">{{translate('messages.denied')}}</span>
                                        @endif
                                    @else
                                        <span class="badge badge-soft-danger">{{translate('messages.pending')}}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                        <a class="btn btn-sm btn--primary btn-outline-primary action-btn"
                                            href="{{route('admin.restaurant.edit',[$dm['id']])}}" title="{{translate('messages.edit')}} {{translate('messages.restaurant')}}"><i class="tio-edit"></i>
                                        </a>
                                        <a class="btn btn-sm btn--warning btn-outline-warning action-btn"
                                            href="{{route('admin.restaurant.view',[$dm['id']])}}" title="{{translate('messages.view')}} {{translate('messages.restaurant')}}"><i class="tio-invisible"></i>
                                        </a>
                                    </div>
                                    {{--<a class="btn btn-sm btn-white" href="javascript:"
                                    onclick="form_alert('vendor-{{$dm['id']}}','Want to remove this information ?')" title="{{translate('messages.delete')}} {{translate('messages.restaurant')}}"><i class="tio-delete-outlined text-danger"></i>
                                    </a>
                                    <form action="{{route('admin.vendor.delete',[$dm['id']])}}" method="post" id="vendor-{{$dm['id']}}">
                                        @csrf @method('delete')
                                    </form>--}}
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="page-area px-4 pb-3">
                            <div class="d-flex align-items-center justify-content-end">
                                <div>
                                    {!! $restaurants->links() !!}
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- End Table -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('keyup', function () {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function () {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script>

        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.restaurant.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
