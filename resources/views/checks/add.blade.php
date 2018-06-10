@extends('app')

@section('content')
<form id="fiscal_form" action="{{action('CheckController@confirm')}}" method="post">
    <div>Фискальный номер (fn)</div>
    <input type="number" name="fn" id="fn" value="9282000100045868">
    <div>Фискальный документ (i)</div>
    <input type="number" name="i" id="i" value="3328">
    <div>Фискальный Признак Документа(fp)</div>
    <input type="number" name="fp" id="fp" value="1750902585">
    {{ csrf_field() }}
    <!--
    <div>Телефон</div>
    <input type="tel" name="phone">
    <div>Пароль</div>
    <input type="number" name="password">-->
    <input type="submit" name="submit" value="Найти">
</form>
@endsection