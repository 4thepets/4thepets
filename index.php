<?php
	include_once 'model/Usuario.php';
	if(isset($_POST['userLogin'])){
		try{
			if($usuario = Usuario::efetuarAcesso($_POST['userEmail'], $_POST['userPassword'])){
				session_start();
				$_SESSION['USUARIO'] = serialize($usuario);
				header("location: home.php");
			}
		}catch(Exception $e){
			echo $e->getMessage();
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
			echo $e->getMessage();
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
	</head>
	<body>
		<fieldset>
			<legend>Login:</legend>
			<form method="post">
				Email:<input type="email" name="userEmail" required/>
				Senha: <input type="password" name="userPassword" required/>
				<input type="submit" name ="userLogin" value="Acessar"/>
			</form>
		</fieldset>
		<fieldset>
			<legend>Cadastro:</legend>
			<form method="post">
				Nome: <input type="text" name="userName" required/>
				Email: <input type="email" name="userEmail" required placeholder="Digite um email válido."/>
				Senha: <input type="password" name="userPassword" required placeholder="Digite uma senha"/>
				Confirme a senha: <input type="password" name="userPasswordConfirm" required placeholder="Digite sua senha novamente"/>
				<input type="submit" name="userRegister" value="Cadastrar"/>
			</form>
		</fieldset>
	</body>
</html>