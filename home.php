<?php
    include_once "model/Usuario.php";
    session_start();
    if($_SESSION['USUARIO'])
        $usuario = unserialize($_SESSION['USUARIO']);
    else
        header("location: index.php");
    
    if(isset($_POST['exitSession'])){
        session_destroy();
        header("location: index.php");        
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>4thePets - Juntando grandes amigos</title>
    </head>
    <body>
        <h3>Bem vindo <?php $usuario->getNome(); ?></h3>
        <div style="border: 1px solid #000;">
            Menu fixo: perfil, meus pets cadastrados, pets que tenho interesse e sair;
        </div>
        <div style="border: 1px solid #000;">
            Pagina: opção pra inserir um pet, e lista com opções pra aadotar um pet (cachorro, gato, etc);
        </div>
        <div style="border: 1px solid #000;">
            Pagina (embaixo): estatisticas de pets (mais adotados, menos adotados, + numero de pets por curtida, - numero de pets por curtida);
        </div>
        <a href="adotarPet.php">Adote um pet</a>
        <a href="addPet.php">Adicione um pet para adoção</a>
        <a href="myPets.php">Meus Pets</a>
        <a href="myIPets.php">Pets interessados</a>
        <form method="post">
            <input type="submit" name="exitSession" value="sair">
        </form>
    </body>
</html>