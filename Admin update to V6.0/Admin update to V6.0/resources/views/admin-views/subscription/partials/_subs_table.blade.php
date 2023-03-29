
@foreach ($restaurants as $key => $dm)
    <tr>
        <td>{{ $key + $restaurants->firstItem() }}</td>
        <td>
            <a href="{{ route('admin.restaurant.view', $dm->restaurant['id']) }}" alt="view restaurant" class="table-rest-info">
                <img onerror="this.src='{{ asset('public/assets/admin/img/100x100/food-default-image.png') }}'"
                    src="{{ asset('storage/app/public/restaurant') }}/{{ $dm->restaurant['logo'] }}">
                <div class="info">
                    <span class="d-block text-body">
                        {{ Str::limit($dm->restaurant->name, 20, '...') }}<br>
                        <!-- Rating -->
                        <span class="rating">
                            @php($restaurant_rating = $dm->restaurant['rating'] == null ? 0 : array_sum($dm->restaurant['rating']) / 5)
                            <i class="tio-star"></i> {{ $restaurant_rating }}
                        </span>
                        <!-- Rating -->
                    </span>
                </div>
            </a>
        </td>
        <td>
            <span class="d-block owner--name text-center">
                {{ $dm->package->package_name }}
            </span>

        </td>
        <td>
            {{ \App\CentralLogics\Helpers::format_currency($dm->package->price) }}

        </td>
        <td>
            {{ $dm->expiry_date->format('d-M-Y') }}
        </td>
        <td>
            @if ($dm->restaurant->vendor->status == 1)
                @if ($dm->status == 1)
                    <span class="badge badge-soft-success">{{ translate('messages.active') }}</span>
                @else
                <span class="badge badge-soft-danger">{{ translate('messages.Expired') }}</span>
                @endif
            @else
                <span class="badge badge-soft-danger">{{ translate('messages.inactive_vendor') }}</span>

            @endif
        </td>
        <td>
            <div class="btn--container justify-content-center">
                <a class="btn btn-sm btn--warning btn-outline-warning action-btn"
                    href="{{ route('admin.restaurant.view', ['restaurant' => $dm->restaurant_id, 'tab' => 'subscriptions']) }}"
                    title="{{ translate('messages.view') }} {{ translate('messages.restaurant') }}"><i
                        class="tio-invisible"></i>
                </a>
            </div>

        </td>
    </tr>
@endforeach
