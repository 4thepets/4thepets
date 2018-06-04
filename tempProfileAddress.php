<?php
	include_once "model/Usuario.php";
    session_start();
    if($_SESSION['USUARIO'])
        $usuario = unserialize($_SESSION['USUARIO']);
    else
        header("location: index.php");

    if(isset($_POST['tempChangeAddress'])){
        try{
            if($usuario->alterarEndereco($_POST['tempAddress'])){
                $STATUS_MESSAGE = "Alterado com sucesso.";
                $_SESSION['USUARIO'] = serialize($usuario);
            }
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
        <link rel="stylesheet" type="text/css" href="style/tempProfileName.css"/>
        <title>Alterar Nome</title>
    </head>
    <body background="images/bkg/01.jpg">
        <div class="filterOpacity"></div>
        <section class="homeContent">
            <article class="menuContent">
                <figure>
                    <img src="<?php echo $usuario->getCaminhoImagem(); ?>"/>
                    <p>Bem vindo, <?php echo $usuario->getNome(); ?>!</p>
                </figure>
                <ul>
                    <?php 
                        if($_SERVER['PHP_SELF'] != "/home.php") 
                            echo "<li><a href='home.php'>Página Inicial</a></li>";
                    ?>     
                    <li><a href='findPet.php'>Encontre um amigo</a></li>              
                    <li><a href='myProfile.php'>Configurações</a></li>
                    <li><a href="myPets.php">Meus Pets</a></li>
                    <li><a href="myInterestPets.php">Pets que possuo interesse</a></li>
                    <li><a href="home.php?quit=true">Sair</a></li>
                </ul>
            </article>
            <!-- Page Content -->
            <article class="pageContent">
                <div class="pageorg">
                     <img class="img" src="images/logo_4tp_white.png"/>
               	    <form method="post">
                        <?php
                            if(isset($STATUS_MESSAGE))
                                echo "<label for='tempName' class='title'>".$STATUS_MESSAGE."</label><br/>";
                            else{
                                echo "<label for='tempName' class='title'>Alterar Endereço</label>";
                                echo "<p>Insira um endereço.</p>";
                            }
                        ?>
               		    <input type="text" name="tempAddress" required placeholder="Digite um novo endereço."/><br><br>
               		    <input type="submit" name="tempChangeAddress" value="Alterar Endereco" class="botao"/>
               	    </form>
                </div>
            </article>
        </section>
    </body>
</html>