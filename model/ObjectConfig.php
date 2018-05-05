<?php
    abstract Class ObjectConfig{
        protected $code;
        
        public function __construct($code){
            $this->code = $code;
        }

        //Transforma booleano em 0 ou 1 (MySQL)
        public static function toTinyInt($bool){
            if($bool) 
                return 1;
            else
                return 0;
        }

        public function cadastrarImagem($postFile){
            switch(static::class){
                case 'Usuario':
                    $dir = "user";
                    $table = "TB_USUARIO";
                    $codeColumn = "CODE_USUARIO";
                    $imageColumn = "IMAG_USUARIO";
                break;
                case 'AnimalEstimacao':
                    $dir = "pets";
                    $table = "TB_PET_ADICIONADO_ADOCAO_USUARIO";
                    $codeColumn = "CODE_PET_ADICIONADO_ADOCAO_USUARIO";
                    $imageColumn = "IMAG_PET_ADICIONADO_ADOCAO_USUARIO";
                break;
                default:
                    throw new Exception(ExceptionTypeEnum::ERRO_INTERNO);
            }
            $caminhoFoto = validarImagem($postFile, $dir);
            $conn = new DatabaseConnection();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("UPDATE ".$conn->getDbName().".".$table." SET ".$imageColumn." = :caminhoFoto WHERE ".$codeColumn." = :pkId");
            $stmt->bindParam("caminhoFoto", $caminhoFoto);
            $stmt->bindParam("pkId", $this->code);
            if($stmt->execute())
                return true;
        }

        public function validarImagem($file, $dir){
            if(isset($file['name']) && $file['error'] == 0){
                $tempFile = $file['tmp_name'];
                $extensionFile = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
                if(strstr('.jpg;.jpeg;.gif;.png', $extensionFile)){
                    $newFileName = uniqid(time()).'.'.$extensionFile;
                    $localFile = 'images/'.$dir.'/'.$newFileName;
                    if (move_uploaded_file($tempFile, $localFile)){
                        return $localFile;
                    }else
                        throw new Exception(ExceptionTypeEnum::ERRO_INTERNO_IMAGEM);
                }else
                    throw new Exception(ExceptionTypeEnum::ERRO_APENAS_IMAGENS);
            }else
                throw new Exception(ExceptionTypeEnum::ERRO_ARQUIVO);
        }
    }
?>