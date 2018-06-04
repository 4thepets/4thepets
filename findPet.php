<?php
    include_once "model/Usuario.php";
    include_once "enumeration/CategoriaEnum.php";
    include_once "enumeration/GeneroEnum.php";
    include_once "model/AnimalEstimacao.php";
    include_once "model/Pigeon/PigeonService.php";
    session_start();
    if($_SESSION['USUARIO'])
        $usuario = unserialize($_SESSION['USUARIO']);
    else
        header("location: index.php");

    if(isset($_POST['searchPets'])){
        try{
            $array = array(null, null, null, null);
            if(isset($_POST['animalCategory']))
                $array[0] = $_POST['animalCategory'];
                
            if(isset($_POST['animalGender']))
                $array[1] = $_POST['animalGender'];

            if(isset($_POST['animalCast']))
                $array[2] = $_POST['animalCast'];

            if(isset($_POST['animalName']))
                $array[3] = $_POST['animalName'];

            $pets = Pigeon::find(PigeonClass::PET, $array[0], $array[1], $array[2], $array[3]);
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
        <link rel="stylesheet" type="text/css" href="style/findPet.css"/>
        <title>4thePets - Juntando grandes amigos</title>
    </head>
    
    <script src="https://npmcdn.com/minigrid@3.0.1/dist/minigrid.min.js"></script>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/script.js"></script>

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
                    <img  src="images/logo_4tp_white.png" class="img"/><br>
                <h1 class="title">Buscando um Amigo</h1>
                <p class="subtitle"><?php if(isset($STATUS_MESSAGE)) echo $STATUS_MESSAGE; else echo "Preencha o formulário abaixo para encontrar um animal."; ?></p><br/><br/>
                <form method="post" enctype="multipart/form-data">
                    <label for="animalCategory" class="subtitle">Selecione a categoria.</label><br>
                    <select name="animalCategory">
                        <option value="">...</option>
                        <?php
                            foreach (CategoriaEnum::getConstants() as $categoria) {
                                echo "<option value=".$categoria.">".$categoria."</option>";
                            }
                        ?>
                    </select><br/><br>
                    <label for="animalGender" class="subtitle">Selecione o Gênero</label><br>
                    <input type="radio" name="animalGender" value="<?php echo GeneroEnum::MACHO; ?>" class="option-input"/><?php echo GeneroEnum::MACHO; ?><p></p><br>
                    <input type="radio" name="animalGender" value="<?php echo GeneroEnum::FEMEA; ?>" class="option-input"/><?php echo GeneroEnum::FEMEA; ?><br/><br><br>
                    <label for="animalCast" class="subtitle">Ele é castrado?</label><br>
                    <input type="radio" name="animalCast" value="y" class="option-input"/>Sim <p></p><br>
                    <input type="radio" name="animalCast" value="n" class="option-input"/>Não<br/><br><br>
                     <label for="animalName" class="subtitle">Sabe o nome do pet que procura? Digite-o abaixo!</label><br>
                    <input type="text" name="animalName" placeholder="Digite o nome do pet."/><br/><br>
                    <input type="submit" name="searchPets" value="Buscar" class="botao"/><br><br><br>
                </form>
                </div>
                <div class="pageContentPets">
                <?php
                    if(isset($pets)){
                        if(!empty($pets)){
                        foreach ($pets as $pet) { ?>
                            <div class="card">
                                <a href="petInformation.php?petValue=<?php echo $pet->getCode(); ?>">
                                <img src="<?php echo $pet->getCaminhoFoto(); ?>"/>
                                <p><?php echo $pet->getNome().", ".$pet->getIdade(); ?></p>
                                </a>
                            </div><?php
                            }
                        }else{
                            echo "<p>Nenhum animal encontrado.";
                        }
                    }
                    ?>
                    <div class="clear"></div>
                </div>
            </article>
        </section>
    </body>
</html>