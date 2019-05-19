<?php
require "ControllerHome.php";

$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : "index";

$ctrl = new ControllerHome();

switch($pagina) {
    case "index" : 
        $ctrl->index();
    break;

    case "logar" : 

    if(isset($_REQUEST['cpf']) && !empty($_REQUEST['cpf'])
        && isset($_REQUEST['senha']) && !empty($_REQUEST['senha']))
        {
            $cpf = $_REQUEST['cpf']; 
            $senha = $_REQUEST['senha'];

            $ctrl->logar($cpf,$senha);

        }else{
            echo "erro ao enviar os parametros index";

        }
        break;

        case 'home':

            $paginaSelecionada  = isset($_REQUEST['paginaSelecionada']) ? $_REQUEST['paginaSelecionada'] : 1;
            $totalPorPagina     = isset($_REQUEST['totalPorPagina']) ? $_REQUEST['totalPorPagina'] : 100;

            $ctrl->home($busca=false,$paginaSelecionada,$totalPorPagina);
            break;

        case 'logout':
            $ctrl->logout();
        break;

        case 'relatorio':


                $paginaSelecionada  = isset($_REQUEST['paginaSelecionada']) ? $_REQUEST['paginaSelecionada'] : 1;
                $totalPorPagina     = isset($_REQUEST['totalPorPagina']) ? $_REQUEST['totalPorPagina'] : 100;

               
            if(isset($_REQUEST['buscar']) && !empty($_REQUEST['buscar']))
            {
                    $busca = $_REQUEST['buscar'];
                   
                    $ctrl->relatorio($busca,$paginaSelecionada,$totalPorPagina);
                   // echo "index com parametro";
            }else{
                    $busca = false;
                    $ctrl->relatorio($busca,$paginaSelecionada,$totalPorPagina);
               // echo "index sem parametro";
            }
        break;

        case 'export':

                $paginaSelecionada  = isset($_REQUEST['paginaSelecionada']) ? $_REQUEST['paginaSelecionada'] : 1;
                $totalPorPagina     = isset($_REQUEST['totalPorPagina']) ? $_REQUEST['totalPorPagina'] : 100;

            if(isset($_REQUEST['type']) && !empty($_REQUEST['type']) &&
                isset($_REQUEST['buscar']))
            {
                   $type =  $_REQUEST['type'];
                   $buscar = $_REQUEST['buscar'];

                   $ctrl->export($type,$buscar,$paginaSelecionada,$totalPorPagina);

                  // $ctrl->export($type,$buscar);
                
            }else{
                echo "não tem parametros csv";
                exit;
            }
            break;

            case 'cliente':

                    $data_start = false;
                    $data_end = false;
                    $id_venda = false;
                        
                    if(isset($_REQUEST['data_start']) 
                            && !empty($_REQUEST['data_start']))
                    {
                        $data_start = $_REQUEST['data_start'];
                    }

                    if(isset($_REQUEST['data_end']) 
                            && !empty($_REQUEST['data_end']))
                    {
                        $data_end = $_REQUEST['data_end'];
                    }

                    if(isset($_REQUEST['id_busca_venda']) 
                            && !empty($_REQUEST['id_busca_venda']))
                    {
                        $id_venda = $_REQUEST['id_busca_venda'];

                    }

                    if($id_venda != false)
                    {
                        $data_start = false;
                        $data_end = false;
                    }

                    if($data_start != false && $data_end != false)
                    {
                        $id_venda = false;
                    }

                    if($data_start != false && $data_end == false)
                    {
                        $id_venda = false;
                    }

                    if($data_end != false && $data_start == false && $id_venda == false)
                    {
                        $data_end = false;
                    }

                    $paginaSelecionada  = isset($_REQUEST['paginaSelecionada']) ? $_REQUEST['paginaSelecionada'] : 1;
                    $totalPorPagina     = isset($_REQUEST['totalPorPagina']) ? $_REQUEST['totalPorPagina'] : 100;
    
                    $ctrl->cliente($data_start,$data_end,$id_venda,$paginaSelecionada,$totalPorPagina);
            break;

            

            case 'gerarBanco':

               $ctrl->gerarBanco();
              
            break;

    default:
        $ctrl->erro();
        break;
}   

