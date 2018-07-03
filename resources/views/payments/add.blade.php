@extends('app')
@section('content')
    <div class="container p-3">
        <form action="{{action('PaymentController@add', $check_id)}}" method="post">
            <div class="form-group">
                <label for="payer">Payer</label>
                <select class="form-control" id="payer" name="payer">
                    @foreach($users as $user)
                        <option value="{{$user->id}}" @if($user->id ==  Auth::id()) selected @endif>{{$user->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="payee">Payee (optional)</label>
                <select class="form-control" id="payee" name="payee">
                    <option value="-1">None</option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}">{{$user->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="check">Check (optional)</label>
                <select class="form-control" id="check" name="check">
                    <option value="-1">None</option>
                    @foreach($checks as $check)
                        <option value="{{$check->id}}"
                                @if($check->id == $check_id)
                                selected
                                @endif>
                            {{$check->id}} from {{$check->storeName}} at {{$check->initialDate}} with sum {{$check->initialTotalSum}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input  type="number" step="0.01" class="form-control"
                            id="amount" name="amount" required value="{{$amount}}">
                </div>
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>

@endsection