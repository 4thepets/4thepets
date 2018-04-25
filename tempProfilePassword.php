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
        <title>Alterar Senha</title>
    </head>
    <body>
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
                <?php
                    if(isset($STATUS_MESSAGE))
                        echo $STATUS_MESSAGE;
                ?>
               	<form method="post">
               		<label for="tempPassword">Alterar Senha</label>
               		<input type="password" name="tempPassword" required placeholder="Digite uma nova senha."/><br/>
                    <label for="tempPassword">Confirme a Senha</label>
                    <input type="password" name="tempPasswordConfirm" required placeholder="Digite sua senha novamente."/>
               		<input type="submit" name="tempChangePassword" value="Alterar Senha"/>
               	</form>
            </article>
        </section>
    </body>
</html>