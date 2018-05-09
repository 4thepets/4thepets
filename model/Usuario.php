<?php
    include_once 'db/DatabaseConnection.php';
    include_once 'model/ObjectConfig.php';
    include_once 'enumeration/ExceptionTypeEnum.php';
    
    Class Usuario extends ObjectConfig{
        private $nome;
        private $caminhoFoto;
        private $email;
        private $endereco;
        private $telefone;
    
        public function __construct($code, $nome, $email, $endereco, $telefone, $caminhoFoto){
            parent::__construct($code);
            $this->nome = $nome;
            $this->email = $email;
            $this->caminhoFoto = $caminhoFoto;
            $this->endereco = $endereco;
            $this->telefone = $telefone;
        }
        
        public static function efetuarCadastro($nomeUsuario, $emailUsuario, $senhaUsuario, $senhaConfirmacao){
            $emailUsuario = self::validarEmail($emailUsuario);
            $senhaUsuario = self::validarSenha($senhaUsuario, $senhaConfirmacao);
            // TEMPORÁRIO 
            $caminhoFoto = "images/sample/default.png";
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $SQL  = " INSERT INTO ".$conn->getDbName().".TB_USUARIO (";
            $SQL .= " `NAME_USUARIO`,";
            $SQL .= " `NAME_EMAIL_USUARIO`,";
            $SQL .= " `CODE_SENHA_USUARIO`,";
            $SQL .= " `IMAG_USUARIO`)";
            $SQL .= " VALUES (";
            $SQL .= " :nomeUsuario,";
            $SQL .= " :emailUsuario,";
            $SQL .= " :senhaUsuario,";
            $SQL .= " :caminhoFoto);";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":nomeUsuario", $nomeUsuario);
            $stmt->bindParam(":emailUsuario", $emailUsuario);
            $stmt->bindParam(":senhaUsuario", $senhaUsuario);
            $stmt->bindParam(":caminhoFoto", $caminhoFoto);
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
                return new Usuario(
                    $result['CODE_USUARIO'], 
                    $result['NAME_USUARIO'], 
                    $result['NAME_EMAIL_USUARIO'], 
                    $result['NAME_ENDERECO_USUARIO'], 
                    $result['NBR_TELEFONE_USUARIO'], 
                    $result['IMAG_USUARIO']);
            else
                throw new Exception(ExceptionTypeEnum::ERRO_DADOS_INCORRETOS_LOGIN);
        }

        public function alterarNome($nome){
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $SQL = "UPDATE ".$conn->getDbName().".TB_USUARIO SET NAME_USUARIO = :nome WHERE CODE_USUARIO = :userId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam("nome", $nome);
            $stmt->bindParam("userId", $this->code);
            if($stmt->execute()){
                $this->nome = $nome;
                return true;
            }else
                throw new Exception(ExceptionTypeEnum::ERRO_NOME_INVALIDO);
        }

        public function alterarEmail($email){
            $email = self::validarEmail($email);
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $SQL = "UPDATE ".$conn->getDbName().".TB_USUARIO SET NAME_EMAIL_USUARIO = :email WHERE CODE_USUARIO = :userId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam("email", $email);
            $stmt->bindParam("userId", $this->code);
            if($stmt->execute()){
                $this->email = $email;
                return true;
            }
        }

        public function alterarSenha($senha, $senhaConfirmacao){
            $senha = self::validarSenha($senha, $senhaConfirmacao);
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $SQL = "UPDATE ".$conn->getDbName().".TB_USUARIO SET CODE_SENHA_USUARIO = :senha WHERE CODE_USUARIO = :userId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam("senha", $senha);
            $stmt->bindParam("userId", $this->code);
            if($stmt->execute())
                return true;
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
                return md5($senhaUsuario);
            }else{
                throw new Exception(ExceptionTypeEnum::ERRO_VALIDACAO_SENHA_CADASTRO);
            }   
        }

        public function alterarEndereco($endereco){
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $SQL = "UPDATE ".$conn->getDbName().".TB_USUARIO SET NAME_ENDERECO_USUARIO = :endereco WHERE CODE_USUARIO = :userId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam("endereco", $endereco);
            $stmt->bindParam("userId", $this->code);
            if($stmt->execute()){
                $this->endereco = $endereco;
                return true;
            }else
                throw new Exception(ExceptionTypeEnum::ERRO_ENDERECO_INVALIDO);
        }

        public function alterarTelefone($telefone){
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $SQL = "UPDATE ".$conn->getDbName().".TB_USUARIO SET NBR_TELEFONE_USUARIO = :telefone WHERE CODE_USUARIO = :userId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam("telefone", $telefone);
            $stmt->bindParam("userId", $this->code);
            if($stmt->execute()){
                $this->telefone = $telefone;
                return true;
            }else
                throw new Exception(ExceptionTypeEnum::ERRO_TELEFONE_INVALIDO);
        }

        public function getNome(){
            return $this->nome;
        }

        public function getCaminhoImagem(){
            return $this->caminhoFoto;
        }

        public function getEmail(){
            return $this->email;
        }
        
        public function getEndereco(){
            return $this->endereco;
        }

        public function getTelefone(){
            return $this->telefone;
        }
    }
?>