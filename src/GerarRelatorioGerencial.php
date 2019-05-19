<?php

include_once "GeraDatabase.php";
class GerarRelatorioGerencial extends GeraDatabase
{

    private $conn;



    function setDados()
    {
        $this->conn = parent::getRetornoConexao();
    }

    
    function relatorioGeralTicker($busca,$paginaSelecionada,$totalPorPagina)
    {
 
        $ticker = null;

        if($busca !== false)
        {
           
            if($busca == 100)
            {
                $ticker = $this->conn->query('select c.id_cliente, c.nome_cliente,sum(v.valor) / count(*) as result,count(id_venda) as id_venda
                from cliente c, venda v where c.id_cliente = v.id_cliente  group by c.id_cliente  order by result DESC limit 100');

            }else{
                //apenas um retorno
                $ticker = $this->conn->query("select sum(valor) / count(*) as result , DATE_FORMAT(data_compra,'%d-%m-%Y') as data, count(id_venda) as id_venda
                from venda where data_compra = '$busca' group by data_compra");
            }
                //echo "com parametro";

        }else{

                //echo "entrou para criar tabela temporaria</br>";
                //exit;
                $paginaSelecionada = (!$paginaSelecionada) ? 1 : $paginaSelecionada;
                $inicio = (($paginaSelecionada - 1) * $totalPorPagina);

                $table_tempory =  $this->conn->query("CREATE TEMPORARY TABLE tb_temporaria select sum(valor) / count(*) as result , DATE_FORMAT(data_compra,'%d-%m-%Y') as data, count(id_venda) as id_venda
                from venda group by data_compra order by data_compra");

                $totalLinhas = $this->conn->query("select count(*) as total
                from tb_temporaria");

                foreach($totalLinhas as $total)
                {
                    $totalLinhas = $total['total'];
                }



                $ticker = $this->conn->query("select id_cliente,sum(valor) / count(*) as result , DATE_FORMAT(data_compra,'%d-%m-%Y') as data, count(id_venda) as id_venda from venda group by data_compra order by data_compra limit $inicio,$totalPorPagina");

                $html =  self::criarLink($busca="",$totalLinhas,$totalPorPagina,$paginaSelecionada);

                return ['criarLink' => $html,
                        'totalPorPagina'=>$totalPorPagina,
                        'dados'=> $ticker];
        }

        return ['dados'=> $ticker];
    }

    //criar link

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
        $html         .= ( $paginaSelecionada == $primeiraPagina ) ? '<a href="#">&laquo; Anterior </a>' : '<a href="?pagina=relatorio&paginaSelecionada=' . ( $paginaSelecionada - 1 ) . $queryString . '">&laquo; Anterior </a>';
        $html         .= '</li>';

        $html .= "<li class='page-item active'><a>".$paginaSelecionada." de ".$quantidadePagina."</a></li>";

        $desabilita    = ( $paginaSelecionada == $quantidadePagina ) ? "disabled" : "";
        $html         .= "<li class='page-item  $desabilita  '>";
        $html         .= ( $paginaSelecionada == $quantidadePagina ) ? '<a href="#">Próxima &raquo;</a>' : '<a href="?pagina=relatorio&paginaSelecionada=' . ( $paginaSelecionada + 1 ) . $queryString . '">Próxima &raquo;</a>';
        $html         .= '</li>';
        $html         .= '</ul>';
        $html         .= '</div>';
        $html         .= '</div>';

        return $html;
    }


    //Methods para criar CSV

    private $header = array();
    private $array_content = array();

    public function setHeader($array_header)
    {
        $this->header = $array_header;
    }
 
    public function setContent($array_content)
    {
        $this->array_content = $array_content;
    }
 
    /**
     * Generate the file and Download
     */
    public function generateAndDownloadFileCSV($pare)
    {
        

            $header_file = $this->getHeader();
            $content_file = $this->getContent();
    
            header('Cache-Control: max-age=0');
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="my_csv_file.csv";');
            $output = fopen('php://output', 'w');
            if (!empty($header_file)) { // => Optional header
                fputcsv($output, $header_file, ';');
            }
            $i=0;
            foreach ($content_file as $value) {

                    if($i >= $pare)
                    {
                        fclose($output);
                        break; 
                    }
                    $i++;

                fputcsv($output, $value, ';');
            }


        //fechando arquivo
    }
 
    /**
     * Gets the file header
     */
    private function getHeader()
    {
        return $this->header;
    }
 
    /**
     * Gets the content from array (usualy the database rows)
     */
    private function getContent()
    {
        $array_retorno = array();
        // => Checks whether data exists to be add on file
        if (count($this->array_content) > 0) {
            // => Scroll through the array
            foreach ($this->array_content as $value) {
                // => Contents definitions from database or other place
                $array_temp = array();
                foreach ($value as $col) {
                    // => Column "Column 1"
                    $array_temp[] = $col;
                }
                $array_retorno[] = $array_temp;
                unset($array_temp);
            }
        }
        return $array_retorno;
    }


    //escrever um json para download
    public function generateAndDownloadFileJSON($json)
    {
        
        header('Cache-Control: max-age=0');
        header('Content-Type: text/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="my_json_file.json";');
        $output = fopen('php://output', 'w');
       
        fwrite($output, $json);
        fclose($output);
    }



}