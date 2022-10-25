<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class BusqCategorias extends Model
{
    protected $table = 'busqcategorias';

    public function Categorias() {
        $Cons = DB::table('busqcategorias')
            ->select('nome')
            ->where('idpai','=',0)
            ->get();
        $ret="";
        foreach ($Cons as $reg) {
            $ret.="<li role='presentation'><a href='#'>$reg->nome</a></li>";
        }
        return $ret;
    }
}
