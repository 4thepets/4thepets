<?php
    include_once "model/Usuario.php";
    include_once "enumeration/CategoriaEnum.php";
    include_once "enumeration/GeneroEnum.php";
    include_once "model/AnimalEstimacao.php";
    session_start();
    if($_SESSION['USUARIO'])
        $usuario = unserialize($_SESSION['USUARIO']);
    else
        header("location: index.php");

    if(isset($_POST['animalRegister'])){
        try{
            if(AnimalEstimacao::efetuarCadastro($_POST['animalName'], $_POST['animalCategory'], $_POST['animalGender'], $_POST['animalAge'], $_POST['animalCast'], $_FILES['animalImage'], $usuario->getCode()))
                $STATUS_MESSAGE = "Cadastrado com sucesso!";
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
                <h1 class="title">Adicionando um Pet</h1>
                <p class="subtitle"><?php if(isset($STATUS_MESSAGE)) echo $STATUS_MESSAGE; else echo "Preencha o formulário abaixo para adicionar um animal."; ?></p><br/><br/>
                <form method="post" enctype="multipart/form-data">
                    <label for="animalName" class="subtitle">Digite o nome do animal de estimação.</label><br>
                    <input type="text" name="animalName" required placeholder="Digite um nome."/><br/><br>
                    <label for="animalCategory" class="subtitle">Selecione a categoria.</label><br>
                    <select name="animalCategory" required>
                        <option>...</option>
                        <?php
                            foreach (CategoriaEnum::getConstants() as $categoria) {
                                echo "<option value=".$categoria.">".$categoria."</option>";
                            }
                        ?>
                    </select><br/><br>
                    <label for="animalGender" class="subtitle">Selecione o Gênero</label><br>
                    <input type="radio" name="animalGender" value="<?php echo GeneroEnum::MACHO; ?>" required class="option-input"/><?php echo GeneroEnum::MACHO; ?><p></p><br>
                    <input type="radio" name="animalGender" value="<?php echo GeneroEnum::FEMEA; ?>" required class="option-input"/><?php echo GeneroEnum::FEMEA; ?><br/><br><br>
                    <label for="animalAge" class="subtitle">Digite a idade</label><br>
                    <input type="number" name="animalAge" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="2" required/><br/><br>
                    <label for="animalCast" class="subtitle">Ele é castrado?</label><br>
                    <input type="radio" name="animalCast" value="y" required class="option-input"/>Sim <p></p><br>
                    <input type="radio" name="animalCast" value="n" required class="option-input"/>Não<br/><br><br>
                    <input type="file" name="animalImage" id="file" class="file-input" />
                    <label for="file">Selecione uma imagem (opcional)</label><br><br>
                    <input type="submit" name="animalRegister" value="Registrar meu pet" class="botao"/><br><br><br>
                </form>
                </div>
            </article>
        </section>
    </body>
</html>