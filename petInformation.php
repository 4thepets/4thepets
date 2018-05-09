<?php
    include_once "model/Usuario.php";
    include_once "model/AnimalEstimacao.php";
    include_once "enumeration/CategoriaEnum.php";
    session_start();
    if($_SESSION['USUARIO'])
        $usuario = unserialize($_SESSION['USUARIO']);
    else
        header("location: index.php");

    if(isset($_GET['petValue'])){
        try{
            if(!$animalEstimacao = AnimalEstimacao::retornaPetAdotadoInfo($_GET['petValue']))
                header('location: home.php');
        }catch(Exception $e){
            $STATUS_MESSAGE = $e->getMessage();
        }
    }else
        header('location: home.php');

    if(isset($_POST['petRemove'])){
        try{
            if(AnimalEstimacao::removeCadastro($animalEstimacao->getCode()))
                header('location: myPets.php');
        }catch(Exception $e){
            $STATUS_MESSAGE = $e->getMessage();
        }
    }

    if(isset($_POST['markPet'])){
        try{
            if(AnimalEstimacao::cadastrarInteresse($usuario->getCode(), $animalEstimacao->getCode()))
                $ADOPT_MESSAGE = "Você adotou um pet!";
        }catch(Exception $e){
            $STATUS_MESSAGE = $e->getMessage();
        }
    }

    if(isset($_POST['desmarkPet'])){
        try{
            if(AnimalEstimacao::removerInteresse($usuario->getCode(), $animalEstimacao->getCode()))
                $ADOPT_MESSAGE = "Você desfez a adoção.";
        }catch(Exception $e){
            $STATUS_MESSAGE = $e->getMessage();
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
        <link rel="stylesheet" type="text/css" href="style/addPets.css"/>
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
                <div class="pageorg">
                    <img  src="images/logo_4tp_white.png" class="img"/><br>
                    <h1 class="title"><?php if(isset($ADOPT_MESSAGE)) echo $ADOPT_MESSAGE; else echo "Informações do pet"; ?></h1>
                    <p class="subtitle"><?php if(isset($STATUS_MESSAGE)) echo $STATUS_MESSAGE; else echo "Tudo o que você precisa saber sobre o pet."; ?></p><br/><br/>
                    <div class="divorgimg">
                        <img src="<?php echo $animalEstimacao->getCaminhoFoto(); ?>"/>                        
                    </div>
                    <div class="divorg">
                        <p class="subsubtitle"><b>Nome:  </b><?php echo $animalEstimacao->getNome(); ?></p><br/>
                        <p class="subsubtitle"><b>Dono:  </b><?php echo $animalEstimacao->getNomeDono(); ?></p><br/>
                        <p class="subsubtitle"><b>Endereço: </b><?php if($animalEstimacao->getEndDono() == null) echo "Não definido pelo usuário."; else echo $animalEstimacao->getEndDono(); ?></p><br/>
                        <p class="subsubtitle"><b>Tefefone: </b><?php if($animalEstimacao->getTelDono() == null) echo "Não definido pelo usuário."; else echo $animalEstimacao->getTelDono(); ?></p><br/>
                        <p class="subsubtitle"><b>Email do Dono: </b><?php echo $animalEstimacao->getEmailDono(); ?></p><br/>
                        <?php 
                            if($usuario->getCode() == $animalEstimacao->getKeyDono()){ ?>
                                <form method="post">
                                    <input type="submit" name="petRemove" value="Remover Pet" class="removePet"/>
                                    <a href="updatePet.php" class="editPet">Alterar dados</a>
                                </form>
                        <?php } else if(!$animalEstimacao->isAdopted($usuario->getCode(), $animalEstimacao->getCode())) { ?>
                            <form method="post">
                                <input type="submit" name="markPet" value="Adotar Pet" class="adoptPet"/>
                            </form>
                        <?php } else { ?>
                            <form method="post">
                                <input type="submit" name="desmarkPet" value="Desfazer adoção" class="removePet"/>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            </article>
        </section>
    </body>
</html>