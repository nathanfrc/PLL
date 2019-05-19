<?php

include_once "GeraDatabase.php";

class ClienteLogin extends GeraDatabase{


    private $conn;
    private $cpf;
    private $senha;

    function __construct(){}

    function setDados($cpf,$senha)
    {
        $this->cpf = $cpf;
        $this->senha = self::criptSenha($senha);
        $this->conn = parent::getRetornoConexao();
    }

   
    function validarUsuario()
    {
        try{

            $cpf_valida =  $this->cpf;
            $senha_valida =$this->senha;
            
            echo "</br>";
        $usuario = $this->conn->query("select id_cliente,nome_cliente,senha from cliente where '$cpf_valida' and senha = '$senha_valida'");

            foreach($usuario as $u)
            {
                if(!strcmp($senha_valida, $u["senha"])) 
                    { 
                        session_start(); 
                        
                        $_SESSION["id_usuario"]= $u["id_cliente"]; 
                        $_SESSION["nome_usuario"] = stripslashes($u["nome_cliente"]); 

                        header("Location:?pagina=home"); 
                        exit; 
                    } 
                    else
                    { 
                        return false;
                        
                    } 
            }

        }catch(Exception $e)
        {
            return 0;
        }

    }


    function criptSenha($senha)
    {

        return sha1(md5($senha));
    }



    function sessionExist()
    {
        session_start();

        if(isset($_SESSION['id_usuario']) && isset($_SESSION['nome_usuario']))
        {
            $arrayDados = array();
            $id = $_SESSION['id_usuario'];
            $nome = $_SESSION['nome_usuario'];

            array_push($arrayDados,$id);
            array_push($arrayDados,$nome);
    
            return  $arrayDados;
        }else{
            return false;
        }

    }




}