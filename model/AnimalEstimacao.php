<?php
    include_once 'db/DatabaseConnection.php';
    include_once 'model/ObjectConfig.php';
    include_once 'enumeration/ExceptionTypeEnum.php';
    include_once 'enumeration/CategoriaEnum.php';
    include_once 'enumeration/GeneroEnum.php';
    
    Class AnimalEstimacao extends ObjectConfig{
        private $nome;
        private $categoria;
        private $genero;
        private $idade;
        private $castracao;
        private $caminhoFoto;

        public function __construct($code, $nome, $categoria, $genero, $idade, $castracao, $caminhoFoto){
            parent::__construct($code);
            $this->nome = $nome;
            $this->categoria = $categoria;
            $this->genero = $genero;
            $this->idade = $idade;
            $this->castracao = $castracao;
            $this->caminhoFoto = $caminhoFoto;
        }

        public function efetuarCadastro($nomeAnimal, $categoriaAnimal, $generoAnimal, $idadeAnimal, $isCastrado, $photoFile, $userId){
            if(!CategoriaEnum::isValidValue($categoriaAnimal))
                throw new Exception(ExceptionTypeEnum::ERRO_CATEGORIA);

            if(!GeneroEnum::isValidValue($generoAnimal))
                throw new Exception(ExceptionTypeEnum::ERRO_GENERO);
            
            if(self::cadastrarImagem($file)){
                $conn = new DatabaseConnection();
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $SQL  = " INSERT INTO ".$conn.getDbName().".TB_PET_ADICIONADO_ADOCAO_USUARIO (";
                $SQL .= " `NAME_PET_ADICIONADO_ADOCAO_USUARIO`,";
                $SQL .= " `NAME_CATEGORIA_PET_ADICIONADO_ADOCAO_USUARIO`,";
                $SQL .= " `GNR_SEXO_PET_ADICIONADO_ADOCAO_USUARIO`,";
                $SQL .= " `NBR_IDADE_PET_ADICIONADO_ADOCAO_USUARIO`,";
                $SQL .= " `BOOL_CASTRACAO_PET_ADICIONADO_ADOCAO_USUARIO`,";
                $SQL .= " `IMAG_PET_ADICIONADO_ADOCAO_USUARIO`,";
                $SQL .= " `SYS_BOOL_PET_REMOVIDO_ADOCAO_USUARIO`,";
                $SQL .= " `CDFK_USUARIO`)";
                $SQL .= " VALUES (";
                $SQL .= " :nomeAnimal,";
                $SQL .= " :categoriaAnimal,";
                $SQL .= " :generoAnimal,";
                $SQL .= " :idadeAnimal,";
                $SQL .= " :isCadastrado,";
                $SQL .= " :caminhoFoto,";
                $SQL .= " :isRemoved,";
                $SQL .= " :userId);";
                $stmt = $conn->prepare($SQL);
                $stmt->bindParam(":nomeAnimal", $nomeAnimal);
                $stmt->bindParam(":categoriaAnimal", $categoriaAnimal);
                $stmt->bindParam(":generoAnimal", $generoAnimal);
                $stmt->bindParam(":idadeAnimal", $idadeAnimal);
                $stmt->bindParam(":isCastrado", $isCastrado);
                $stmt->bindParam(":caminhoFoto",$caminhoFoto);
                $stmt->bindParam(":isRemoved", false);
                $stmt->bindParam(":userId", $userId);
                if($stmt->execute())
                    return true;
            }
        }

        public static function retornarPets($userId){
            $petsArray = array();
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $SQL  = " SELECT * FROM ".$conn->getDbName().".TB_PET_ADICIONADO_ADOCAO_USUARIO";
            $SQL .= " WHERE CDFK_USUARIO = :userId";
            $SQL .= " AND SYS_BOOL_PET_REMOVIDO_ADOCAO_USUARIO = :isRemoved";
            $SQL .= " ORDER BY SYS_DATE_PET_ADICIONADO_ADOCAO_USUARIO DESC";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":isRemoved", false);
            if($stmt->execute()){
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($data as $row) {
                    $petsArray[] = new AnimalEstimacao(
                        $row['CODE_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['NAME_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['NAME_CATEGORIA_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['GNR_SEXO_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['NBR_IDADE_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['BOOL_CASTRACAO_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['IMAG_PET_ADICIONADO_ADOCAO_USUARIO']);
                }
            }
            return $petsArray;
        }

        public static function retornaPetsInteressados(){

        }

        // GETS & SETS
        public function getNome(){
            return $this->nome;
        }
    
        public function setNome($petId, $nome){
            $conn = new DatabaseConnection();
            $SQL  = " UPDATE "$conn->getDbName()".TB_PET_ADICIONADO_ADOCAO_USUARIO";
            $SQL .= " SET NAME_PET_ADICIONADO_ADOCAO_USUARIO = :nome";
            $SQL .= " WHERE CODE_PET_ADICIONADO_ADOCAO_USUARIO = :petId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":petId", $petId);
            if($stmt->execute())
                return true;
            else
                throw new Exception(ExceptionTypeEnum::ERRO_INTERNO);
        }
    
        public function getCategoria(){
            return $this->categoria;
        }
    
        public function setCategoria($petId, $categoria){
            if(!CategoriaEnum::isValidValue($categoria))
                throw new Exception(ExceptionTypeEnum::ERRO_CATEGORIA);
            
            $conn = new DatabaseConnection();
            $SQL  = " UPDATE "$conn->getDbName()".TB_PET_ADICIONADO_ADOCAO_USUARIO";
            $SQL .= " SET NAME_CATEGORIA_PET_ADICIONADO_ADOCAO_USUARIO = :categoria";
            $SQL .= " WHERE CODE_PET_ADICIONADO_ADOCAO_USUARIO = :petId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":nome", $categoria);
            $stmt->bindParam(":petId", $petId);
            if($stmt->execute())
                return true;
            else
                throw new Exception(ExceptionTypeEnum::ERRO_INTERNO);
        }
    
        public function getGenero(){
            return $this->genero;
        }
    
        public function setGenero($petId, $genero){
            if(!GeneroEnum::isValidValue($genero))
                throw new Exception(ExceptionTypeEnum::ERRO_GENERO);
            
            $conn = new DatabaseConnection();
            $SQL  = " UPDATE "$conn->getDbName()".TB_PET_ADICIONADO_ADOCAO_USUARIO";
            $SQL .= " SET GNR_SEXO_PET_ADICIONADO_ADOCAO_USUARIO = :genero";
            $SQL .= " WHERE CODE_PET_ADICIONADO_ADOCAO_USUARIO = :petId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":genero", $genero);
            $stmt->bindParam(":petId", $petId);
            if($stmt->execute())
                return true;
            else
                throw new Exception(ExceptionTypeEnum::ERRO_INTERNO);
        }
    
        public function getIdade(){
            return $this->idade;
        }
    
        public function setIdade($petId, $idade){
            $conn = new DatabaseConnection();
            $SQL  = " UPDATE "$conn->getDbName()".TB_PET_ADICIONADO_ADOCAO_USUARIO";
            $SQL .= " SET NBR_IDADE_PET_ADICIONADO_ADOCAO_USUARIO = :idade";
            $SQL .= " WHERE CODE_PET_ADICIONADO_ADOCAO_USUARIO = :petId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":idade", $idade);
            $stmt->bindParam(":petId", $petId);
            if($stmt->execute())
                return true;
            else
                throw new Exception(ExceptionTypeEnum::ERRO_INTERNO);
        }
    
        public function getCastracao(){
            return $this->castracao;
        }
    
        public function setCastracao($petId, $castracao){
            $conn = new DatabaseConnection();
            $SQL  = " UPDATE "$conn->getDbName()".TB_PET_ADICIONADO_ADOCAO_USUARIO";
            $SQL .= " SET BOOL_CASTRACAO_PET_ADICIONADO_ADOCAO_USUARIO = :castracao";
            $SQL .= " WHERE CODE_PET_ADICIONADO_ADOCAO_USUARIO = :petId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":castracao", $castracao);
            $stmt->bindParam(":petId", $petId);
            if($stmt->execute())
                return true;
            else
                throw new Exception(ExceptionTypeEnum::ERRO_INTERNO);
        }
    
        public function getCaminhoFoto(){
            return $this->caminhoFoto;
        }
    
        public function setCaminhoFoto($caminhoFoto){
            //alterar imagem
            if(self::alterarImagem($file))
        }

        public function removeCadastro($petId){
            $conn = new DatabaseConnection();
            $SQL  = " UPDATE "$conn->getDbName()".TB_PET_ADICIONADO_ADOCAO_USUARIO";
            $SQL .= " SET SYS_BOOL_PET_REMOVIDO_ADOCAO_USUARIO = :true";
            $SQL .= " WHERE CODE_PET_ADICIONADO_ADOCAO_USUARIO = :petId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":true", true);
            $stmt->bindParam(":petId", $petId);
            if($stmt->execute())
                return true;
            else
                throw new Exception(ExceptionTypeEnum::ERRO_INTERNO);
        }
    }
?>