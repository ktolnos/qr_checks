<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta title="Excel Mailer">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{URL::asset('css/style.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.css">
    <link rel="stylesheet" href="{{URL::asset('css/vueful/app.style.css')}}">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="{{URL::asset('js/app.js')}}"></script>
    <script src="http://cdn.date-fns.org/v1.9.0/date_fns.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('js/vueful/components.bundle.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
    <nav class="my-2 my-md-0 mr-md-3">
        <a class="p-2 text-dark" href="{{url('checks')}}">Checks</a>
        <a class="p-2 text-dark" href="products.php">Products</a>
        <a class="p-2 text-dark" href="payments.php">Payments</a>
        <a class="p-2 text-dark" href="tags.php">Tags</a>
        <a class="p-2 text-dark" href="users.php">Users</a>
    </nav>
    @include('common.errors')
    @include('common.messages')
</div>
<div id="app">
    @yield('content')
</div>
</body>
</html>