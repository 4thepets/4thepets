<?php
    include_once 'db/DatabaseConnection.php';
    include_once 'model/ObjectConfig.php';
    include_once 'enumeration/ExceptionTypeEnum.php';
    include_once 'enumeration/CategoriaEnum.php';
    include_once 'enumeration/GeneroEnum.php';
    include_once 'model/Pigeon/PigeonService.php';
    
    Class AnimalEstimacao extends ObjectConfig{
        private $nome;
        private $categoria;
        private $genero;
        private $idade;
        private $castracao;
        private $caminhoFoto;
        private $nomeDono;
        private $emailDono;
        private $endDono;
        private $telDono;
        private $keyDono;

        public function __construct($code, $nome, $categoria, $genero, $idade, $castracao, $caminhoFoto, $nomeDono = null, $emailDono = null, $endDono = null, $telDono = null, $keyDono = null){
            parent::__construct($code);
            $this->nome = $nome;
            $this->categoria = $categoria;
            $this->genero = $genero;
            $this->idade = $idade;
            $this->castracao = $castracao;
            $this->caminhoFoto = $caminhoFoto;
            $this->nomeDono = $nomeDono;
            $this->emailDono = $emailDono;
            $this->endDono = $endDono;
            $this->telDono = $telDono;
            $this->keyDono = $keyDono;
        }

        public function efetuarCadastro($nomeAnimal, $categoriaAnimal, $generoAnimal, $idadeAnimal, $castrado, $caminhoFoto, $userId){
            $nomeAnimal = Pigeon::validate(PigeonClass::PET, PigeonMethodType::NAME, $nomeAnimal);
            $categoriaAnimal = Pigeon::validate(PigeonClass::PET, PigeonMethodType::CATEGORY, $categoriaAnimal);
            $generoAnimal = Pigeon::validate(PigeonClass::PET, PigeonMethodType::GENDER, $generoAnimal);
            $idadeAnimal = Pigeon::validate(PigeonClass::PET, PigeonMethodType::AGE, $idadeAnimal);
            $castrado = Pigeon::validate(PigeonClass::PET, PigeonMethodType::CASTRATED, $castrado);

            try{
                $caminhoFoto = self::atualizarImagem($caminhoFoto);
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }

            if($caminhoFoto){
                $removido = false;
                $conn = new DatabaseConnection();
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $SQL  = " INSERT INTO ".$conn->getDbName().".TB_PET_ADICIONADO_ADOCAO_USUARIO (";
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
                $SQL .= " :castrado,";
                $SQL .= " :caminhoFoto,";
                $SQL .= " :isRemoved,";
                $SQL .= " :userId);";
                $stmt = $conn->prepare($SQL);
                $stmt->bindParam(":nomeAnimal", $nomeAnimal);
                $stmt->bindParam(":categoriaAnimal", $categoriaAnimal);
                $stmt->bindParam(":generoAnimal", $generoAnimal);
                $stmt->bindParam(":idadeAnimal", $idadeAnimal);
                $stmt->bindParam(":castrado", $castrado);
                $stmt->bindParam(":caminhoFoto",$caminhoFoto);
                $stmt->bindParam(":isRemoved", $removido);
                $stmt->bindParam(":userId", $userId);
                if($stmt->execute())
                    return true;
            }
        }

        public static function retornarPets($userId = false){
            $petsArray = array();
            $isRemoved = false;
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $SQL  = " SELECT * FROM ".$conn->getDbName().".TB_PET_ADICIONADO_ADOCAO_USUARIO";
            $SQL .= " WHERE SYS_BOOL_PET_REMOVIDO_ADOCAO_USUARIO = :isRemoved";
            if($userId)
                $SQL .= " AND CDFK_USUARIO = :userId";
            $SQL .= " ORDER BY RAND()";
            if(!$userId)
                $SQL .= " LIMIT 4";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":isRemoved", $isRemoved);
            if($userId)
                $stmt->bindParam(":userId", $userId);
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
                        $row['IMAG_PET_ADICIONADO_ADOCAO_USUARIO'],
                        null,
                        null,
                        null,
                        null,
                        $row['CDFK_USUARIO']);
                }
            }
            return $petsArray;
        }

        public static function retornarPetsInteressados($userId){
            $petsInteresse = array();
            $isRemoved = false;
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $SQL  = " SELECT";
            $SQL .= " p.CODE_PET_ADICIONADO_ADOCAO_USUARIO,";
            $SQL .= " p.NAME_PET_ADICIONADO_ADOCAO_USUARIO,";
            $SQL .= " p.NAME_CATEGORIA_PET_ADICIONADO_ADOCAO_USUARIO,";
            $SQL .= " p.GNR_SEXO_PET_ADICIONADO_ADOCAO_USUARIO,";
            $SQL .= " p.NBR_IDADE_PET_ADICIONADO_ADOCAO_USUARIO,";
            $SQL .= " p.BOOL_CASTRACAO_PET_ADICIONADO_ADOCAO_USUARIO, ";
            $SQL .= " p.IMAG_PET_ADICIONADO_ADOCAO_USUARIO,";
            $SQL .= " p.CDFK_USUARIO";
            $SQL .= " FROM ".$conn->getDbName().".TB_PET_INTERESSE_ADOCAO_USUARIO i";
            $SQL .= " JOIN ".$conn->getDbName().".TB_PET_ADICIONADO_ADOCAO_USUARIO p ON i.CDFK_PET_ADICIONADO_ADOCAO_USUARIO = p.CODE_PET_ADICIONADO_ADOCAO_USUARIO";
            $SQL .= " JOIN ".$conn->getDbName().".TB_USUARIO u ON i.CDFK_USUARIO = u.CODE_USUARIO";
            $SQL .= " WHERE SYS_BOOL_PET_REMOVIDO_ADOCAO_USUARIO = :isRemoved";
            $SQL .= " AND i.CDFK_USUARIO = :userId";
            $SQL .= " ORDER BY SYS_DATE_PET_ADICIONADO_ADOCAO_USUARIO DESC";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":isRemoved", $isRemoved);
            $stmt->bindParam(":userId", $userId);
            if($stmt->execute()){
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($data as $row) {
                    $petsInteresse[] = new AnimalEstimacao(
                        $row['CODE_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['NAME_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['NAME_CATEGORIA_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['GNR_SEXO_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['NBR_IDADE_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['BOOL_CASTRACAO_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['IMAG_PET_ADICIONADO_ADOCAO_USUARIO'],
                        null,
                        null,
                        null,
                        null,
                        $row['CDFK_USUARIO']);
                }
            }
            return $petsInteresse;
        }

        public function retornaPetAdotadoInfo($petId){
            $animalEstimacao = false;
            $isRemoved = false;
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $SQL  = " SELECT";
            $SQL .= " p.CODE_PET_ADICIONADO_ADOCAO_USUARIO,";
            $SQL .= " p.NAME_PET_ADICIONADO_ADOCAO_USUARIO,";
            $SQL .= " p.NAME_CATEGORIA_PET_ADICIONADO_ADOCAO_USUARIO,";
            $SQL .= " p.GNR_SEXO_PET_ADICIONADO_ADOCAO_USUARIO,";
            $SQL .= " p.NBR_IDADE_PET_ADICIONADO_ADOCAO_USUARIO,";
            $SQL .= " p.BOOL_CASTRACAO_PET_ADICIONADO_ADOCAO_USUARIO, ";
            $SQL .= " p.IMAG_PET_ADICIONADO_ADOCAO_USUARIO,";
            $SQL .= " u.NAME_USUARIO,";
            $SQL .= " u.NAME_EMAIL_USUARIO,";
            $SQL .= " u.NAME_ENDERECO_USUARIO,";
            $SQL .= " u.NBR_TELEFONE_USUARIO,";
            $SQL .= " p.CDFK_USUARIO";
            $SQL .= " FROM ".$conn->getDbName().".TB_PET_ADICIONADO_ADOCAO_USUARIO p";
            $SQL .= " JOIN ".$conn->getDbName().".TB_USUARIO u ON p.CDFK_USUARIO = u.CODE_USUARIO";
            $SQL .= " WHERE CODE_PET_ADICIONADO_ADOCAO_USUARIO = :petId";
            $SQL .= " AND SYS_BOOL_PET_REMOVIDO_ADOCAO_USUARIO = :isRemoved";
            $SQL .= " ORDER BY SYS_DATE_PET_ADICIONADO_ADOCAO_USUARIO DESC";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":petId", $petId);
            $stmt->bindParam(":isRemoved", $isRemoved);
            if($stmt->execute()){
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($data as $row) {
                    $animalEstimacao = new AnimalEstimacao(
                        $row['CODE_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['NAME_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['NAME_CATEGORIA_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['GNR_SEXO_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['NBR_IDADE_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['BOOL_CASTRACAO_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['IMAG_PET_ADICIONADO_ADOCAO_USUARIO'],
                        $row['NAME_USUARIO'],
                        $row['NAME_EMAIL_USUARIO'],
                        $row['NAME_ENDERECO_USUARIO'],
                        $row['NBR_TELEFONE_USUARIO'],
                        $row['CDFK_USUARIO']);
                }
            }
            return $animalEstimacao;
        }

        public function update($nomeAnimal, $categoriaAnimal, $generoAnimal, $idadeAnimal, $castrado, $caminhoFoto){
            try{
                self::setNome($this->getCode(), $nomeAnimal);
                self::setCategoria($this->getCode(), $categoriaAnimal);
                self::setGenero($this->getCode(), $generoAnimal);
                self::setIdade($this->getCode(), $idadeAnimal);
                self::setCastracao($this->getCode(), $castrado);
                if(isset($caminhoFoto['name']))
                    self::setCaminhoFoto($this->getCode(), $caminhoFoto);
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }
        }

        // GETS & SETS
        public function getNome(){
            return $this->nome;
        }
    
        public function setNome($petId, $nome){
            $nome = Pigeon::validate(PigeonClass::PET, PigeonMethodType::NAME, $nome);
            $conn = new DatabaseConnection();
            $SQL  = " UPDATE ".$conn->getDbName().".TB_PET_ADICIONADO_ADOCAO_USUARIO";
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
            $categoria = Pigeon::validate(PigeonClass::PET, PigeonMethodType::CATEGORY, $categoria);
            $conn = new DatabaseConnection();
            $SQL  = " UPDATE ".$conn->getDbName().".TB_PET_ADICIONADO_ADOCAO_USUARIO";
            $SQL .= " SET NAME_CATEGORIA_PET_ADICIONADO_ADOCAO_USUARIO = :categoria";
            $SQL .= " WHERE CODE_PET_ADICIONADO_ADOCAO_USUARIO = :petId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":categoria", $categoria);
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
            $genero = Pigeon::validate(PigeonClass::PET, PigeonMethodType::GENDER, $genero);
            $conn = new DatabaseConnection();
            $SQL  = " UPDATE ".$conn->getDbName().".TB_PET_ADICIONADO_ADOCAO_USUARIO";
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
            $idade = Pigeon::validate(PigeonClass::PET, PigeonMethodType::AGE, $idade);
            $conn = new DatabaseConnection();
            $SQL  = " UPDATE ".$conn->getDbName().".TB_PET_ADICIONADO_ADOCAO_USUARIO";
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
            if($this->castracao == 0)
                return "Não";
            else
                return "Sim";
        }
    
        public function setCastracao($petId, $castracao){
            $conn = new DatabaseConnection();
            $SQL  = " UPDATE ".$conn->getDbName().".TB_PET_ADICIONADO_ADOCAO_USUARIO";
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
    
        public function setCaminhoFoto($petId, $caminhoFoto){
            try{
                $caminhoFoto = self::atualizarImagem($caminhoFoto);
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }

            if($caminhoFoto){
                $conn = new DatabaseConnection();
                $SQL  = " UPDATE ".$conn->getDbName().".TB_PET_ADICIONADO_ADOCAO_USUARIO";
                $SQL .= " SET IMAG_PET_ADICIONADO_ADOCAO_USUARIO = :caminhoFoto";
                $SQL .= " WHERE CODE_PET_ADICIONADO_ADOCAO_USUARIO = :petId";
                $stmt = $conn->prepare($SQL);
                $stmt->bindParam(":caminhoFoto", $caminhoFoto);
                $stmt->bindParam(":petId", $petId);
                if($stmt->execute())
                    return true;
                else
                    throw new Exception(ExceptionTypeEnum::ERRO_INTERNO);
            }
        }

        public function getNomeDono(){
            return $this->nomeDono;
        }

        public function getEmailDono(){
            return $this->emailDono;
        }

        public function getEndDono(){
            return $this->endDono;
        }

        public function getTelDono(){
            return $this->telDono;
        }

        public function getKeyDono(){
            return $this->keyDono;
        }

        public static function removeCadastro($petId){
            $true = true;
            $conn = new DatabaseConnection();
            $SQL  = " UPDATE ".$conn->getDbName().".TB_PET_ADICIONADO_ADOCAO_USUARIO";
            $SQL .= " SET SYS_BOOL_PET_REMOVIDO_ADOCAO_USUARIO = :true";
            $SQL .= " WHERE CODE_PET_ADICIONADO_ADOCAO_USUARIO = :petId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":true", $true);
            $stmt->bindParam(":petId", $petId);
            if($stmt->execute())
                return true;
            else
                throw new Exception(ExceptionTypeEnum::ERRO_INTERNO);
        }

        public function isAdopted($userId, $petId){
            $conn = new DatabaseConnection();
            $SQL  = " SELECT * FROM ".$conn->getDbName().".TB_PET_INTERESSE_ADOCAO_USUARIO";
            $SQL .= " WHERE CDFK_USUARIO = :userId";
            $SQL .= " AND CDFK_PET_ADICIONADO_ADOCAO_USUARIO = :petId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":petId", $petId);
            if($stmt->execute()){
                if($stmt->rowCount() > 0)
                    return true;
                else
                    return false;
            }
        }

        public function cadastrarInteresse($userId, $petId){
            $conn = new DatabaseConnection();
            $SQL  = " INSERT INTO ".$conn->getDbName().".TB_PET_INTERESSE_ADOCAO_USUARIO (";
            $SQL .= " CDFK_USUARIO, CDFK_PET_ADICIONADO_ADOCAO_USUARIO)";
            $SQL .= " VALUES (:userId, :petId)";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":petId", $petId);
            if($stmt->execute())
                return true;
            else
                throw new Exception(ExceptionTypeEnum::ERRO_INTERNO);
        }

        public function removerInteresse($userId, $petId){
            $conn = new DatabaseConnection();
            $SQL  = " DELETE FROM ".$conn->getDbName().".TB_PET_INTERESSE_ADOCAO_USUARIO";
            $SQL .= " WHERE CDFK_USUARIO = :userId";
            $SQL .= " AND CDFK_PET_ADICIONADO_ADOCAO_USUARIO = :petId";
            $stmt = $conn->prepare($SQL);
            $stmt->bindParam(":userId", $userId);
            $stmt->bindParam(":petId", $petId);
            if($stmt->execute())
                return true;
            else
                throw new Exception(ExceptionTypeEnum::ERRO_INTERNO);
        }
    }
?>