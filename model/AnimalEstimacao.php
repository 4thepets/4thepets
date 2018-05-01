<?php
    include_once 'db/DatabaseConnection.php';
    include_once 'model/ObjectConfig.php';
    include_once 'enumeration/ExceptionTypeEnum.php';
    include_once 'enumeration/CategoriaEnum.php';
    
    Class AnimalEstimacao extends ObjectConfig{
        private $nome;
        private $categoria;
        private $sexo;
        private $idade;
        private $castracao;
        private $caminhoFoto;

        public function __construct($code, $nome, $categoria, $sexo, $idade, $castracao, $caminhoFoto){
            parent::__construct($code);
            $this->nome = $nome;
            $this->categoria = $categoria;
            $this->sexo = $sexo;
            $this->idade = $idade;
            $this->castracao = $castracao;
            $this->caminhoFoto = $caminhoFoto;
        }

        public function efetuarCadastro($nomeAnimal, $categoriaAnimal, $sexoAnimal, $idadeAnimal, $isCastrado, $caminhoFoto){
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $SQL = "INSERT INTO TB_PET_ADICIONADO_ADOCAO_USUARIO"
        }

        public static function pesquisarPets($params, $interesse){
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $SQL  = " SELECT * FROM ".$conn->getDbName().".TB_PET_ADICIONADO_ADOCAO_USUARIO";
            $SQL .= " ORDER BY SYS_DATE_PET_ADICIONADO_ADOCAO_USUARIO DESC";
            return $petsArray;
        }
    }
?>