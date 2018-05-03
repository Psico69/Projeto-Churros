<?php
    date_default_timezone_set('America/Sao_Paulo');
    include 'bdConexao.php';
    // Preparação das querys
    $sql = "SELECT token, id FROM usuario WHERE token IS NOT NULL";
    $stmt = mysqli_stmt_init($conexao);
    // Preparação da query "SELECT"
    if (!mysqli_stmt_prepare($stmt, $sql)){
        $erroLog = fopen("erroLog.txt", "w") or die("Ocorreu um erro ao gerar arquivo de log!");
        fwrite($erroLog, '['.date("d/m/Y-H:i:s").']'.'[mysqli_stmt_prepare 1 ERRO deletaTokenSenha.php]'.PHP_EOL);
        fclose($erroLog);
    } else {
        // Execuçã da query "SELECT"
        if (!mysqli_stmt_execute($stmt)){
            $erroLog = fopen("erroLog.txt", "w") or die("Ocorreu um erro ao gerar arquivo de log!");
            fwrite($erroLog, '['.date("d/m/Y-H:i:s").']'.'[mysqli_stmt_execute 1 ERRO deletaTokenSenha.php]'.PHP_EOL);
            fclose($erroLog);
        } else {
            // Recuperando resultados e inserindo em um array
            $resultado = mysqli_stmt_get_result($stmt);
            $resultado_array = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
            $numUsuarios = mysqli_num_rows($resultado);
            // Caso algum valor seja retornado pela query "SELECT"
            if ($numUsuarios > 0){
                $hora = date('H');
                $sql = "UPDATE usuario SET token = NULL WHERE id = ? AND token = ?";
                for ($x=0; $x < $numUsuarios; $x++){
                    $prefixoToken = substr($resultado_array[$x]['token'], 0, 2);
                    // Verifica se o token deve ser deletado, comparando seu prefixo com a hora atual
                    if ($prefixoToken == $hora){
                        // Preparação da query "UPDATE"
                        if (!mysqli_stmt_prepare($stmt, $sql)){
                            $erroLog = fopen("erroLog.txt", "w") or die("Ocorreu um erro ao gerar arquivo de log!");
                            fwrite($erroLog, '['.date("d/m/Y-H:i:s").']'.'[mysqli_stmt_prepare 2 ERRO deletaTokenSenha.php]'.PHP_EOL);
                            fclose($erroLog);
                        } else {
                             mysqli_stmt_bind_param($stmt, "ss", $resultado_array[$x]['id'], $resultado_array[$x]['token']);
                            // Execução da query "UPDATE"
                            if (!mysqli_stmt_execute($stmt)){
                                $erroLog = fopen("erroLog.txt", "w") or die("Ocorreu um erro ao gerar arquivo de log!");
                                fwrite($erroLog, '['.date("d/m/Y-H:i:s").']'.'[mysqli_stmt_execute 2 ERRO deletaTokenSenha.php]'.PHP_EOL);
                                fclose($erroLog);
                            }
                        }  
                    }
                }   
            }  
        }
    }