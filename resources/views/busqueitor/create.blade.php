@extends('layouts.base')

@section('main')

<script type="text/javascript" src="/resources/assets/js/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<link href="/resources/assets/js/bootstrap.min.css" rel="stylesheet">
<head>
    <title>Untitled</title>
</head>

<div class="dropdown">Categoria
    {{--onclick--}}
    {{--onchange--}}
    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button" onblur="ClicouCatego()" >Selecione<span class="caret"></span></button>
    <ul class="dropdown-menu" role="menu">
    <?php
        $cCat = new App\BusqCategorias();
        echo $cCat->Categorias();
    ?>
    </ul>
</div>
<div class="dropdown">SubCategoria
    <button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button">Selecione<span class="caret"></span></button>
    <ul class="dropdown-menu" role="menu">
        <li role="presentation"><a href="#">First Item</a></li>
        <li role="presentation"><a href="#">Second Item</a></li>
        <li role="presentation"><a href="#">Third Item</a></li>
    </ul>
</div>
<label>Nome</label><input type="text"></p>
<label>Endereço</label><input type="text"></p>
<label>Quantidade de pessoas</label><input type="text"></p>
<label>Data de criação do grupo</label><input type="text"></p>
<label>Contato do Adm</label><input type="text"></p>
<label>Observação</label><input type="text"></p>

<script>
    function ClicouCatego() {
        alert("ClicouCatego");
    }
</script>

@endsection