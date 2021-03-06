<?php
include 'connection.php';
session_start();
if (!isset($_SESSION['user']) || !isset($_SESSION['pass'])) {
	header("Location:login.php");
}
mysqli_query($conn, "SET NAMES utf8");
mysqli_query($conn, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
if (isset($_FILES['arquivo'])) {
    $book = $_POST['book-name'];
    $author = $_POST['author-name'];
    if (strlen($book) > 1 and strlen($author) > 1) {
        $ext = strtolower(substr($_FILES['arquivo']['name'], -4));
        if ($ext == '.pdf') {
          $book_name = ucwords(strtolower($_POST['book-name']));
          $author_name = ucwords(strtolower($_POST['author-name']));
          $genre = $_POST['genre'];
          session_start();
          $ra = $_SESSION['user'];
          $new_name = md5(time()) . $ext;
          $direc = "home/www/bibliotecajgv.atwebpages.com/uploads/";
          chdir('/');
          $arquivo = $_FILES['arquivo']['tmp_name'];
          move_uploaded_file($arquivo, $direc.$new_name);
          $hour = date('H');
          $today = getdate();
          $d = $today['mday'];
          $m = $today['mon'];
          $y = $today['year'];
          if ($hour >= 0 and $hour < 3) {
              $d -= 1;
          }
          $now = "$y-$m-$d";
          $sql_code = "INSERT INTO pendentes (id, nome_livro, autor_livro, genero, usuario, arquivo, data) VALUES(null, '$book_name', '$author_name', '$genre', '$ra', '$new_name', '$now')";
          $sql = mysqli_query($conn, $sql_code);
          $msg = "$book_name enviado com sucesso";
        }   
    }else {
        if (isset($book)) {
         $msg = "Digite o nome do livro e do autor antes de o partilhar.";   
        }
    }
  }
?>



<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Biblioteca JGV</title>
  <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
     <!-- GOOGLE FONTS-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
     
           
          
    <div id="wrapper">
         <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="adjust-nav">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">
                        <img src="assets/img/Book-icon.png" />
                    </a>
                </div>
              
                 <span class="logout-spn" >
                      <?php 
                $ra = $_SESSION['user'];
                $sql = "SELECT * FROM alunos WHERE ra_aluno LIKE '$ra'";
                $sql = mysqli_query($conn, $sql);
                $row = mysqli_num_rows($sql);
                if ($row > 0) {
                    while ($linha = mysqli_fetch_array($sql)) {
                        $email = $linha['email_aluno'];
                        $grav_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?s=32";
                        echo "<a href='settings.php'><img src='$grav_url'/><a/>";
                    }
                }
                ?>
                  <a href="index.php" style="color:#fff;">SAIR</a>  

                </span>
            </div>
        </div>
        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                 

                    <li>
                        <a href="home.php" ><i class="fa fa-desktop "></i>Principal </a>
                    </li>
                    <li>
                        <a href="search.php"><i class="fa fa-search "></i>Pesquisa por livro </a>
                    </li>
                    <li>
                        <a href="list_all.php"><i class="fa fa-table "></i>Listar todos os livros  </a>
                    </li>
                    <li class="active-link">
                        <a href="upload.php"><i class="fa fa-upload "></i>Envio de livros  </a>
                    </li>
                    <li>
                        <a href="real.php"><i class="fa fa-book"></i>Biblioteca física </a>
                    </li>
                </ul>
                            </div>

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                     <h3>Envio de livros </h3>   
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
                  <form action="upload.php" method="POST" enctype="multipart/form-data">
                   <div class="form-group">
                            <label>Nome do livro</label>
                            <input class="form-control" name="book-name" />
                            <p class="help-block">Iremos utilizar o nome do livro para salvá-lo posteriormente junto com o nome de usuário que o publicou e a data.</p>
                            <label>Nome do autor</label>
                            <input class="form-control" name="author-name" />
                            <p class="help-block">Vamos utilizar esse campo para futuras buscas, preencha-o.</p>
                            <div style="width:30%">
                            <label>Gênero do livro</label>
                            <select name="genre" class="form-control">
                                <option>Selecione uma opção...</option>
                                <option>Administração e Negócios</option>
                                <option>Contos e Crônicas</option>
                                <option>Engenharia e Tecnologia</option>
                                <option>Ficção Científica</option>
                                <option>Romance</option>
                                <option>Literatura Infanto Juvenil</option>
                                <option>Filosofia</option>
                                <option>Ciências Humanas e Sociais</option>
                                <option>Ciências Biológicas</option>
                                </select>
                            </div>
                            <p class="help-block">Utilizaremos o gênero para classificação dos livros, então, peço que selecione-o.</p>
                        </div>
                  <label class="control-label"> Escolha um livro</label><br/>
                  <label for="file-upload" class="btn btn-default">
                      Procurar por livro no meu computador
                  </label>
                  <input id="file-upload" type="file" required name="arquivo" style="display:none;" />
                  <input type="submit" class="btn btn-info" name="enviar" value="Enviar">
                  </form>
                  <hr>
                    <?php
                    if ($msg != false and $msg != 'Digite o nome do livro e do autor antes de o partilhar.') {
                      echo "<div class='alert alert-success'>";
                      echo "<h3>Status do upload: $msg</h3>";
                      echo "</div>";
                    }else {
                        if ($msg == '') {
                            
                        }else {
                          echo "<div class='alert alert-danger'>";
                            echo "<h3>Status do upload: $msg</h3>";
                            echo "</div>";   
                        }
                    }
                    ?>
                  </hr>
                 <!-- /. ROW  -->           
    </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
    <div class="footer">
      
    
             <div class="row">
                <div class="col-lg-12" >
                    &copy;  2017 José Geraldo Vieira
                </div>
        </div>
        </div>
          

     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- BOOTSTRAP SCRIPTS -->
    <script src="assets/js/bootstrap.min.js"></script>
      <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    
   
</body>
</html>
