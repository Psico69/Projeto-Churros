<?php
date_default_timezone_set('America/Sao_Paulo');
include 'bdConexao.php';

$emailLista = fopen("lista.txt", "r") or die("Ocorreu um erro ao abrir o arquivo!");
$headers = 'From: naoresponda.teste.teste@gmail.com';

while(!feof($emailLista)) {
    $email = trim(fgets($emailLista));
    $token = uniqid(rand(10,99), true);
    $cadastroUrl = "http://localhost/Condominio/cadastro.php?e=".$email."&t=".$token;
    $msgEmail = "Olá,\n\nClique no link abaixo para cadastrar uma nova conta no portal do seu condomínio. Se não puder clicar no link, copie-o e cole-o na barra de endereços do seu navegador.\n\n" . $cadastroUrl . "\nSe você não requisitou cadastramento no portal, desconsidere esse email. ";
    if(filter_var($email, FILTER_VALIDATE_EMAIL) && mail($email, "=?utf-8?Q?Cadastro condomínio?=", $msgEmail, $headers)){
        $sql = "INSERT INTO cadastro (email, token, uso) VALUES (?, ?, ?)";
        $stmt = mysqli_stmt_init($conexao);
        // Verifica se ocorreu algum erro ao preparar a query 
        if(!mysqli_stmt_prepare($stmt, $sql)){
            $erroLog = fopen("erroLog.txt", "w") or die("Ocorreu um erro ao gerar arquivo de log!");
            fwrite($erroLog, '[' . date("d-m-Y"). ']' . '[mysqli_stmt_prepare ERRO]' . $email);
            fclose($erroLog);
        } else {
            $uso = '0';
            mysqli_stmt_bind_param($stmt, "sss", $email, $token, $uso);
            // Verifica se ocorreu algum erro ao executar a query
            if(!mysqli_stmt_execute($stmt)){
                $erroLog = fopen("erroLog.txt", "w") or die("Ocorreu um erro ao gerar arquivo de log!");
                fwrite($erroLog, '[' . date("d-m-Y"). ']' . '[mysqli_stmt_execute ERRO]' . $email);
                fclose($erroLog);
            } else {
                $log = fopen("log.txt", "w") or die("Ocorreu um erro ao gerar arquivo de log!");
                fwrite($log, '[' . date("d-m-Y"). ']' . '[SUCESSO]' . $email);
                fclose($log);
                mysqli_stmt_close($stmt);
            }
        }     
    } else {
        $emailX = $email.PHP_EOL;
        $emailErro = fopen("listaErro.txt", "a") or die("Ocorreu um erro ao gerar arquivo de log!");
        fwrite($emailErro, $emailX);
        fclose($emailErro);
    }
}
fclose($emailLista);
?>