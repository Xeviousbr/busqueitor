<?php



namespace App\Http\Controllers;



use App\BusqGrupos;
use App\BusqCategorias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BusqueitorController extends Controller
{
    public function index()
    {
        // $grp = Busqgrupos::all();
        return view('busqueitor.index');
    }

    /* public function index(Request $request)
    {
        echo '17'; die;
        $query = Busqgrupos::select('*');
        if (!empty($request->search)) {
            $searchFields = ['nome', 'descricao'];
            $query->where(function ($query) use ($request, $searchFields) {
                $searchWildcard = '%' . $request->search . '%';
                foreach ($searchFields as $field) {
                    $query->orWhere($field, 'LIKE', $searchWildcard);
                }
            });
        }
        //$grupos = Grupo::all();
        $grupos = $query->get();
        return view('busqueitor.index', compact('busqueitor'));
    } */

    public function create()
    {
        return view('busqueitor.create');
    }

    public function store(Request $request)
    {
        // var_dump($request); die;
// echo "store"; die;
        // FORMATO DO ARQUIVO = TSV
        if ($request->file('File1') == null) {
            echo "É necessário enviar o arquivo<p>";
            ?>
            <input name="btVoltar" type="button" value="Voltar" onclick="window.history.go(-1)">
            <?php
            exit(0);
        }

        // DETECTAR NOME
        $file = $request->file('File1');
        $nome = $request->file('File1')->getClientOriginalName();

        // APAGAR VERSÃO ANTERIOR, SE OUVER
        $files1 = \File::files('bus');
        foreach($files1 as $arq) {
            Storage::delete($arq);
        }

        // REALIZAR O UPLOAD DO ARQUIVO
        Storage::put($nome, $file);

        // DETECTAR CATEGORIA - principal
        $aux = substr($nome, 14);
        $tam = strlen($aux);    // 13
        $nomecat = substr($aux, 0, $tam-4);
        $idpai = 0;
        // APAGAR REGISTROS DA CATEGORIA
        $idcategorias = DB::table('busqcategorias')
            ->select('idcategoria')
            ->where('nome','=',$nomecat)
            ->where('idpai','=',0)
            ->first();
        if ($idcategorias==null) {
            $Cate = new BusqCategorias;
            $Cate->idpai = 0;
            $Cate->nome = $nomecat;
            $Cate->save();
            $idcat = DB::table('busqcategorias')->max('idcategoria');
        } else {
            $idcat = $idcategorias->idcategoria;
            DB::update("delete from busqgrupos where idcategoria = ".$idcat);
            echo "Registros de categoria de tipo ".$idcat." deletada<Br>";
        }

        // ABRIR ARQUIVO
        $content = file_get_contents($file);
        
        $LINHA = explode(PHP_EOL, $content);
        $Quant = count($LINHA);
        $qtd = 0;
        $idcat = 0;

        // LER LINHA A LINHA
        for ($i=0;$i<$Quant;$i++) {
            $tam = strlen($LINHA[$i]);
            if ($tam>10) {
                $campos = preg_split("/[\t]/", $LINHA[$i]);
                $subcategoria = $campos[0]; // A
                $grupo = $campos[1];        // B
                $criador = $campos[2];      // C
                $endereco = $campos[3];     // D
                $quant = $campos[4];        // E
                $dtatu = $campos[5];        // F
                if (strpos($endereco,'chat')>0) {
                    // VERIFICAR SE É SUB-CATEORIA EXISTE

                    $TamEnb = strlen($endereco); 
                    if ($TamEnb<50) {
                        if (strlen($subcategoria)>1) {
                            $idSubcategorias = DB::table('busqcategorias')
                                ->select('idcategoria')
                                ->where('nome','=',$subcategoria)
                                ->where('idpai','>',0)
                                ->first();
                            if ($idSubcategorias==null) {
                                if ($idpai==0) {
                                    $idpai = DB::table('busqcategorias')->max('idcategoria') + 1;
    echo "<Br>Planilha de tipo ".$nomecat." ganha o ID ".$idpai." passa a ser o IdPai dessa importação<Br>";
                                }
                                $Cate = new BusqCategorias;
                                $Cate->idpai = $idpai;
                                $Cate->nome = $subcategoria;
                                $Cate->save();
                                $idSubcat = DB::table('busqcategorias')->max('idcategoria');
                            } else {
                                $idSubcat = $idSubcategorias->idcategoria;
                            }
                        }
                        // INSERIR REGISTROS
        
                        // CRITICA DA DATA
                        $dtatu = trim($dtatu," ");
                        $TamDt = strlen($dtatu); 
                        switch ( $TamDt ) {
                            case 0:
                                $dt = "";
                                break;
                            case 1:
                                $dt = "";
                                break;                            
                            case 4: 
                                $ano = substr($dtatu,2);
                                $mes = substr($dtatu,0,2); 
                                if (strpos($mes,'/')==1) {
                                    $mes = "0"+$mes[0];
                                }
                                $dt = "20".$ano."-".$mes."-01"; 
                                break;
                            case 5:
                                $ano = substr($dtatu,3);
                                $mes = substr($dtatu,0,2);
                                if ($mes>12) {
                                    $dia = substr($dtatu,0,2);
                                    $mes = substr($dtatu,3,2);
                                    $dt = "2022-".$mes."-".$dia; 
                                } else {
                                    $dt = "20".$ano."-".$mes."-01"; 
                                }
                                break;
                            case 6: 
                                echo "TamDt = 6 dtatu = ".$dtatu." | ";
                                $dtatu = substr($dtatu,0,5);
                                $TamDt = 5;                             
                                break;
                            break; 
                            case 7:
                                $ano = substr($dtatu,3);
                                $mes = substr($dtatu,0,2);
                                $dt = $ano."-".$mes."-01";
                                break;
                            break;
                            case 8:
                                $dia = substr($dtatu,0,2);
                                $ano = "20".substr($dtatu,6);
                                $mes = substr($dtatu,3,2);
                                $dt = $ano."-".$mes."-".$dia; 
                                break;
                            case 10:
                                $dia = substr($dtatu,0,2);
                                $ano = substr($dtatu,6,4);
                                $mes = substr($dtatu,3,2);
                                $dt = $ano."-".$mes."-".$dia; 
                            break;
                            default: 
                                echo "TamDt = ".$TamDt;                         
                                echo " dtatu = |".$dtatu."| ";
                                echo $endereco;
                                die;     
                        } 
    
                        // Critica do GRUPO
                        $grupo = $this->FiltraCaracter($grupo, "'");
                        $grupo = $this->FiltraCaracter($grupo, ",");
    
                        $Grupo = new BusqGrupos;
                        if ($dt>"") {
                            $Grupo->atualizacao = $dt;
                        }                    
                        if ($quant>"") {
                            $Grupo->qtdusers = $quant;
                        }
                        $Grupo->idComunicador = 1;
                        $Grupo->idcategoria = $idSubcat;
                        $Grupo->nome = $grupo;
                        $Grupo->endereco = $endereco;                    
                        $Grupo->foneadm = $criador;                    
                        $Grupo->save();
                        $qtd++;
                        // echo $qtd." ";    
                    }
                }
            }
        }
        // MOSTRAR RELATÓRIO DE REGISTROS IMPORTADOS
echo "<Br>Quantidade Importada = ".$qtd;
        ?>
        <input name="btVoltar" type="button" value="Voltar" onclick="window.history.go(-1)">
        <?php
    }

    private function FiltraCaracter($grupo, $Carac) {
        $posAspa = strpos($grupo, $Carac);
        if ($posAspa>0) {
            $ped1 = substr($grupo,0,$posAspa);
            $ped2 = substr($grupo,$posAspa+1);
            $grupo =  $ped1." ".$ped2;
        } 
        return $grupo;
    }

    public function importa() {
        return view('busqueitor.importar');
    }

    /* public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'link' => 'required'
        ]);
        $grupo = new Grupo([
            'nome' => $request->get('nome'),
            'link' => $request->get('link'),
            'descricao' => $request->get('descricao')
        ]);
        $grupo->save();
        return redirect('/busqueitor')->with('success', 'Grupo registrado com sucesso');

    } */

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $grupo = Busqgrupos::find($id);
        return view('busqueitor.edit', compact('grupo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required',
            'link' => 'required'
        ]);
        $grupo = Busqgrupos::find($id);
        $grupo->nome =  $request->get('nome');
        $grupo->link = $request->get('link');
        $grupo->descricao = $request->get('descricao');
        $grupo->save();
        return redirect('/busqueitor')->with('success', 'Grupo alterado com sucesso');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $grupo = Busqgrupos::find($id);
        $grupo->delete();
        return redirect('/busqueitor')->with('success', 'Grupo apagado com sucesso');
    }

}

