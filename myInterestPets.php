<?php
    include_once "model/Usuario.php";
    include_once "enumeration/CategoriaEnum.php";
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
        <link rel="stylesheet" type="text/css" href="style/myPets.css"/>
        <title>4thePets - Juntando grandes amigos</title>
    </head>

    <script src="https://npmcdn.com/minigrid@3.0.1/dist/minigrid.min.js"></script>
    <script src="js/script.js"></script>

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
                    <li><a href="myInterestPets.php">Pets interessados</a></li>
                    <li><a href="home.php?quit=true">Sair</a></li>
                </ul>
            </article>
            <!-- Page Content -->
            <article class="pageContent">
                <div class="pageContentApresentation">
                    <figure>
                        <img src="images/logo_4tp_white.png"/>
                        <figcaption>Pets que gostei</figcaption>
                        <p class="indexApresentationSubtext">Hoje é um bom dia para dar um lar a quem precisa de você.</p>
                    </figure>
                </div>
                <div class="pageContentPets">
                    <h1>Adote um amigo!</h1>
                    <div class="card">
                        <a href="petInformation.php">
                            <img src="images/bkg/01.jpg"/>
                            <p>Leko, 15</p>
                        </a>
                    </div>
                    <div class="card">
                        <a href="petInformation.php">
                        <img src="images/bkg/02.jpg"/>
                        <p>Leko, 15</p>
                        </a>
                    </div>
                    <div class="card">
                        <img src="images/bkg/03.jpg"/>
                        <p>Leko, 15</p>
                    </div>
                    <div class="card">
                        <img src="images/sample/default.png"/>
                        <p>Leko, 15</p>
                    </div>
                    <div class="card">
                        <img src="images/bkg/01.jpg"/>
                        <p>Leko, 15</p>
                    </div>
                    <div class="card">
                        <img src="images/sample/default.png"/>
                        <p>Leko, 15</p>
                    </div>
                    <div class="card">
                        <img src="images/sample/default.png"/>
                        <p>Leko, 15</p>
                    </div>
                    <div class="card">
                        <img src="images/bkg/01.jpg"/>
                        <p>Leko, 15</p>
                    </div>
                    <div class="card">
                        <img src="images/bkg/01.jpg"/>
                        <p>Leko, 15</p>
                    </div>
                    <div class="clear"></div>
                </div>
            </article>           
        </section>
    </body>
</html>

