<?php
    include_once 'db/DatabaseConnection.php';
    include_once 'model/ObjectConfig.php';
    include_once 'model/Pigeon/PigeonService.php';
    
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
            try{
                $nomeUsuario = Pigeon::validate(PigeonClass::USER, PigeonMethodType::NAME, $nomeUsuario);
                $emailUsuario = Pigeon::validate(PigeonClass::USER, PigeonMethodType::EMAIL, $emailUsuario);
                $senhaUsuario = Pigeon::validate(PigeonClass::USER, PigeonMethodType::PASSWORD, array($senhaUsuario, $senhaConfirmacao));
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }

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

        public function alterarNome($nomeUsuario){
            $nomeUsuario = Pigeon::validate(PigeonClass::USER, PigeonMethodType::NAME, $nomeUsuario);
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $SQL = "UPDATE ".$conn->getDbName().".TB_USUARIO SET NAME_USUARIO = :nome WHERE CODE_USUARIO = :userId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam("nome", $nomeUsuario);
            $stmt->bindParam("userId", $this->code);
            if($stmt->execute()){
                $this->nome = $nomeUsuario;
                return true;
            }else
                throw new Exception(ExceptionTypeEnum::ERRO_NOME_INVALIDO);
        }

        public function alterarEmail($emailUsuario){
            $emailUsuario = Pigeon::validate(PigeonClass::USER, PigeonMethodType::EMAIL, $emailUsuario);
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $SQL = "UPDATE ".$conn->getDbName().".TB_USUARIO SET NAME_EMAIL_USUARIO = :email WHERE CODE_USUARIO = :userId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam("email", $emailUsuario);
            $stmt->bindParam("userId", $this->code);
            if($stmt->execute()){
                $this->email = $emailUsuario;
                return true;
            }
        }

        public function alterarSenha($senhaUsuario, $senhaConfirmacao){
            $senhaUsuario = Pigeon::validate(PigeonClass::USER, PigeonMethodType::PASSWORD, array($senhaUsuario, $senhaConfirmacao));
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $SQL = "UPDATE ".$conn->getDbName().".TB_USUARIO SET CODE_SENHA_USUARIO = :senha WHERE CODE_USUARIO = :userId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam("senha", $senhaUsuario);
            $stmt->bindParam("userId", $this->code);
            if($stmt->execute())
                return true;
        }

        public function alterarEndereco($endereco){
            $endereco = Pigeon::validate(PigeonClass::USER, PigeonMethodType::ADDRESS, $endereco);
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
            $endereco = Pigeon::validate(PigeonClass::USER, PigeonMethodType::TELEPHONE, $telefone);
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