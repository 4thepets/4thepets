<?php
    class AnimalEstimacao{
        private $nome;
        private $especie;
        private $sexo;
        private $idade;
        private $castracao;
        private $caminhoFoto;

        public function __construct($nome, $especie, $sexo, $idade, $castracao, $caminhoFoto){
            $this->nome = $nome;
            $this->especie = $especie;
            $this->sexo = $sexo;
            $this->idade = $idade;
            $this->castracao = $castracao;
            $this->caminhoFoto = $caminhoFoto;
        }

        public static function pesquisarPets($params, $interesse = false){
            
            return $petsArray;
        }
    }
?>