<?php
header('Content-Type: text/html; charset=utf-8');

$bdNomeservidor = "localhost";
$bdNomeusuario = "root";
$bdSenha = "";
$bdNome = "condominio";

$conexao = mysqli_connect($bdNomeservidor, $bdNomeusuario, $bdSenha, $bdNome);

mysqli_select_db($conexao, $bdNome);
mysqli_query($conexao, "SET NAMES 'utf8'");
mysqli_query($conexao, 'SET character_set_connection=utf8');
mysqli_query($conexao, 'SET character_set_client=utf8');
mysqli_query($conexao, 'SET character_set_results=utf8');