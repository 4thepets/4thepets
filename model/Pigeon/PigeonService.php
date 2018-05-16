<?php
    include_once "PigeonClass.php";
    include_once "PigeonMethod.php";
    include_once "PigeonMethodType.php";
    include_once "model/AnimalEstimacao.php";
    include_once "model/Usuario.php";
    
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
                return $e->getMessage();
            }
                
        }

        private function validateUserName($value){
            return $value;
        }

        private function validateUserCpf($value){
            return $value;
        }

        private function validateUserEmail($value){
            return $value;
        }

        private function validateUserPassword($value){
            return $value;
        }
    
        private function validateUserImage($value){
            return $value;
        } 

        private function validatePetName($value){
            return $value;
        }

        private function validatePetImage($value){
            return $value;
        }
        
        private function validatePetAge($value){
            return $value;
        }

        private function validatePetCastrated($value){
            return $value;
        }

        private function validatePetCategory($value){
            return $value;
        }

        private function validatePetGender($value){
            return $value;
        }

        public function generateLog(){

        }

        // -- MANIPULAÇÃO DE DADOS EM DB -- //
        public static function pigeonManipulate($methodType = PigeonMethod::DEFAULT){
            return true;
        }


    }

    Class PigeonException{
        const ERRO_INTERNO = "Ocorreu um erro interno. Por favor, tente novamente.";
    }
?>