<?php
    include_once "model/Usuario.php";
    include_once "model/AnimalEstimacao.php";
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
    
    <script src="https://npmcdn.com/minigrid@3.0.1/dist/minigrid.min.js"></script>
    <script src="js/jquery-3.3.1.min.js"></script>
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
                        <figcaption>Onde os pets encontram uma família.</figcaption>
                        <p class="indexApresentationSubtext">Muitos pets procuram um novo amigo. Um amigo que nem você.</p>
                    </figure>
                    <div class="readMore">
                        <a href="findPet.php">Encontre um amigo</a>
                    </div>
                </div>
                <div class="pageContentPets">
                    <h1>Conheça alguns amigos!</h1>
                    <?php
                        $myPets = AnimalEstimacao::retornarPets();
                        foreach ($myPets as $myPet) { 
                            if($myPet->getKeyDono() != $usuario->getCode()){?>
                            <div class="card">
                                <a href="petInformation.php?petValue=<?php echo $myPet->getCode(); ?>">
                                <img src="<?php echo $myPet->getCaminhoFoto(); ?>"/>
                                <p><?php echo $myPet->getNome().", ".$myPet->getIdade(); ?></p>
                                </a>
                            </div>
                    <?php }} ?>
                    <div class="clear"></div>
                </div>
            </article>
        </section>
    </body>
</html>