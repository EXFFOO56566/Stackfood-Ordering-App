<div class="w-410px initial-38-1 col-md-12 pr-0">
    @if ($order->restaurant)
    <div class="text-center pt-4 mb-3">
        <h2 class="text-break lh-1">{{$order->restaurant->name}}</h2>
        <h5 class="text-break initial-44">
            {{$order->restaurant->address}}
        </h5>
        <h5 class="initial-45">
            {{translate('messages.phone')}} : {{$order->restaurant->phone}}
        </h5>
        @if($order->restaurant->gst_status)
        <h5 class="initial-46">
            {{translate('messages.Gst No')}} : {{$order->restaurant->gst_code}}
        </h5>
        @endif
    </div>
    @endif


    <span class="initial-38-7">---------------------------------------------------------------------------------</span>
    <div class="row mt-3">
        <div class="col-6">
            <h5>{{translate('messages.Order ID')}} : {{$order['id']}}</h5>
        </div>
        <div class="col-6">
            <h5 class="font-light">
                {{date('d/M/Y '.config('timeformat'),strtotime($order['created_at']))}}
            </h5>
        </div>
        @if($order->customer)
        <div class="col-12 text-break">
            <h5>
                {{translate('messages.Customer Name')}} : {{$order->customer['f_name'].' '.$order->customer['l_name']}}
            </h5>
            <h5>
                {{translate('messages.phone')}} : {{$order->customer['phone']}}
            </h5>
            <h5 class="text-break">
                {{translate('messages.Address')}} : {{isset($order->delivery_address)?json_decode($order->delivery_address, true)['address']:''}}
            </h5>
        </div>
        @endif
    </div>
    <h5 class="text-uppercase"></h5>
    <span class="initial-38-7">---------------------------------------------------------------------------------</span>
    <table class="table table-bordered mt-3">
        <thead>
        <tr>
            <th class="w-10p">{{translate('messages.qty')}}</th>
            <th class="">{{translate('messages.description')}}</th>
            <th class="">{{translate('messages.Price')}}</th>
        </tr>
        </thead>

        <tbody>
        @php($sub_total=0)
        @php($total_tax=0)
        @php($total_dis_on_pro=0)
        @php($add_ons_cost=0)
        @foreach($order->details as $detail)
            @if($detail->food)
                <tr>
                    <td class="">
                        {{$detail['quantity']}}
                    </td>
                    <td class="text-break">
                        {{$detail->food['name']}} <br>
                        @if (count(json_decode($detail['variation'], true)) > 0)
                            <strong><u>{{ translate('messages.variation') }} : </u></strong>
                            @foreach(json_decode($detail['variation'],true) as $variation)
                            @if(isset($variation["price"]))

                                @break
                                @else
                                    @if (isset($variation['values']))
                                    <span class="d-block text-capitalize">
                                            <strong>
                                        {{  $variation['name']}} -
                                            </strong>
                                    </span>
                                        @foreach ($variation['values'] as $value)
                                        <span class="d-block text-capitalize">
                                            &nbsp; {{ $value['label']}} :
                                            <strong>{{\App\CentralLogics\Helpers::format_currency( $value['optionPrice'])}}</strong>
                                            </span>
                                        @endforeach
                                    @endif
                                @endif
                                        @endforeach
                        @else
                        <div class="font-size-sm text-body">
                            <span>{{'Price'}} :  </span>
                            <span class="font-weight-bold">{{\App\CentralLogics\Helpers::format_currency($detail->price)}}</span>
                        </div>
                        @endif

                        @foreach(json_decode($detail['add_ons'],true) as $key2 =>$addon)
                            @if($key2==0)<strong><u>{{translate('messages.addon')}} : </u></strong>@endif
                            <div class="font-size-sm text-body">
                                <span class="text-break">{{$addon['name']}} :  </span>
                                <span class="font-weight-bold">
                                    {{$addon['quantity']}} x {{\App\CentralLogics\Helpers::format_currency($addon['price'])}}
                                </span>
                            </div>
                            @php($add_ons_cost+=$addon['price']*$addon['quantity'])
                        @endforeach
                    </td>
                    <td class="w-28p">
                        @php($amount=($detail['price'])*$detail['quantity'])
                        {{\App\CentralLogics\Helpers::format_currency($amount)}}
                    </td>
                </tr>
                @php($sub_total+=$amount)
                @php($total_tax+=$detail['tax_amount']*$detail['quantity'])

            @elseif($detail->campaign)
                <tr>
                    <td class="">
                        {{$detail['quantity']}}
                    </td>
                    <td class="text-break">
                        {{$detail->campaign['title']}} <br>
                        @if (count(json_decode($detail['variation'], true)) > 0)
                        <strong><u>{{ translate('messages.variation') }} : </u></strong>
                        @foreach(json_decode($detail['variation'],true) as $variation)
                        @if(isset($variation["price"]))

                            @break
                            @else
                                @if (isset($variation['values']))
                                <span class="d-block text-capitalize">
                                        <strong>
                                    {{  $variation['name']}} -
                                        </strong>
                                </span>
                                    @foreach ($variation['values'] as $value)
                                    <span class="d-block text-capitalize">
                                          &nbsp; {{ $value['label']}} :
                                        <strong>{{\App\CentralLogics\Helpers::format_currency( $value['optionPrice'])}}</strong>
                                        </span>
                                    @endforeach
                                @endif
                            @endif
                        @endforeach
                        @else
                        <div class="font-size-sm text-body">
                            <span>{{'Price'}} :  </span>
                            <span class="font-weight-bold">{{\App\CentralLogics\Helpers::format_currency($detail->price)}}</span>
                        </div>
                        @endif

                        @foreach(json_decode($detail['add_ons'],true) as $key2 =>$addon)
                            @if($key2==0)<strong><u>{{translate('messages.Addons')}} : </u></strong>@endif
                            <div class="font-size-sm text-body">
                                <span class="text-break">{{$addon['name']}} :  </span>
                                <span class="font-weight-bold">
                                    {{$addon['quantity']}} x {{\App\CentralLogics\Helpers::format_currency($addon['price'])}}
                                </span>
                            </div>
                            @php($add_ons_cost+=$addon['price']*$addon['quantity'])
                        @endforeach
                    </td>
                    <td class="w-28p">
                        @php($amount=($detail['price'])*$detail['quantity'])
                        {{\App\CentralLogics\Helpers::format_currency($amount)}}
                    </td>
                </tr>
                @php($sub_total+=$amount)
                @php($total_tax+=$detail['tax_amount']*$detail['quantity'])
            @endif
        @endforeach
        </tbody>
    </table>
    <span class="initial-38-7">---------------------------------------------------------------------------------</span>
    <div class="row justify-content-md-end">
        <div class="col-md-7 col-lg-7">
            <dl class="row text-right">
                <dt class="col-6 text-left">{{translate('messages.Items Price')}}:</dt>
                <dd class="col-6">{{\App\CentralLogics\Helpers::format_currency($sub_total)}}</dd>
                <dt class="col-6 text-left">{{translate('messages.Addon Cost')}}:</dt>
                <dd class="col-6">
                    {{\App\CentralLogics\Helpers::format_currency($add_ons_cost)}}
                    <hr>
                </dd>
                <dt class="col-6 text-left">{{translate('messages.subtotal')}}:</dt>
                <dd class="col-6">
                    {{\App\CentralLogics\Helpers::format_currency($sub_total+$total_tax+$add_ons_cost)}}</dd>
                <dt class="col-6 text-left">{{translate('messages.discount')}}:</dt>
                <dd class="col-6">
                    - {{\App\CentralLogics\Helpers::format_currency($order['restaurant_discount_amount'])}}</dd>
                <dt class="col-6 text-left">{{translate('messages.coupon_discount')}}:</dt>
                <dd class="col-6">
                    - {{\App\CentralLogics\Helpers::format_currency($order['coupon_discount_amount'])}}</dd>
                <dt class="col-6 text-left">{{translate('messages.vat/tax')}}:</dt>
                <dd class="col-6">+ {{\App\CentralLogics\Helpers::format_currency($order['total_tax_amount'])}}</dd>
                <dt class="col-6 text-left">{{translate('messages.Delivery Fee')}}:</dt>
                <dd class="col-6">
                    @php($del_c=$order['delivery_charge'])
                    {{\App\CentralLogics\Helpers::format_currency($del_c)}}
                    <hr>
                </dd>

                <dt class="col-6 fz-12px">{{translate('messages.Total')}}:</dt>
                <dd class="col-6 fz-12px">{{\App\CentralLogics\Helpers::format_currency($sub_total+$del_c+$order['total_tax_amount']+$add_ons_cost-$order['coupon_discount_amount'] - $order['restaurant_discount_amount'])}}</dd>
            </dl>
        </div>
    </div>
    <div class="d-flex flex-row justify-content-between border-top">
        <span>{{translate('messages.Paid by')}}: {{translate('messages.'.$order->payment_method)}}</span>
        <span>{{translate('messages.amount')}}: {{$order->adjusment}}</span>	<span>{{translate('messages.change')}}: {{$order->adjusment - $order->order_amount}}</span>
    </div>
    <span class="initial-38-7">---------------------------------------------------------------------------------</span>
    <h5 class="text-center pt-1">
        """{{translate('messages.THANK YOU')}}"""
    </h5>
    <span class="initial-38-7">---------------------------------------------------------------------------------</span>
</div>
