<?php
    include_once 'db/DatabaseConnection.php';
    include_once 'model/ObjectConfig.php';
    include_once 'enumeration/ExceptionTypeEnum.php';
    
    Class Usuario extends ObjectConfig{
        private $nome;
        private $caminhoFoto;
        private $email;
    
        public function __construct($code, $nome, $email, $caminhoFoto){
            $this->code = $code;
            $this->nome = $nome;
            $this->email = $email;
            $this->caminhoFoto = $caminhoFoto;
        }

        public static function efetuarCadastro($nomeUsuario, $emailUsuario, $senhaUsuario, $senhaConfirmacao){
            $emailUsuario = self::validarEmail($emailUsuario);
            $senhaUsuario = self::validarSenha($senhaUsuario, $senhaConfirmacao);
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $SQL  = " INSERT INTO ".$conn->getDbName().".TB_USUARIO (";
            $SQL .= " `NAME_USUARIO`,";
            $SQL .= " `NAME_EMAIL_USUARIO`,";
            $SQL .= " `CODE_SENHA_USUARIO`)";
            $SQL .= " VALUES (";
            $SQL .= " :nomeUsuario,";
            $SQL .= " :emailUsuario,";
            $SQL .= " :senhaUsuario);";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":nomeUsuario", $nomeUsuario);
            $stmt->bindParam(":emailUsuario", $emailUsuario);
            $stmt->bindParam(":senhaUsuario", $senhaUsuario);
            if($stmt->execute())
                return true;
            else
                throw new Exception(ExceptionTypeEnum::ERRO_INTERNO);
        }

        public static function efetuarAcesso($email, $senha){
            $senha = md5($senha);
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT * FROM ".$conn->getDbName().".TB_USUARIO WHERE NAME_EMAIL_USUARIO = :email AND CODE_SENHA_USUARIO = :senha");
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":senha", $senha);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result)
                return new Usuario($result['CODE_USUARIO'], $result['NAME_USUARIO'], $result['NAME_EMAIL_USUARIO'], $result['IMAG_USUARIO']);
            else
                throw new Exception(ExceptionTypeEnum::ERRO_DADOS_INCORRETOS_LOGIN);
        }

        private function validarEmail($emailUsuario){
            if(is_null($emailUsuario))
                throw new Exception(ExceptionTypeEnum::ERRO_EMAIL_NULO_CADASTRO);
            
            if(!filter_var($emailUsuario, FILTER_VALIDATE_EMAIL))
                throw new Exception(ExceptionTypeEnum::ERRO_EMAIL_CADASTRO);
            
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT NAME_EMAIL_USUARIO FROM ".$conn->getDbName().".TB_USUARIO WHERE NAME_EMAIL_USUARIO = :userEmail");
            $stmt->bindParam(":userEmail", $emailUsuario);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($result)
                throw new Exception(ExceptionTypeEnum::ERRO_EMAIL_EXISTENTE_CADASTRO);
            else
                return $emailUsuario;
        }

        private function validarSenha($senhaUsuario, $senhaConfirmacao){
            if(is_null($senhaUsuario) || is_null($senhaConfirmacao))
                throw new Exception(ExceptionTypeEnum::ERRO_SENHA_NULA_CADASTRO);
            
            if($senhaUsuario == $senhaConfirmacao){
                $pattern = "/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=*\/_-]).*$/"; 
                if(preg_match($pattern, $senhaUsuario))
                    return md5($senhaUsuario);
                else
                    throw new Exception(ExceptionTypeEnum::ERRO_SENHA_CADASTRO);
                
            }else{
                throw new Exception(ExceptionTypeEnum::ERRO_VALIDACAO_SENHA_CADASTRO);
            }   
        }

        public function getNome(){
            echo $this->nome;
        }
    }
?>