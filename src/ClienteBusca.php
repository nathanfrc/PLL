<?php 

include_once "GeraDatabase.php";

class ClienteBusca extends GeraDatabase{

    private $conn;


    function conectDatabase()
    {
        $this->conn = parent::getRetornoConexao();
    }


    public function getDadosHoje($id_venda,$paginaSelecionada,$totalPorPagina)
    {
        try{

                    $paginaSelecionada = (!$paginaSelecionada) ? 1 : $paginaSelecionada;
                    $inicio = (($paginaSelecionada - 1) * $totalPorPagina);

            if($id_venda == false)
            {

                $totalLinhas = $this->conn->query("select distinct count(*) as total
                from venda v, cliente c, uf u  where c.id_uf = u.id_uf 
                and c.id_cliente = v.id_cliente order by data_compra;");

                foreach($totalLinhas as $total)
                {
                    $totalLinhas=$total['total'];
                }
                   
                $dadosHoje = $this->conn->query("select distinct v.id_venda,v.valor,DATE_FORMAT(v.data_compra,'%d-%m-%Y') as data, c.id_cliente,c.nome_cliente,c.cpf, u.nome as nome_cidade, u.sigla
                from venda v, cliente c, uf u  where c.id_uf = u.id_uf 
                and c.id_cliente = v.id_cliente order by data_compra DESC limit $inicio,$totalPorPagina;");

                 $html =  self::criarLink($busca="",$totalLinhas,$totalPorPagina,$paginaSelecionada);

                return ['criarLink' => $html,
                        'totalPorPagina'=>$totalPorPagina,
                        'dados'=> $dadosHoje];

            }else{


                $totalLinhas = $this->conn->query("select count(*) as total 
                from venda v, cliente c, uf u  where v.id_venda like '%$id_venda%' and c.id_uf = u.id_uf 
                and c.id_cliente = v.id_cliente  order by v.id_venda;");

                foreach($totalLinhas as $total)
                {
                    $totalLinhas=$total['total'];
                }


                $dadosHoje = $this->conn->query("select distinct v.id_venda,v.valor,DATE_FORMAT(v.data_compra,'%d-%m-%Y') as data, c.id_cliente,c.nome_cliente,c.cpf, u.nome as nome_cidade, u.sigla
                from venda v, cliente c, uf u  where v.id_venda like '%$id_venda%' and c.id_uf = u.id_uf 
                and c.id_cliente = v.id_cliente  order by v.id_venda DESC");

                $html =  self::criarLink($busca="",$totalLinhas,$totalPorPagina,$paginaSelecionada);

                return ['criarLink' => $html,
                        'totalPorPagina'=>$totalPorPagina,
                        'dados'=> $dadosHoje];


            }

                 

        }catch(Exception $e){
            echo 'ERROR: ' . $e->getMessage();
        }
    }


    public function getForDate($data_start,$data_end,$paginaSelecionada,$totalPorPagina)
    {
            try{

                    $paginaSelecionada = (!$paginaSelecionada) ? 1 : $paginaSelecionada;
                    $inicio = (($paginaSelecionada - 1) * $totalPorPagina);

                    if($data_start != false && $data_end != false)
                    {

                        $totalLinhas = $this->conn->query("select distinct count(*) as total
                        from venda v, cliente c, uf u  where v.data_compra between '$data_start' and '$data_end' and c.id_uf = u.id_uf 
                        and c.id_cliente = v.id_cliente;");

        
                            foreach($totalLinhas as $total)
                            {
                                $totalLinhas=$total['total'];
                            }


                        $dadosHoje = $this->conn->query("select  v.id_venda,v.valor,DATE_FORMAT(v.data_compra,'%d-%m-%Y') as data, c.id_cliente,c.nome_cliente,c.cpf, u.nome as nome_cidade, u.sigla
                        from venda v, cliente c, uf u  where v.data_compra between '$data_start' and '$data_end' and c.id_uf = u.id_uf 
                        and c.id_cliente = v.id_cliente  order by v.data_compra ASC;");

                        $html =  self::criarLinkBuscaPorData($data_start,$data_end,$totalLinhas,$totalPorPagina,$paginaSelecionada);

                        return ['criarLink' => $html,
                                'totalPorPagina'=>$totalPorPagina,
                                'dados'=> $dadosHoje];


                    }else{

                        $totalLinhas = $this->conn->query("select distinct count(*) as total
                        from venda v, cliente c, uf u  where v.data_compra >= '$data_start' and c.id_uf = u.id_uf 
                        and c.id_cliente = v.id_cliente  order by data_compra;");

                        //var_dump($totalLinhas);

                        foreach($totalLinhas as $total)
                        {
                            $totalLinhas=$total['total'];
                        }

                        $dadosHoje= $this->conn->query("select  v.id_venda,v.valor,DATE_FORMAT(v.data_compra,'%d-%m-%Y') as data, c.id_cliente,c.nome_cliente,c.cpf, u.nome as nome_cidade, u.sigla
                        from venda v, cliente c, uf u  where v.data_compra >= '$data_start' and c.id_uf = u.id_uf 
                        and c.id_cliente = v.id_cliente  order by data_compra ASC;");

                        $html =  self::criarLinkBuscaPorData($data_start,$data_end,$totalLinhas,$totalPorPagina,$paginaSelecionada);

                        return ['criarLink' => $html,
                                'totalPorPagina'=>$totalPorPagina,
                                'dados'=> $dadosHoje];
                    }




            }catch(Exception $e){
                echo 'ERROR: ' . $e->getMessage();
            }

    }

    //total por linhas = retorno do banco
    //totalPorgina oq o cliente digitou
    //$paginaSelecionada , pagina selecionada

    public function criarLink($buscaProduto,$totalLinhas,$totalPorPagina,$paginaSelecionada)
    {

        $quantidadePagina   = ceil($totalLinhas / $totalPorPagina );
        $queryString        = (isset($buscaProduto)) ? "&buscaProduto=$buscaProduto" : "";
        $queryString       .= (!empty($totalPorPagina)) ? '&totalPorPagina=' . $totalPorPagina : '';
        
        $primeiraPagina     = 1;


        $html          = '<div class="row">';
        $html         .= '<div class="col-md-12 cenralizado">';
        $html         .= '<ul class="pagination pagination-sm">';
        $desabilita    = ( $paginaSelecionada == $primeiraPagina ) ? "disabled" : "";
        $html         .= "<li class='page-item $desabilita '>";
        $html         .= ( $paginaSelecionada == $primeiraPagina ) ? '<a href="#">&laquo; Anterior </a>' : '<a href="?pagina=cliente&paginaSelecionada=' . ( $paginaSelecionada - 1 ) . $queryString . '">&laquo; Anterior </a>';
        $html         .= '</li>';

        $html .= "<li class='page-item active'><a>".$paginaSelecionada." de ".$quantidadePagina."</a></li>";

        $desabilita    = ( $paginaSelecionada == $quantidadePagina ) ? "disabled" : "";
        $html         .= "<li class='page-item  $desabilita  '>";
        $html         .= ( $paginaSelecionada == $quantidadePagina ) ? '<a href="#">Próxima &raquo;</a>' : '<a href="?pagina=cliente&paginaSelecionada=' . ( $paginaSelecionada + 1 ) . $queryString . '">Próxima &raquo;</a>';
        $html         .= '</li>';
        $html         .= '</ul>';
        $html         .= '</div>';
        $html         .= '</div>';

        return $html;
    }

    public function criarLinkBuscaPorData($data_start,$data_end,$totalLinhas,$totalPorPagina,$paginaSelecionada)
    {

        $quantidadePagina   = ceil($totalLinhas / $totalPorPagina );
        $queryString        = (isset($data_start)) ? "&data_start=$data_start" : "";
        $queryString       .= (isset($data_start)) ? "&data_end=$data_end" : "";
        $queryString       .= (!empty($totalPorPagina)) ? '&totalPorPagina=' . $totalPorPagina : '';
        
        $primeiraPagina     = 1;


        $html          = '<div class="row">';
        $html         .= '<div class="col-md-12 cenralizado">';
        $html         .= '<ul class="pagination pagination-sm">';
        $desabilita    = ( $paginaSelecionada == $primeiraPagina ) ? "disabled" : "";
        $html         .= "<li class='page-item $desabilita '>";
        $html         .= ( $paginaSelecionada == $primeiraPagina ) ? '<a href="#">&laquo; Anterior </a>' : '<a href="?pagina=cliente&paginaSelecionada=' . ( $paginaSelecionada - 1 ) . $queryString . '">&laquo; Anterior </a>';
        $html         .= '</li>';

        $html .= "<li class='page-item active'><a>".$paginaSelecionada." de ".$quantidadePagina."</a></li>";

        $desabilita    = ( $paginaSelecionada == $quantidadePagina ) ? "disabled" : "";
        $html         .= "<li class='page-item  $desabilita  '>";
        $html         .= ( $paginaSelecionada == $quantidadePagina ) ? '<a href="#">Próxima &raquo;</a>' : '<a href="?pagina=cliente&paginaSelecionada=' . ( $paginaSelecionada + 1 ) . $queryString . '">Próxima &raquo;</a>';
        $html         .= '</li>';
        $html         .= '</ul>';
        $html         .= '</div>';
        $html         .= '</div>';

        return $html;
    }






}