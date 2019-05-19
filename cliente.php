<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700' rel='stylesheet' type='text/css'>
    <link href="public/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/estilo.css" rel="stylesheet">
      
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
      
  </head>
  <body data-spy="scroll" data-target=".menu-navegacao" data-offset="80">
      <!-- Menu da aplicação -->
      <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-navegacao" >
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>                
                <a class="navbar-brand" href="#page-top">PLL</a>
            </div>  
            
            <div class="collapse navbar-collapse menu-navegacao" id="menu-navegacao">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#page-top"></a></li>
                    <li>
                        <a class="" href="?pagina=home">Relatórios</a>
                    </li>
                    <li>
                        <a class="" href="?pagina=cliente">Cliente</a>
                    </li>
                   
                    <li>
                        <a class="" href="?pagina=logout">Sair</a>
                    </li>
                </ul>
            </div>
            
        </div>
      </nav>
      <!-- // Menu da aplicação -->      
      <!-- título da página -->
      <div class="divslider">
        <div class="container">
            <div class="col-xs-12">
                <div class="page-header"><center><h1>Busca</h1></center></div>
            </div>
        </div>
      </div>      
      <!-- // título da página -->
      
      <!-- portfolio -->
      <br>
      <br>
      <section id="portfolio" class="section_interna">

                   
          
        
          
        <div class="container">
            <div class="row"><!--onchange="this.form.submit()"-->
                <form action="?pagina=cliente" method="POST">
                        <div class="col-sm-2">
                                <div class="input-group">
                                    <label for="pwd">Data:</label>
                                    <input type="date" class="form-control" placeholder="Digite o nome da cidade" name="data_start" id="data_start" value="<?php echo $dt_start ?>">
                                </div> 
                        </div>

                        <div class="col-sm-2">
                                <div class="input-group">
                                    <label for="pwd">Data - Final:</label>
                                    <input type="date" class="form-control" placeholder="Digite o nome da cidade" name="data_end" id="data_end" value="<?php echo $dt_end ?>">
                                </div>   
                        </div>

                        <div class="col-sm-2">
                                <div class="input-group">
                                    <label for="pwd">Nº Compra:</label>
                                    <input type="text" class="form-control" placeholder="Nº da compra" name="id_busca_venda" id="id_busca_venda"  value="<?php echo $id_venda ?>">
                                </div>   
                        </div>

                                    <div class="col-sm-1">
                                        <div class="input-group">
                                        <label for="pwd">Total pág:</label>
                                            <select name="totalPorPagina" id="totalPorPagina" class="form-control input-sm" onchange="this.form.submit()">
                                                <option value="100" <?php echo ($parse == true) ? "selected": "" ?>>100</option>
                                                <option value="200" <?php echo ($t200 == true) ? "selected": "" ?>>200</option>
                                                <option value="300" <?php  echo ($t300 == true) ? "selected": "" ?>>300</option>
                                            </select>
                                        </div>
                             </div>


                       
            </div><!-- fim da row-->
    <br>
                    <div class="row">
                        <div class="col-sm-4">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit">Pesquisar</button>
                                    </span>
                                </div>   
                        </div>  

                    </div>

                </form>

             <!--   <div class="row">
                        <div class="col-sm-12">-->

                    

                            <table class="table table-hover">
                                    
                                <thead>
                                    <tr>
                                    <h3><th>#</th>
                                        <th>Venda</th>
                                        <th>Data</th>
                                        <th>Valor</th>
                                        <th>Nome</th>
                                        <th>Cidade<th>
                                        
                                    </tr>
                                </thead>
                                <tbody>

                            <?php 

                                    $i=1;

                                   foreach($retorno as $ret)
                                    {
  
                                ?>
                                         <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $ret['id_venda']; ?></td>
                                                <td><?php echo $ret['data']; ?></td>
                                                <td><?php echo 'R$ '.number_format($ret['valor'],2, '.', '');?></td>
                                                <td><?php echo $ret['nome_cliente'];?></td>
                                                <td><?php echo $ret['nome_cidade']."-".$ret['sigla'];?></td>
                                                
                                            </tr>
                                           

                                    <?php $i++; }?>
                                
                                </tbody>
                            </table>  

                    <center>
                             <?php
            
                                if(isset($retornoDados['criarLink']))
                                {
                                    echo $retornoDados['criarLink'];
                                }
                            
                            ?>

                    </center>

                       
                         
            </div>
        </div>
      </section>
      <!-- // portfolio -->
    
      
      <footer>
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    
                </div>
                <div class="col-sm-2 text-right">
                    <small>Desenvolvido por:</small><br/>
                    <strong>Nathan Costa</strong>
                </div>
            </div>  
        </div>
      </footer>
      
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      <script src="public/js/bootstrap.min.js"></script>
      <script src="public/js/main.js"></script>
  </body>
</html>