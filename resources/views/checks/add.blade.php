@extends('app')

@section('content')
<form id="fiscal_form" action="{{action('CheckController@confirm')}}" method="post">
    <div>Фискальный номер (fn)</div>
    <input type="number" name="fn" id="fn">
    <div>Фискальный документ (i)</div>
    <input type="number" name="i" id="i">
    <div>Фискальный Признак Документа(fp)</div>
    <input type="number" name="fp" id="fp">
    <h1>OR</h1>
    <div>QR code</div>
    <input type="text" name="qr" id="qr">
    <h2>For both:</h2>
    <div>Телефон</div>
    <input type="tel" name="phone" value="+79787466941">
    <div>Пароль</div>
    <input type="number" name="password" value="891075">
    {{ csrf_field() }}
    <input type="submit" name="submit" value="Найти">
</form>
@endsection