@extends('app')
@section('content')
    <div class="container p-3">
        <form action="{{$action}}" method="post">
            <div class="form-group">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input  type="text" class="form-control"
                            id="name" name="name" required value="{{isset($user)?$user->name:''}}">
                </div>
            </div>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>

@endsection