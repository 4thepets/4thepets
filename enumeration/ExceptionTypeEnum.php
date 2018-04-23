<?php
    include_once "Enumeration.php";
    Class ExceptionTypeEnum extends Enumeration {
        const ERRO_DADOS_INCORRETOS_LOGIN = "Usuário ou senha incorretos.";
        const ERRO_EMAIL_CADASTRO = "O email digitado é invalido. Por favor, insira um email válido.";
        const ERRO_EMAIL_NULO_CADASTRO = "O campo email encontra-se nulo. Por favor, digite um email válido.";
        const ERRO_EMAIL_EXISTENTE_CADASTRO = "Já existe uma conta com este email cadastrado.";
        const ERRO_SENHA_CADASTRO = "Digite uma senha forte. (8 caracteres, 1 maiúsculo, 1 especial e 1 número)";
        const ERRO_SENHA_NULA_CADASTRO = "Por favor, preencha corretamente os campos de senha e confirmação.";
        const ERRO_VALIDACAO_SENHA_CADASTRO = "As senhas digitadas não coincidem.";
        const ERRO_INTERNO = "Erro interno. Por favor, contate a equipe de suporte.";
    }
?>