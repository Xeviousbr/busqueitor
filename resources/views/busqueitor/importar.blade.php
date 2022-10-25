@extends('layouts.base')
<title>Importador do Buscator</title>
@section('main')
<Br>
<form name="formulario" action="{{ url('/busqueitor') }}" enctype="multipart/form-data" method="post">
    {{ csrf_field() }}
    <input name="File1" id="File1" type="file" />
    <input name="Button1" type="submit" value="Importar" />
</form>
<input name="btVoltar" type="button" value="Voltar" onclick="window.history.go(-1)">
@endsection