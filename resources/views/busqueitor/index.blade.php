@extends('layouts.base')
@section('main')
    @if(session()->get('success'))
        <br>
        <div class="col-sm-12">
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-sm-12">
            <h1 class="display-3">Grupos</h1>
            <div>
                <a style="margin: 19px;" href="{{ route('busqueitor.create')}}" class="btn btn-primary">Novo grupo</a>
                <a style="margin: 19px;" href="https://tele-tudo.com/importatabelas" class="btn btn-warning">Importar</a>
            </div>
            <form action="https://tele-tudo.com/busqueitor" method="get">
                <div class="input-group md-form form-sm form-2 pl-0">
                    <input autofocus class="form-control my-0 py-1 amber-border" type="text" name="search" placeholder="Buscar grupo..." aria-label="Search" value="{{ request('search')}}">
                    <div class="input-group-append">
                        <button class="btn btn-success" type="submit">Busca</button>
                    </div>
                </div>
            </form>
            <table class="table table-striped">
                <thead>
                <tr>
                    <td>Nome</td>
                    <td>link</td>
                    <td>Tipo</td>
                </tr>
                </thead>
                <tbody>
                <?php
                $search = request('search');
                if ($search=="") {
                    $grupos = DB::table('busqgrupos')
                        ->join('busqcategorias', 'busqcategorias.idcategoria', '=', 'busqgrupos.idcategoria')
                        ->select('busqgrupos.nome','busqgrupos.endereco','busqcategorias.nome as categoria')
                        ->where('busqgrupos.nome', '>', '')
                        ->orderBy('busqgrupos.nome')
                        ->get();
                } else {
                    $grupos = DB::table('busqgrupos')
                        ->join('busqcategorias', 'busqcategorias.idcategoria', '=', 'busqgrupos.idcategoria')
                        ->select('busqgrupos.nome','busqgrupos.endereco','busqcategorias.nome as categoria')
                        ->where('busqgrupos.nome', 'like', '%'.$search.'%')
                        ->orderBy('busqgrupos.nome')
                        ->get();
                }
                foreach ($grupos as $grupo) {
                    echo "<tr><td>".$grupo->nome."</td>
                        <td><a target='_blank' href=".$grupo->endereco.">".$grupo->endereco."</a></td>
                        <td>".$grupo->categoria."</td>
                        </tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    Busqueitor
@endsection