<?php
    include_once "PigeonClass.php";
    include_once "PigeonMethod.php";
    include_once "PigeonMethodType.php";
    include_once "model/AnimalEstimacao.php";
    include_once "model/Usuario.php";
    include_once "enumeration/CategoriaEnum.php";
    include_once "enumeration/GeneroEnum.php";
    include_once "db/DatabaseConnection.php";
    
    Class Pigeon {

        // -- VALIDAÇÃO DE DADOS -- //
        public static function validate($class = PigeonClass::DEFAULT, $methodType = PigeonMethodType::DEFAULT, $value){
            try{
                $method = "validate".$class."".$methodType;
                if(method_exists(__CLASS__, $method))
                    return self::$method($value);
                else
                    throw new Exception(PigeonException::ERRO_INTERNO);
            }catch(Exception $e){
                throw new Exception($e->getMessage());
            }   
        }

        public static function find($class = PigeonClass::DEFAULT, $categoria = null, $genero = null, $castrado = null, $nome = null){
            $isRemoved = false;
            $petsArray = array();

            $conn = new DatabaseConnection();
            $SQL  = " SELECT * FROM ".$conn->getDbName().".TB_PET_ADICIONADO_ADOCAO_USUARIO";
            $SQL .= " WHERE SYS_BOOL_PET_REMOVIDO_ADOCAO_USUARIO = :isRemoved";
            
            if($categoria){
                $categoria = self::validate(PigeonClass::PET, PigeonMethodType::CATEGORY, $categoria);
                $SQL .= " AND NAME_CATEGORIA_PET_ADICIONADO_ADOCAO_USUARIO = :categoria";
            }

            if($genero){
                $genero = self::validate(PigeonClass::PET, PigeonMethodType::GENDER, $genero);
                $SQL .= " AND GNR_SEXO_PET_ADICIONADO_ADOCAO_USUARIO = :genero";
            }

            if($castrado){
                $castrado = self::validate(PigeonClass::PET, PigeonMethodType::CASTRATED, $castrado);
                $SQL .= " AND BOOL_CASTRACAO_PET_ADICIONADO_ADOCAO_USUARIO = :castrado";
            }

            if($nome){
                $SQL .= " AND NAME_PET_ADICIONADO_ADOCAO_USUARIO LIKE :nome";
            }

            $stmt = $conn->prepare($SQL);
            $stmt->bindParam("isRemoved", $isRemoved);

            if($categoria){
                $stmt->bindParam("categoria", $categoria);
            }
            if($genero){
                $stmt->bindParam("genero", $genero);
            }
            if(isset($castrado)){
                $stmt->bindParam("castrado", $castrado);
            }
            if($nome){
                $nome = "%".$nome."%";
                $stmt->bindParam("nome", $nome);
            }

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
                        $row['CDFK_USUARIO']
                    );
                }
                return $petsArray;
            }
            else{
                throw new Exception(PigeonException::ERRO_INTERNO);
            }
        }

        private function validateUserName($value){
            if(is_null($value))
                throw new Exception(PigeonException::ERRO_NOME_INVALIDO);
            $pattern = "/^[A-Za-zÀ-ú0-9_\s]{1,24}$/";
            if(preg_match($pattern, $value))
                return $value;
            else
                throw new Exception(PigeonException::ERRO_NOME_INVALIDO);
        }

        private function validateUserCpf($value){
            if(is_null($value)) 
                throw new Exception(PigeonException::ERRO_CPF_INVALIDO);
            $value = preg_replace('[^0-9]', '', $value);
            $value = str_pad($value, 11, '0', STR_PAD_LEFT);
            if (strlen($value) != 11) 
                throw new Exception(PigeonException::ERRO_CPF_INVALIDO);
            else if ($value == '00000000000' || 
                     $value == '11111111111' || 
                     $value == '22222222222' || 
                     $value == '33333333333' || 
                     $value == '44444444444' || 
                     $value == '55555555555' || 
                     $value == '66666666666' || 
                     $value == '77777777777' || 
                     $value == '88888888888' || 
                     $value == '99999999999') 
                throw new Exception(PigeonException::ERRO_CPF_INVALIDO);
            else {   
                for ($t = 9; $t < 11; $t++) {     
                    for ($d = 0, $c = 0; $c < $t; $c++) {
                        $d += $value{$c} * (($t + 1) - $c);
                    }
                    $d = ((10 * $d) % 11) % 10;
                    if ($value{$c} != $d)
                        throw new Exception(PigeonException::ERRO_CPF_INVALIDO);
                }
                return $value;
            }
        }

        private function validateUserEmail($value){
            if(is_null($value))
                throw new Exception(PigeonException::ERRO_EMAIL_NULO_CADASTRO);
            
            else if(!filter_var($value, FILTER_VALIDATE_EMAIL))
                throw new Exception(PigeonException::ERRO_EMAIL_CADASTRO);

            else
                return $value;
        }

        private function validateUserPassword($value){
            if(!is_array($value))
                throw new Exception(PigeonException::ERRO_INTERNO);

            if(is_null($value[0]) || is_null($value[1]))
                throw new Exception(PigeonException::ERRO_SENHA_NULA_CADASTRO);
                
            if($value[0] != $value[1])
                throw new Exception(PigeonException::ERRO_VALIDACAO_SENHA_CADASTRO);

            $pattern = "/^[A-Za-z0-9!@#$%¨&*()_\-\[\]\\/<>:;]{1,16}$/";
            if(preg_match($pattern, $value[0]))
                return md5($value[0]);
            else
                throw new Exception(PigeonException::ERRO_VALIDACAO_SENHA_GRANDE);
                
        }
    
        private function validateUserImage($value){
            if(isset($file['name']) && $file['error'] == 0){
                $tempFile = $file['tmp_name'];
                $extensionFile = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
                if(strstr('.jpg;.jpeg;.gif;.png', $extensionFile)){
                    if(filesize($file['name']) < 3072)
                        return true;
                }
            }
            throw new Exception(PigeonException::ERRO_IMAGEM_GRANDE);
        } 

        private function validateUserAddress($value){
            if(is_null($value))
                throw new Exception(PigeonException::ERRO_ENDERECO_INVALIDO);
            $pattern = "/^[A-Za-zÀ-ú0-9_\s,\-()]{1,60}$/";
            if(preg_match($pattern, $value))
                return $value;
            else
                throw new Exception(PigeonException::ERRO_ENDERECO_INVALIDO);
        }

        private function validateUserTelephone($value){
            if(is_null($value))
                throw new Exception(PigeonException::ERRO_TELEFONE_INVALIDO);
            $pattern = "/^[0-9]{1,11}$/";
            if(preg_match($pattern, $value))
                return $value;
            else
                throw new Exception(PigeonException::ERRO_TELEFONE_INVALIDO);
        }

        private function validatePetName($value){
            if(is_null($value))
                throw new Exception(PigeonException::ERRO_NOME_INVALIDO);
            $pattern = "/^[A-Za-zÀ-ú0-9\s]{1,24}$/";
            if(preg_match($pattern, $value))
                return $value;
            else
                throw new Exception(PigeonException::ERRO_NOME_INVALIDO);
        }
        
        private function validatePetAge($value){
            if(is_null($value))
                throw new Exception(PigeonException::ERRO_IDADE_INVALIDA);
            
            if(!is_numeric($value))
                throw new Exception(PigeonException::ERRO_IDADE_INVALIDA);
    
            $pattern = "/^[0-9]+$/";
            if(!preg_match($pattern, $value))
                throw new Exception(PigeonException::ERRO_IDADE_INVALIDA);
            
            if($value < 0 || $value > 99)
                throw new Exception(PigeonException::ERRO_IDADE_INVALIDA);
             
            return $value;

        }

        private function validatePetCategory($value){
            if(CategoriaEnum::isValidValue($value))
                return $value;
            else
                throw new Exception(PigeonException::ERRO_CATEGORIA);   
        }

        private function validatePetGender($value){
            if(GeneroEnum::isValidValue($value))
                return $value;
            else
                throw new Exception(PigeonException::ERRO_GENERO);    
        }

        private function validatePetCastrated($value){
            if(strtolower($value) == "y") 
                return true;
            else
                return false;
        }
    }

    Class PigeonException{
        const ERRO_INTERNO = "Ocorreu um erro interno. Por favor, tente novamente.";
        const ERRO_NOME_INVALIDO = "Por favor, digite um nome válido.";
        const ERRO_EMAIL_CADASTRO = "O email digitado é invalido. Por favor, insira um email válido.";
        const ERRO_EMAIL_NULO_CADASTRO = "O campo email encontra-se nulo. Por favor, digite um email válido.";
        const ERRO_EMAIL_EXISTENTE_CADASTRO = "Já existe uma conta com este email cadastrado.";
        const ERRO_SENHA_CADASTRO = "Digite uma senha forte.";
        const ERRO_SENHA_NULA_CADASTRO = "Por favor, preencha corretamente os campos de senha e confirmação.";
        const ERRO_VALIDACAO_SENHA_CADASTRO = "As senhas digitadas não coincidem.";
        const ERRO_VALIDACAO_SENHA_GRANDE = "Por favor, digite uma senha menor que 15 caracteres.";
        const ERRO_INTERNO_IMAGEM = "Erro ao salvar o arquivo.";
        const ERRO_APENAS_IMAGENS = "Por favor, envie apenas imagens.";
        const ERRO_IMAGEM_GRANDE = "Por favor, envie uma imagem de tamanho menor.";
        const ERRO_ARQUIVO = "Por favor, envie um arquivo!";
        const ERRO_CATEGORIA = "Categoria inválida.";
        const ERRO_GENERO = "Gênero inválido.";
        const ERRO_ENDERECO_INVALIDO = "Endereço inválido. Por favor, digite um endereço válido.";
        const ERRO_TELEFONE_INVALIDO = "Telefone inválido. Por favor, digite um telefone válido. (DD9XXXXxxxx)";
        const ERRO_CPF_INVALIDO = "CPF inválido. Digite um CPF válido.";
        const ERRO_IDADE_INVALIDA = "Idade do pet inválida. Por favor, digite uma idade válida. (0 - 99)";
    }
?>