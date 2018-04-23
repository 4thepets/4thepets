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
    <body>
        <section class="homeContent">
            <article class="menuContent">
                <figure>
                    <img src="<?php $usuario->getCaminhoImagem(); ?>"/>
                    <p>Bem vindo <?php $usuario->getNome(); ?>!</p>
                </figure>
                <ul>
                    <li><a href="myProfile.php">Meu Perfil</a></li>
                    <li><a href="myPets.php?interested=f">Meus Pets</a></li>
                    <li><a href="myPets.php?interested=t">Pets interessados</a></li>
                    <li><a href="home.php?quit=true">Sair</li>
                </ul>
            </article>
            <!-- Page Content -->
            <article class="pageContent">
                
            </article>
        </section>
    </body>
</html>