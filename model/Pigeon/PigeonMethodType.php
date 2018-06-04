<?php
    include_once "enumeration/Enumeration.php";
    Class PigeonMethodType extends Enumeration{
        //Geral
        const NAME = "Name";
        const IMAGE = "Image";
        
        //Usuario
        const EMAIL = "Email";
        const CPF = "Cpf";
        const PASSWORD = "Password";
        const ADDRESS = "Address";
        const TELEPHONE = "Telephone";
        
        //AnimalEstimacao
        const AGE = "Age";
        const CASTRATED = "Castrated";
        const CATEGORY = "Category";
        const GENDER = "Gender";
        
    }
?>