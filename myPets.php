<?php
    include_once "model/Usuario.php";
    session_start();
    if($_SESSION['USUARIO'])
        $usuario = unserialize($_SESSION['USUARIO']);
    else
        header("location: index.php");

    if(isset($_GET['interested'])){
        if($_GET['interested'] == "t")
            $interestHandler = true;
        else 
            $interestHandler = false;
    }else{
        $interestHandler = false;
    }
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
                <?php if(!$interestHandler){ ?>
                <h1>Meus pets</h1>
                <p>Nada melhor do que prestigiar seus filhinhos.</p><br/>
                <a href="addPet.php">Adicionar um animal para adoção</a>
            <?php }else{ ?>
                <h1>Meu interesse em pets</h1>
                <p>Esta página encontra-se em processo de construção.</p>
                <p>Desculpe-nos pelo transtorno.</p>
                <a href="home.php">Voltar</a>
            <?php } ?>
            </article>
        </section>
    </body>
</html>