<?php
    abstract Class ObjectConfig{
        private $code;
        
        //Transforma booleano em 0 ou 1 (MySQL)
        public static function toTinyInt($bool){
            if($bool) 
                return 1;
            else
                return 0;
        }
    }
?>