<?php


Class ControllerHome{


    public function index()
    {
        include "login.php";
    }

    public function erro()
    {
        include "erro.php";
    }

    public function logar($cpf,$senha)
    {
        if(!empty($cpf) && !empty($senha))
        {
            $cpf = $_POST['cpf']; 
            $senha = $_POST['senha'];

            include_once "src/ClienteLogin.php";

            $clienteLogin  = new ClienteLogin();

            $clienteLogin->setDados($cpf,$senha);

           $validar =  $clienteLogin->validarUsuario();

           if($validar == 0)
           {
               session_start();

               $_SESSION["erro"]  = true; 
               $_SESSION["msg"]  = "Banco de Dados não está criado";

               header("Location:?pagina=index"); 
               exit;

           }

           if(!$validar)
           {
               session_start();

                $_SESSION["erro"]  = true; 
                $_SESSION["msg"]  = "Login ou senha inválidos";

                header("Location:?pagina=index"); 
                exit;
           }


        }else{
              
                    echo "Erro ao enviar parametros";
                
                
             }

    }

    public function logout()
    {
        session_start();
        unset($_SESSION['id_usuario']);
        unset($_SESSION['nome_usuario']);
        session_destroy();
        header("Location:?pagina=index"); 
        exit;
    }

    public function home($busca,$paginaSelecionada,$totalPorPagina)
    {
        include_once "src/ClienteLogin.php";

        $paginaLink = $paginaSelecionada;
        $totalLink = $totalPorPagina;

        $top=false;
        $busca = false;

        $clienteLogin = new ClienteLogin();

       $rt =  $clienteLogin->sessionExist();

        if($rt != false)
        {
            $nome_user = $rt[1];

            include_once "src/GerarRelatorioGerencial.php";
             $gerarRelatorio =  new GerarRelatorioGerencial();
             $gerarRelatorio->setDados();


             $retornoDados =   $gerarRelatorio->relatorioGeralTicker($busca=false,$paginaSelecionada,$totalPorPagina);

             $retorno = $retornoDados['dados'];

             $parse = true;
             $t200 = false;
             $t300 = false;
            
             include "home.php";

        }else{
            header("Location:?pagina=index"); 
            exit;
        }
      

    }

    public function relatorio($busca,$paginaSelecionada,$totalPorPagina)
    {
             include_once "src/GerarRelatorioGerencial.php";
             $gerarRelatorio =  new GerarRelatorioGerencial();
             $gerarRelatorio->setDados();

             $paginaLink = $paginaSelecionada;
             $totalLink = $totalPorPagina;

              $top=false;

            if($busca)
            {
                $retornoDados = $gerarRelatorio->relatorioGeralTicker($busca,$paginaSelecionada,$totalPorPagina);

                    $retorno = $retornoDados['dados'];

                if($busca == 100)
                {
                    $top =true;
                }

            }else{
                //bsuca default
                $retornoDados =  $gerarRelatorio->relatorioGeralTicker($busca=false,$paginaSelecionada,$totalPorPagina);

                $retorno = $retornoDados['dados'];

            }

            $parse = true;
            $t200 = false;
            $t300 = false;

             if(isset($retornoDados['totalPorPagina']))
             {
                     $parse = (int) $retornoDados['totalPorPagina'];

                     if($parse == 200)
                     {
                         $t200 =true;
                         $parse =false;
                         $t300 =false;

                     }else{
                             if($parse == 300)
                             {
                                 $t200 =false;
                                 $parse =false;
                                 $t300 =true;
                             }
                     }
            }

                //echo $retornoDados['totalPorPagina'];

              include "home.php";

    }

    public function export($type,$busca,$paginaSelecionada,$totalPorPagina)
    {

             include_once "src/GerarRelatorioGerencial.php";
             $gerarRelatorio =  new GerarRelatorioGerencial();
             $gerarRelatorio->setDados();

             $top=false;

            if($type == 'csv')
            {
                    
                    if($busca)
                    {
                        
                        $retornoDados = $gerarRelatorio->relatorioGeralTicker($busca,$paginaSelecionada,$totalPorPagina);

                        $retorno = $retornoDados['dados'];


                        if($busca == 100)
                        {
                            $top =true;
                        }

                    }else{

                        $retornoDados =  $gerarRelatorio->relatorioGeralTicker($busca=false,$paginaSelecionada,$totalPorPagina);

                        $retorno = $retornoDados['dados'];

                    }
                   

                //se for top 100 montar layout diferente
                if($top == true && $type == 'csv')
                {
                    
                    $array_content = array();
                    $array_line = array();
                    $array_header = array(
                        '#',
                        'ID',
                        'NOME',
                        'MEDIA',
                        'TOTAL',
                    );

                    $i=1;
                    foreach($retorno as $ex)
                    {
                        $array_line = [
                            'col_1' => $i,
                            'col_2' => $ex['id_cliente'],
                            'col_3' => $ex['nome_cliente'],
                            'col_4' => 'R$ '.number_format($ex['result'],2, '.', ''),
                            'col_5' => $ex['id_venda'],
                        ];

                        $i++;

                        array_push($array_content,$array_line);
                        
                    }
                }else{
                    //outro laytou de CSV
                    if($top == false && $type =='csv')
                    {
                        $array_content = array();
                        $array_line = array();
                        $array_header = array(
                            '#',
                            'DATA',
                            'MEDIA',
                            'TOTAL',
                        );

                        $i=1;

                        foreach($retorno as $ex)
                        {

                            //$ex['data']
                            $array_line = [
                                'col_1' => $i,
                                'col_2' => $ex['data'],
                                'col_3' => 'R$ '.number_format($ex['result'],2, '.', ''),
                                'col_4' => $ex['id_venda'],
                            ];

                            array_push($array_content,$array_line);
                            $i++;
                        }

                    }


                }


                if($top == true || $top == false && $type == 'csv')
                {
                    
                    include_once "src/GerarRelatorioGerencial.php";
                    $generateCSV =  new GerarRelatorioGerencial();
                    $generateCSV->setDados();

                    $pare = count($array_content);

                    $generateCSV->setHeader($array_header);
                    $generateCSV->setContent($array_content);
                    $generateCSV->generateAndDownloadFileCSV($pare);
                    exit;
                }


            }else{
                        if($busca)
                        {
                            $retornoDados = $gerarRelatorio->relatorioGeralTicker($busca,$paginaSelecionada,$totalPorPagina);
                            $retorno = $retornoDados['dados'];


                                if($busca == 100)
                                {
                                    $top =true;
                                
                                }
                        }else{

                            $retornoDados =  $gerarRelatorio->relatorioGeralTicker($busca=false,$paginaSelecionada,$totalPorPagina);
                            $retorno = $retornoDados['dados'];

                        }

                         //se for top 100 montar layout diferente
                        if($top == true  && $type == 'json')
                        {
                        
                            $retorno_array = array();
                            $i=1;
                            
                            foreach($retorno as $rt)
                            {
                                $array_header = array(
                                    '#'=>$i,
                                    'ID'=>$rt['id_cliente'],
                                    'NOME'=>$rt['nome_cliente'],
                                    'MEDIA'=>'R$ '.number_format($rt['result'],2, '.', ''),
                                    'TOTAL'=>$rt['id_venda'],
                                );

                                array_push($retorno_array,$array_header);
                                $i++;

                            }

                        }

                        if($top == false  && $type == 'json')
                        {
                        
                            $retorno_array = array();
                            $i=1;
                            
                            foreach($retorno as $rt)
                            {
                                $array_header = array(
                                    '#'=>$i,
                                    'DATA'=>$rt['data'],
                                    'MEDIA'=>'R$ '.number_format($rt['result'],2, '.', ''),
                                    'TOTAL'=>$rt['id_venda'],
                                );

                                array_push($retorno_array,$array_header);
                                $i++;

                            }

                        }


            }

                if($top == true || $top == false && $type == 'json')
                {
                    
                    include_once "src/GerarRelatorioGerencial.php";
                    $generateJSON =  new GerarRelatorioGerencial();
                    $generateJSON->generateAndDownloadFileJSON(json_encode($retorno_array));

                }

                    
                

        }


        public function cliente($dt_start,$dt_end,$id_venda,$paginaSelecionada,$totalPorPagina)
        {
            include_once "src/ClienteBusca.php";
            $cliente_busca = new ClienteBusca();

            $cliente_busca->conectDatabase();

            $top = false;

               if($dt_start === false &&
                     $dt_end === false 
                         && $id_venda === false)
               {
                        $retornoDados = $cliente_busca->getDadosHoje($id_venda,$paginaSelecionada,$totalPorPagina);

                       // echo count($retornoDados);
                        $retorno = $retornoDados['dados'];
                        
                        
               }

               if($id_venda != false && $dt_start == false && $dt_end == false)
               {
                    $retornoDados =  $cliente_busca->getDadosHoje($id_venda,$paginaSelecionada,$totalPorPagina);

                    $retorno = $retornoDados['dados'];

               }


               if($dt_start != false && $dt_end != false && $id_venda == false)
               {
                       //data_start e data end
                        $retornoDados = $cliente_busca->getForDate($dt_start,$dt_end,$paginaSelecionada,$totalPorPagina);
                        $retorno = $retornoDados['dados'];

               }else{
                        if($dt_start != false && $dt_end == false && $id_venda == false )
                        {
                            //busca só por data
                            $retornoDados = $cliente_busca->getForDate($dt_start,$dt_end,$paginaSelecionada,$totalPorPagina);
                            $retorno = $retornoDados['dados'];
                            
                        }

               }


               //tratar pagina selecionada totalPorPagina

               $parse = true;
               $t200 = false;
               $t300 = false;

                if(isset($retornoDados['totalPorPagina'])){

                        $parse = (int) $retornoDados['totalPorPagina'];

                        if($parse == 200)
                        {
                            
                            $t200 =true;
                            $parse =false;
                            $t300 =false;

                        }else{
                                if($parse == 300)
                                {
                                    $t200 =false;
                                    $parse =false;
                                    $t300 =true;
                                }

                           

                        }

                        //echo "</br>if totalPagina = $totalPorPagina</br>";

                }else{

                    $default = true;
                    $totalPorPagina ="";
                }


               
               include "cliente.php";
        }

        public function gerarBanco()
        {
            error_reporting(0);
            ini_set('display_errors', 0);
            include "loader.php";
            sleep(10);
            include_once "src/GeraDatabase.php";
            $conn = new GeraDataBase();
            $conn->getRetornoConexao();

            $conn->GerarTabelas();
            $conn->popularTableUf();
            $clientes = $conn->popularTableCliente();
            $conn->gerarVendasclientes();
            $user = $conn->gerarUserAdmin();

            echo'
            <script>
                var myVar;

                function myFunction() {
                myVar = setTimeout(showPage, 1000);
                }

                function showPage() {
                document.getElementById("loader").style.display = "none";
                document.getElementById("myDiv").style.display = "block";
                }
            </script>
            
';

        }




}