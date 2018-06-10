@extends('app')

@section('content')
<div>
    Сумма чека: {{money_format("%.2n", $receipt->totalSum/100)}}
</div>
@if (count($receipt->items) > 0)
    <a class="btn btn-primary" href="{{action('CheckController@store')}}">Сохранить</a>
    <div class="panel panel-default">
        <div class="panel-heading">
            Checks
        </div>

        <table id="table" class="table table-striped table-bordered" style="width:100%">
            <thead>
            <th>
                Name
            </th>
            <th>
                Price
            </th>
            <th>
                Quantity
            </th>
            <th>
                Sum
            </th>
            </thead>
            <tbody>
            @foreach ($receipt->items as $item)
                <tr>
                    <td>
                        {{$item->name}}
                    </td>
                    <td>
                        {{money_format("%.2n", $item->price/100)}}
                    </td>
                    <td>
                        {{$item->quantity}}
                    </td>
                    <td>
                        {{money_format("%.2n", $item->sum/100)}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="panel-heading">
        No items
    </div>
@endif
@endsection