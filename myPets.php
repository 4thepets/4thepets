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
                <h1>Meus pets</h1>
                <p>Nada melhor do que prestigiar seus filhinhos.</p><br/>
                <h1>Adicionando um pet</h1>
                <form method="post">
                    <label for="animalName">Digite o nome do animal de estimação.</label>
                    <input type="text" name="animalName" required placeholder="Digite um nome."/><br/>
                    <label for="animalCategory">Selecione a categoria.</label>
                    <select name="animalCategory" required>
                        <option>...</option>
                        <?php
                            foreach (CategoriaEnum::getConstants() as $categoria) {
                                echo "<option value=".$categoria.">".$categoria."</option>";
                            }
                        ?>
                    </select><br/>
                    <label for="animalGender">Selecione o Gênero</label>
                    <input type="radio" name="animalGender" value="Macho" required/>Macho
                    <input type="radio" name="animalGender" value="Femea" required/>Fêmea<br/>
                    <label for="animalAge">Digite a idade</label>
                    <input type="number" name="animalAge" required/><br/>
                    <label for="animalCast">Ele é castrado?</label>
                    <input type="radio" name="animalCast" value="yes" required/>Sim
                    <input type="radio" name="animalGender" value="no" required/>Não<br/>
                    <input type="submit" name="animalRegister" value="Registrar meu pet"/>
                </form>
            </article>
        </section>
    </body>
</html>