<?php
    include_once "Enumeration.php";
    Class ExceptionTypeEnum extends Enumeration {
        const ERRO_DADOS_INCORRETOS_LOGIN = "Usuário ou senha incorretos.";
        const ERRO_NOME_INVALIDO = "O nome fornecido é inválido. Por favor, insira um nome válido.";
        const ERRO_EMAIL_CADASTRO = "O email digitado é invalido. Por favor, insira um email válido.";
        const ERRO_EMAIL_NULO_CADASTRO = "O campo email encontra-se nulo. Por favor, digite um email válido.";
        const ERRO_EMAIL_EXISTENTE_CADASTRO = "Já existe uma conta com este email cadastrado.";
        const ERRO_SENHA_CADASTRO = "Digite uma senha forte.";
        const ERRO_SENHA_NULA_CADASTRO = "Por favor, preencha corretamente os campos de senha e confirmação.";
        const ERRO_VALIDACAO_SENHA_CADASTRO = "As senhas digitadas não coincidem.";
        const ERRO_INTERNO = "Erro interno. Por favor, contate a equipe de suporte.";
        const ERRO_INTERNO_IMAGEM = "Erro ao salvar o arquivo.";
        const ERRO_APENAS_IMAGENS = "Por favor, envie apenas imagens.";
        const ERRO_ARQUIVO = "Por favor, envie um arquivo!";
        const ERRO_CATEGORIA = "Categoria inválida.";
        const ERRO_GENERO = "Gênero inválido.";
        const ERRO_ENDERECO_INVALIDO = "Endereço inválido.";
        const ERRO_TELEFONE_INVALIDO = "Telefone inválido.";
    }
?>