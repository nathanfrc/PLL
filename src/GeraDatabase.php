<?php

class GeraDataBase{


    private static $conn;
    
    //qualquer coisa alterar o valor das variaveis para procesar o banco mais rapido
    public $qt_clientes_total = 10000;
    public $qt_vendas_total = 30000;


    public function getRetornoConexao()
    {
        try{
            
            self::$conn = new PDO('mysql:host=localhost;port=3306;dbname=PLL2','sabesp','nathan040993');
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return self::$conn;

        }catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }


    public function GerarTabelas()
    {

        try{

            $table_exist = self::tableExist('cliente');

            if(!$table_exist)
            {


                $sql_create_table_cliente = "CREATE TABLE IF NOT EXISTS `cliente` (
                    `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
                    `nome_cliente` varchar(200) NOT NULL,
                    `cpf` char(11) NOT NULL,
                    `senha` varchar(200) NOT NULL,
                    `id_uf` int(11) NOT NULL,
                    `dt_cadastro` DATE NOT NULL,
                    PRIMARY KEY (`id_cliente`)
                )";


                $sql_create_table_uf ="CREATE TABLE IF NOT EXISTS `uf` (
                    `id_uf` int(11) NOT NULL AUTO_INCREMENT,
                    `nome` varchar(150) NOT NULL,
                    `sigla` char(2) NOT NULL,
                    `created_at` TIMESTAMP,
                    `update_at` TIMESTAMP,
                    PRIMARY KEY (`id_uf`)
                )";


                $sql_create_table_venda = "CREATE TABLE IF NOT EXISTS `venda` (
                    `id_venda` INT(11) NOT NULL AUTO_INCREMENT,
                    `data_compra` DATE,
                    `valor` REAL NULL,
                    `id_cliente` INT NULL,
                    PRIMARY KEY (`id_venda`)) AUTO_INCREMENT = 100000;";


                $sql_chaves_cliente ="ALTER TABLE `cliente` 
                ADD CONSTRAINT `id_uf`
                FOREIGN KEY (`id_uf`)
                REFERENCES `uf` (`id_uf`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION;";

                $sql_chaves_venda ="ALTER TABLE `venda` 
                ADD CONSTRAINT `id_cliente`
                FOREIGN KEY (`id_cliente`)
                REFERENCES  `cliente` (`id_cliente`)
                ON DELETE NO ACTION
                ON UPDATE NO ACTION;";

                    $status = self::$conn->query($sql_create_table_cliente);

                    if($status)
                    {
                    
                        $status_two = self::$conn->query($sql_create_table_uf);

                        if($status_two)
                        {
                                $status_tre = self::$conn->query($sql_create_table_venda);

                            $chave_one  =   self::$conn->query($sql_chaves_cliente);
                            $chave_two =   self::$conn->query($sql_chaves_venda);

                                if($chave_one == true && $chave_two)
                                {

                                    return "Ok na criação das tabelas</br>";
                                }

                                return "Erro ao criar as chaves</br>";

                        }else{

                            return "erro na criação da tabela uf</br>";
                        }

                    }else{
                        return "Erro ao criar a primeira cliente</br>";
                    }


                    return "Erro ao criar a tabela</br>";
            }else{
                return "table já existem</br>";
            }

        }catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }


    }

    private function tableExist($table)
    {
        $table_exist = self::$conn->query('SHOW TABLES');

        $tb_db = array();

        foreach($table_exist as $tb)
        {
            array_push($tb_db,$tb['Tables_in_pll2']);
        }

        if(in_array($table,$tb_db))
        {
            return true;
        }

        return false;
    
    }

    public function popularTableUf()
    {

        try{

                $quantidade = self::$conn->query('select count(*) as qt from uf;');

                foreach($quantidade as $q)
                {
                    $qt = $q['qt'];
                }

               if($qt == 0 )
               {

                    $string = file_get_contents('src/uf.php',0,null,null);
                    $uf = json_decode($string);

                    $stmt = self::$conn->prepare('INSERT INTO `uf` (nome,sigla)VALUES(:nome,:sigla);');

                            foreach($uf as $r)
                            {
                                try{

                                    $stmt->execute(array(':nome' =>$r[1],':sigla' =>$r[0]));
                                    
                                    
                                }catch(Exception $e) {
                                    echo 'ERROR: ' . $e->getMessage();
                                }
                                
                            }

                        return "tabela uf populado com sucesso</br>";
                }else{
                    return "Já está populado o banco</br>";
                }

            }catch(PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
            }
        
    }

    public function popularTableCliente()
    {

        $quantidade = self::$conn->query('select count(*) as qt from cliente;');

        foreach($quantidade as $q)
        {
            $qt = $q['qt'];
        }

        

        if($qt == 0)
        {
            //sha1(md5($criptoSenha));
            $clientes = array(
                ['Nathan Costa','38045075813','nathan1234'],
                ['Bart Ferreira','12345678901','nathacdcdc1234'],
                ['Mel Dias','98765432102','nathcdcd4'],
                ['Gabi Ferraz ','38043475813','nathacdcd234'],
                ['Costa Nathan','38045075813','natccd1234'],
                ['Joao B ','12345678901','fdcd1234'],
                ['Dias Gomes','98765432102','1234']);

                set_time_limit(0);

                $maxCli = count($clientes) -1;

                $nome = array();
                $cpf = array();
                $senha = array();

                foreach($clientes as $cli)
                {
                    array_push($nome,$cli[0]);
                    array_push($cpf,$cli[1]);
                    array_push($senha,sha1(md5($cli[2])));
                }

        
                $stmt = self::$conn->prepare('INSERT INTO `cliente` (nome_cliente,cpf,senha,id_uf,dt_cadastro)
                VALUES(:nome,:cpf,:senha,:id_uf,:dt_cadastro);');

                $sequencia = 1;

                $cliente_vendas = array();
                $arraystatic = array();
                $cliente_vendas_id= array();
                $cliente_vendas_data = array();
                $data = date("Y-m-d");
                for($i=0; $i<$this->qt_clientes_total ;$i++)
                {
                    $a = rand(0,$maxCli);
                    $b = rand(0,$maxCli);
                    $c = rand(0,$maxCli);
                    $d = rand(1,27);
                    //data 01-01-2018 atual
                    $dd = rand(01,28);
                    $array_data_end =  explode( "-",$data);

                    $ano_atual = $array_data_end[0];
                    $m_end = $array_data_end[1];
                    $d_atual = $array_data_end[2];

                    $m = rand(1,$m_end);
                    $p1 = substr($m_end,0,1);

                    if($p1==0)
                    {
                        $m_end = substr($m_end,0,1);
                    }

                    if($m == $m_end)
                    {
                        if($dd > $d_atual)
                        {
                            $dd = $dd -$d_atual;
                        }
                            
                    }

                    if(strlen($dd) == 1)
                    {
                    
                        $dd ="0$dd";
                    }

                    if(strlen($m) == 1)
                    {
                    
                        $m ="0$m";
                    }

                    if($sequencia == 2)
                    {
                        $ano_atual = 2018;

                        $sequencia = 0;
                    }

                    $data_builder = $ano_atual."-".$m."-".$dd;

                    //echo  $data_builder."</br>";

                  $stmt->execute(array(':nome' =>$nome[$a],':cpf' =>$cpf[$b],':senha'=>$senha[$c],':id_uf'=>$d,':dt_cadastro'=>$data_builder));
 
                  
                 $sequencia++;

                    $arraystaticID = [$i];
                    $array_data_cliente = [$data_builder];

                    array_push($cliente_vendas_id,$arraystaticID);
                    array_push($cliente_vendas_data,$array_data_cliente);

                }

                //var_dump($cliente_vendas);

                return [0=>$cliente_vendas_id,1=>$cliente_vendas_data];
            
        }else{
            return "Já esxistem clientes cadastrados</br>";
        }

    }

    public function popularTableVendas($cliente_vendas)
    {
        $quantidade = self::$conn->query('select count(*) as qt from venda;');

        foreach($quantidade as $q)
        {
            $qt = $q['qt'];
        } 

        if($qt == 0)
        {
            $stmtVendas = self::$conn->prepare('INSERT INTO `venda` (valor,id_cliente,data_compra)
            VALUES(:valor,:id_cliente,:data_compra);');


            $indice = 0;
            for($i=0;$i <=30;$i++)
            {
                $valor = rand(30,100);
                $id_cliente = rand(1,10);
                $stmtVendas->execute(array(':valor' =>$valor,':id_cliente' =>$id_cliente,':data_compra'=>$cliente_vendas[$indice]));

                if($indice == 9)
                {
                    $indice =0;
                }

                //echo "$i</br>";
            }

        }else{
            return "Vendas já cadastradas</br>";
        }

    }

    public function gerarVendasclientes()
    {
        $quantidade = self::$conn->query('select count(*) as qt from venda;');

        foreach($quantidade as $q)
        {
            $qt = $q['qt'];
        } 

        if($qt == 0)
        {

            $stmtVendas = self::$conn->prepare('INSERT INTO `venda` (valor,id_cliente,data_compra)
            VALUES(:valor,:id_cliente,:data_compra);');

            $controlador = false;
            $start_dados = 1;
            $end_dados = 2;
            $total_vendas = 0;
        
            //echo "entrou no loop infinito</br>";
            while($controlador == false)
            {

                $clientes = self::$conn->query("select id_cliente, dt_cadastro from cliente limit $start_dados,$end_dados;");

                foreach($clientes as $c)
                {
                     $id_cliente = $c['id_cliente'];
                     $dt_cliente = $c['dt_cadastro'];

                     $qtProdutosComprados = rand(1,5);

                   // $total_vendas = $total_vendas + $qtProdutosComprados;

                    

                     if($total_vendas >= $this->qt_vendas_total)
                     {
                         $controlador = true;
                         break;
                         
                     }else{

                            for($i=0; $i<= $qtProdutosComprados; $i++)
                            {
                                if($total_vendas >= $this->qt_vendas_total)
                                {
                                    return "Dados poludados com sucesso";

                                        $controlador = true;
                                        break;

                                }else{
                                    $valor = rand(30,100);

                                    $dataGerada = self::GeradorDatas($dt_cliente);

                                    if(empty($dataGerada))
                                    {
                                        $dataGerada = '2018-01-01';
                                    }

                                    $stmtVendas->execute(array(':valor' =>$valor,
                                        ':id_cliente' =>$id_cliente,
                                        ':data_compra'=>$dataGerada));


                                        $total_vendas++;

                                       // echo "total = $total_vendas";


                                        if($total_vendas >= $this->qt_vendas_total)
                                        {
    
                                                $controlador = true;
                                                return "Dados poludados com sucesso";
                                                break;

                                        }

                                    }
                            }      
                    }

                } 

                $start_dados = $start_dados +2;

            }
           // echo "saiu no loop infinito</br>";


            

        }

    }


    public function GeradorDatas($dateStart)
    {
        try{
            //Star date
            $dateStart 		= implode('-', array_reverse(explode('/', substr($dateStart, 0, 10)))).substr($dateStart, 10);
            $dateStart 		= new DateTime($dateStart);
        
            //End date
            $dateEnd 		= date("Y-m-d");
            $dateEnd 		= implode('-', array_reverse(explode('/', substr($dateEnd, 0, 10)))).substr($dateEnd, 10);
            $dateEnd 		= new DateTime($dateEnd);
        
            //Prints days according to the interval
            $i=0;
            $dateRange = array();
            while($dateStart <= $dateEnd)
            {
                $dateRange[] = $dateStart->format('Y-m-d');
                $dateStart = $dateStart->modify('+1day');
                $i++;
            }

            $dataCompra = rand(1,$i);

            if(empty($dataCompra))
            {
                $dataCompra="2018-01-01";
            }


            return $dateRange[$dataCompra];

        }catch(Exception $e)
        {
            echo "Erro ao gerar uma data";
        }
    }


    public function gerarUserAdmin()
    {
        try{

            $senha = sha1(md5('123456'));
            $stmt = self::$conn->prepare('INSERT INTO `cliente` (nome_cliente,cpf,senha,id_uf,dt_cadastro)
            VALUES(:nome,:cpf,:senha,:id_uf,:dt_cadastro);');

            $stmt->execute(array(':nome' =>'Roberto Allan Lemos',':cpf' =>'123456789',':senha'=>"$senha",':id_uf'=>1,':dt_cadastro'=>'2019-05-17'));

            return $dados = ['cpf'=>'123456789','senha'=>"$senha"];

        }catch(Exception $e)
        {
            return false;
        }
    }



}

/*
$conn = new GeraDataBase();
echo $conn->GerarTabelas();
echo $conn->popularTableUf();
$clientes = $conn->popularTableCliente();


echo $conn->gerarVendasclientes();

//polular o banco */

