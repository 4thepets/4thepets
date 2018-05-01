<?php
	include_once "model/Usuario.php";
    session_start();
    if($_SESSION['USUARIO'])
        $usuario = unserialize($_SESSION['USUARIO']);
    else
        header("location: index.php");
        
    if(isset($_POST['tempChangePassword'])){
        try{
            if($usuario->alterarSenha($_POST['tempPassword'], $_POST['tempPasswordConfirm']))
                $STATUS_MESSAGE = "Sucesso";
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
         <link rel="stylesheet" type="text/css" href="style/tempProfilePassword.css"/>
        <title>Alterar Senha</title>
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
                    <li><a href="myInterestPets.php">Pets interessados</a></li>
                    <li><a href="home.php?quit=true">Sair</a></li>
                </ul>
            </article>
            <!-- Page Content -->
            <article class="pageContent">
                <div class="pageorg">
                    <img class="img" src="images/logo_4tp_white.png"/><br>
               	<form method="post">
                       <?php
                            if(isset($STATUS_MESSAGE))
                                echo "<label for='tempPassword' class='title'>".$STATUS_MESSAGE."</label>";
                            else
                                echo "<label for='tempPassword' class='title'>Alterar Senha</label>";
                        ?>
                    <br/><label for="password" class="subtitle">Digite uma nova senha.</label><br/>
               		<input type="password" name="tempPassword" required/><br/>
                    <label for="tempPassword" class="subtitle">Digite novamente sua senha.</label><br/>
                    <input type="password" name="tempPasswordConfirm" required/><br/>
               		<input type="submit" name="tempChangePassword" value="Alterar Senha" class="botao"/>
               	</form>
                </div>
            </article>
        </section>
    </body>
</html>