<?php
    include_once "Enumeration.php";
    Class IndexMessagesEnum extends Enumeration{
        const M0 = "Porquê não adotar um amigo hoje?";
        const M1 = "Porquê não conhecer um novo amigo?";
        const M2 = "Venha conhecer um novo integrante da família.";
        const M3 = "Milhares de pets procuram um amigo. Amigo igual você.";
        
        static function getMessage(){
            $array = [
                0 => "M0", 
                1 => "M1", 
                2 => "M2", 
                3 => "M3"
            ];

            $reflectionClass = new ReflectionClass(__CLASS__);
            $messagesArray = $reflectionClass->getConstants();
            return $messagesArray[$array[rand(0, count($array) - 1)]];
        }
    }
?>