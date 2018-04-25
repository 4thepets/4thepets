<?php
    include_once "model/Usuario.php";
    session_start();
    if($_SESSION['USUARIO'])
        $usuario = unserialize($_SESSION['USUARIO']);
    else
        header("location: index.php");
    
    if(isset($_GET['quit'])){
        if($_GET['quit'] == true){
            session_destroy();
            header("location: index.php");            
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="style/style.css"/>
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
                <div class="pageContentApresentation">
                    <figure>
	    				<img src="images/logo_4tp_white.png"/>
                        <figcaption>Onde os pets encontram uma família.</figcaption>
                        <p class="indexApresentationSubtext">Muitos pets procuram um novo amigo. Um amigo que nem você.</p>
                    </figure>
                    <div class="readMore">
                        <a href="#">Leia mais</a>
                    </div>
                </div>
                <!--
                <div class="pageContentPets">
                    <h1>Conheça alguns amigos!</h1>
                    <figure>
                        <img src="images/bkg/01.jpg"/>
                        <p>Leko, 15</p>
                    </figure>
                </div>
                -->
            </article>
        </section>
    </body>
</html>