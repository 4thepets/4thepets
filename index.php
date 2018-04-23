<?php
	include_once 'model/Usuario.php';
	include_once 'enumeration/IndexMessagesEnum.php';
	if(isset($_POST['userLogin'])){
		try{
			if($usuario = Usuario::efetuarAcesso($_POST['userEmail'], $_POST['userPassword'])){
				session_start();
				$_SESSION['USUARIO'] = serialize($usuario);
				header("location: home.php");
			}
		}catch(Exception $e){
			$loginMessage = $e->getMessage();
		}
	}else if(isset($_POST['userRegister'])){
		try{
			if(Usuario::efetuarCadastro($_POST['userName'], $_POST['userEmail'], $_POST['userPassword'], $_POST['userPasswordConfirm'])){
				if($usuario = Usuario::efetuarAcesso($_POST['userEmail'], $_POST['userPassword'])){
					session_start();
					$_SESSION['USUARIO'] = serialize($usuario);
					header("location: home.php");
				}
			}
		}catch(Exception $e){
			$registerMessage = $e->getMessage();
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>4thePets - Juntando grandes amigos</title>
		<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<link rel="stylesheet" type="text/css" href="style/index.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>
	<script> 
		$(document).ready(function(){
    		$("#userRegister").click(function(){
				$("#menuToggle").removeAttr('style');
        		$("#menuToggle").animate({bottom: '0'}, "slow");
    		});
			$("#userLogin").click(function(){
        		$("#menuToggle").animate({top: '0'}, "slow");
			});
		});
	</script> 
	<body background="images/bkg/0<?php echo rand(1, 5); ?>.jpg">
		<div class="filterOpacity"></div>
		<section class="indexContent">
			<article id="menuToggle" class="menuContent">
				<div class="loginForm">
					<h1><?php if(isset($loginMessage)) echo $loginMessage; else echo "Acesso:"; ?></h1>
					<form method="post">
						<label for="userEmail">Email:</label>
						<input type="email" name="userEmail" required placeholder="Digite seu email!"/>
						<label for="userPassword">Senha:</label>
						<input type="password" name="userPassword" required placeholder="Digite sua senha"/>
						<input type="submit" name="userLogin" value="Acessar"/>
						<a href="forgotPassword.php">Esqueci minha senha</a>
					</form>
					<p>Novo por aqui? <a id="userRegister" href="#">Cadastre-se</a>!</p>
				</div>
				<div class="registerForm">
					<?php if(isset($registerMessage)) echo "<h3>".$registerMessage."</h3>"; else echo "<h1>Cadastre-se:</h1>"; ?>
					<form method="post">
						<label for="userName">Nome de Usuário:</label>
						<input type="text" name="userName" required placeholder="Digite seu nome."/>
						<label for="userEmail">Email:</label>
						<input type="email" name="userEmail" required placeholder="Digite um email válido."/>
						<label>Senha:</label>
						<input type="password" name="userPassword" required placeholder="Digite uma senha forte."/>
						<label for="password">Confirme a senha:</label>
						<input type="password" name="userPasswordConfirm" required placeholder="Digite sua senha novamente."/>
						<input type="submit" name="userRegister" value="Cadastrar"/>
					</form>
					<p>Já é cadastrado? <a id="userLogin" href="#">Acesse</a>!</p>
				</div>
			</article>
			<article class="indexApresentationContent">
				<figure>
					<img src="images/logo_4tp_white.png"/>
					<figcaption><?php echo IndexMessagesEnum::getMessage(); ?></figcaption>
					<p>Acesse nossa plataforma e faça novos amigos!</p>
				</figure>	
			</article>
		</section>
	</body>
</html>