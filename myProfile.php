<?php
    include_once "model/Usuario.php";
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
         <link rel="stylesheet" type="text/css" href="style/myProfile.css"/>

        <title>4thePets - Juntando grandes amigos</title>
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
                    <img class="img" src="images/logo_4tp_white.png" />
                    <h1 class="title">Minhas informações</h1>
                    <p class="subtitle">Atualizar as informações é sempre bem vindo.</p><br/><br/>
                    <p class="subsubtitle"><b>Meu alias: </b><?php echo $usuario->getNome(); ?></p><br/>
                    <a href="tempProfileName.php" class="botao">Alterar Nome</a><br/><br/>
                    <p class="subsubtitle"><b>Email: </b><?php echo $usuario->getEmail(); ?></p><br/>
                    <a href="tempProfileEmail.php" class="botao">Alterar Email</a><br/><br/>
                    <p class="subsubtitle"><b>Senha: </b></p><br/>
                    <a href="tempProfilePassword.php" class="botao">Alterar Senha</a><br/><br/>
                    <p class="subsubtitle"><b>Endereço: </b><?php if($usuario->getEndereco() == null) echo "Não definido."; else echo $usuario->getEndereco(); ?></p><br/>
                    <a href="tempProfileAddress.php" class="botao">Alterar Endereço</a><br/><br/>
                    <p class="subsubtitle"><b>Telefone: </b><?php if($usuario->getTelefone() == null) echo "Não definido."; else echo $usuario->getTelefone(); ?></p><br/>
                    <a href="tempProfileTelephone.php" class="botao">Alterar Telefone</a><br/><br/>
                </div>
            </article>
        </section>
    </body>
</html>