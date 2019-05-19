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
                <div class="page-header"><center><h1>Relatórios</h1></center></div>
            </div>
        </div>
      </div>      
      <!-- // título da página -->
      
      <!-- portfolio -->
      <br>
      <br>
      <section id="portfolio" class="section_interna">
    
          
        <div class="container">

         
            <div class="row">

            <form action="?pagina=relatorio" method="POST">
                 <div class="col-sm-4">
                        <div class="input-group">
                                    <input type="date" class="form-control" placeholder="Digite o nome da cidade" name="buscar" id="buscar" value="<?php echo $busca ?>" onchange="this.form.submit()">
                                    <span class="input-group-btn">
                                        <button class="btn btn-info" type="submit">Pesquisar</button>
                                    </span>
                        </div>
                       
                </div>

                <div class="col-sm-4">
                    <a href="?pagina=relatorio&buscar=100">Top 100 /</a>
                    <?php echo "<a href='?pagina=export&type=csv&buscar=$busca&paginaSelecionada=$paginaLink&totalPorPagina=$totalLink'>Exportar CSV /</a>
                            <a href='?pagina=export&type=json&buscar=$busca&paginaSelecionada=$paginaLink&totalPorPagina=$totalLink'>Exportar JSON /</a>";?>
                </div>


                <?php 
                       if($top)
                       {
                          
                           
                           
                           ?>
                                <table class="table table-hover">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Total por página:</label>
                                            <select name="totalPorPagina" id="totalPorPagina" class="form-control input-sm" onchange="this.form.submit()">
                                                <option value="100" <?php echo ($parse == true)  ? "selected": ""; ?>>100</option>
                                                <option value="200" <?php echo ($t200 == true) ? "selected": ""; ?>>200</option>
                                                <option value="300" <?php  echo ($t300 == true) ? "selected": "";  ?>>300</option>
                                            </select>
                                        </div>
                                    </div>
                        </form> 

                                <thead>
                                    <tr>
                                    <h3> <th scope="col">#</th>
                                        <th scope="col">ID</th>
                                        <th scope="col">Nome</th>
                                        <th scope="col">Média</th>
                                        <th scope="col">Total</th></h3>
                                    </tr>
                                </thead>
                                <tbody>

                            <?php 

                                    $i=1;

                                    foreach($retorno as $ret)
                                    {
                                        
                                ?>
                                            <tr>
                                                <th scope="row"><?php echo $i; ?></th>
                                                <th scope="row"><?php echo $ret['id_cliente']; ?></th>
                                                <td><?php echo $ret['nome_cliente'];?></td>
                                                <td><?php echo 'R$ '.number_format($ret['result'],2, '.', '');?></td>
                                                <td><?php echo $ret['id_venda'];?></td>
                                            </tr>

                                    <?php $i++; }?>
                                
                                </tbody>
                            </table>  

                        
                   <?php    }else{ ?>
                    
                <table class="table table-hover">

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Total por página:</label>
                                            <select name="totalPorPagina" id="totalPorPagina" class="form-control input-sm" onchange="this.form.submit()">
                                                <option value="100" <?php echo ($parse == true) ? "selected": ""; ?>>100</option>
                                                <option value="200" <?php echo ($t200==true) ? "selected": "";?>>200</option>
                                                <option value="300" <?php  echo ($t300==true) ? "selected": "";?>>300</option>
                                            </select>
                                        </div>
                                    </div>
                            </form>
                            <thead>
                                <tr>
                                  <h3>  <th scope="col">#</th>
                                    <th scope="col">Data</th>
                                    <th scope="col">Média</th>
                                    <th scope="col">Total</th></h3>
                                </tr>
                            </thead>
                            <tbody>

                        <?php 

                                $i=1;

                                foreach($retorno as $ret)
                                {
                                
                               ?>
                                        <tr>
                                            <th scope="row"><?php echo $i ?></th>
                                            <td><?php echo $ret['data']?></td>
                                            <td><?php echo 'R$ '.number_format($ret['result'],2, '.', '');?></td>
                                            <td><?php echo $ret['id_venda']?></td>
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

                        <?php }?>   

                          

                                     <!-- <div class="well well-sm">
                                            <h2>Não há buscas para essa data</h2>
                                        </div> -->

                         
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