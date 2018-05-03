<?php
if (isset($_POST['entrar'])) {
    session_start();
    include 'bdConexao.php';
    $usuario = mysqli_real_escape_string($conexao, $_POST['usuario']);
    $senha = mysqli_real_escape_string($conexao, $_POST['senha']);
    //Erros
    //Verifica se existem campos vazios
    if(empty($usuario) || empty($senha)) {
        header("Location: index.php?vazio");
        exit();
    } else {
        $sql = "SELECT * FROM usuario WHERE nome_usuario=? OR email=?";
        $stmt = mysqli_stmt_init($conexao);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: index.php?erro");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $usuario, $usuario);
            if(!mysqli_stmt_execute($stmt)){
                header("Location: index.php?erro");
                exit();
            } else {
                $resultado = mysqli_stmt_get_result($stmt);
            }
        }
        $resultadoCheck = mysqli_num_rows($resultado);
        //Verifica se o nome de usuário ou email digitado pelo usuário foi encontrado no banco de dados
        if($resultadoCheck < 1) {
            header("Location: index.php?erro");
            exit();
        } else {
            if ($linha = mysqli_fetch_assoc($resultado)) {
                //De-hashing
                $senhaHashCheck = password_verify($senha, $linha['senha_hash']);
                //Verifica se a senha digitada é correta
                if($senhaHashCheck == false) {
                    header("Location: index.php?erro");
                    exit();
                } elseif ($senhaHashCheck == true) {
                    //Logar o usuário
                    $_SESSION['u_id'] = $linha['id'];
                    $_SESSION['u_nome'] = $linha['nome'];
                    $_SESSION['u_sobrenome'] = $linha['sobrenome'];
                    $_SESSION['u_email'] = $linha['email'];
                    $_SESSION['u_usuario'] = $linha['nome_usuario'];
                    header("Location: index.php");
                    exit();
                 }
            }
        }
    } 
} else {
        header("Location: index.php");
        exit();
}