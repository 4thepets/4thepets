<?php
	include_once "model/Usuario.php";
    session_start();
    if($_SESSION['USUARIO'])
        $usuario = unserialize($_SESSION['USUARIO']);
    else
        header("location: index.php");

    if(isset($_POST['tempChangeName'])){
        try{
            if($usuario->alterarNome($_POST['tempName'])){
                $SUCESS_MESSAGE = "Sucesso";
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
    <body background="images/bkg/0<?php echo rand(1, 5); ?>.jpg">
        <div class="filterOpacity"></div>
        <section class="homeContent">
            <article class="menuContent">
                <figure>
                    <img src="<?php echo $usuario->getCaminhoImagem(); ?>"/>
                    <p><?php if(isset($SUCESS_MESSAGE)) echo "Alterado para: ".$usuario->getNome(); else echo "Bem vindo ".$usuario->getNome();?>!</p>
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
                     <img class="img"   src="images/logo_4tp_white.png"/><br>
                <?php
                    if(isset($STATUS_MESSAGE))
                        echo $STATUS_MESSAGE;
                ?>
               	<form method="post">
               		<label for="tempName" class="title">Alterar Nome</label><br><br><br>
               		<input type="text" name="tempName" required placeholder="Digite um novo nome"/><br><br>
               		<input type="submit" name="tempChangeName" value="Alterar Nome" class="botao"/>
               	</form>
               </div>
            </article>
        </section>
    </body>
</html>