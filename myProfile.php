<?php
    include_once "model/Usuario.php";
    session_start();
    if($_SESSION['USUARIO'])
        $usuario = unserialize($_SESSION['USUARIO']);
    else
        header("location: index.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="style/style.css"/>
         <link rel="stylesheet" type="text/css" href="style/myProfile.css"/>

        <title>4thePets - Juntando grandes amigos</title>
    </head>
    <body background="images/bkg/0<?php echo rand(1, 5); ?>.jpg">
        <div class="filterOpacity"></div>
        <section class="homeContent">
            <article class="menuContent">
                <figure>
                    <img src="<?php echo $usuario->getCaminhoImagem(); ?>"/>
                    <p>Bem vindo <?php echo $usuario->getNome(); ?>!</p>
                </figure>
                <ul>
                    <?php 
                        if($_SERVER['PHP_SELF'] != "/home.php") 
                            echo "<li><a href='home.php'>Página Inicial</a></li>";
                    ?>                   
                    <li><a href='myProfile.php'>Meu Perfil</a></li>
                    <li><a href="myPets.php">Meus Pets</a></li>
                    <li><a href="myPets.php?interested=t">Pets interessados</a></li>
                    <li><a href="home.php?quit=true">Sair</a></li>
                </ul>
            </article>
            <!-- Page Content -->
            <article class="pageContent">
                <div class="pageorg">
                    <img class="img"   src="images/logo_4tp_white.png"/>
                    <h1 class="title">Minhas informações</h1>
                    <p class="subtitle">Atualizar as informações é sempre bem vindo.</p><br/><br><br>
                    <p class="subsubtitle"><b>Alias: </b> Marjorie  <br> <br>  <a href="tempProfileName.php" class="botao"> Alterar Nome</a></p> <br><br>
                    <p class="subsubtitle"><b>Email: </b> teste   <br> <br>  <a href="tempProfileEmail.php" class="botao"> Alterar Email</a></p><br><br>
                    <p class="subsubtitle"><b>Senha: </b>   <br><br><a href="tempProfilePassword.php" class="botao"> Alterar Senha</a><p><br>
                </div>
            </article>
        </section>
    </body>
</html>