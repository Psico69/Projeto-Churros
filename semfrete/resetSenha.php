<!DOCTYPE html>
<html>
    <head>
        <title>Condominio - Nova Senha</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="loginEstilo.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="funcoes.js"></script>
    </head>
    <body>
        <?php
        //Caso o formulário com a nova senha tenha sido enviado
        if (isset($_POST['novaSenha'], $_POST['novaSenhaConfirmar'])){
            include 'bdConexao.php';
            $novaSenha = mysqli_real_escape_string($conexao, $_POST['novaSenha']);
            $novaSenhaConfirmar = mysqli_real_escape_string($conexao, $_POST['novaSenhaConfirmar']);
            $email = mysqli_real_escape_string($conexao, $_POST["emailControle"]);
            $token = mysqli_real_escape_string($conexao, $_POST["tokenControle"]);
            //Verifica se o usuário digitou a mesma senha em ambos os campos
            if ($novaSenha === $novaSenhaConfirmar){
                //Verifica se a senha digitada tem, no mínimo, 6 caracteres
                if(strlen($novaSenha) < 6){
                    echo'<div class="login">
                            <form id="formNovaSenha" class="login-container" method="POST">
                                <p><input name="novaSenha" type="password" placeholder="Digite a sua nova senha" maxlength="80" required/></p>
                                <p><input name="novaSenhaConfirmar" type="password" placeholder="Digite outra vez a sua nova senha" maxlength="90" required/></p>
                                <p><input name="esqueceuSenha" type="submit"></p>   
                            </form> 
                            <textarea id="erro" name="erro" class="erroResetSenha" cols="200" rows="1" disabled hidden></textarea>
                        </div>
                        <input name="emailControle" type="text" value="'.$email.'" form="formNovaSenha" hidden/>
                        <input name="tokenControle" type="text" value="'.$token.'" form="formNovaSenha" hidden/>';
                    echo'<script>erro("A senha deve ter no mínimo 6 caracteres");</script>';
                    echo'<script>outroMotivo(2);</script>';
                } else {
                    $novaSenhaHash = password_hash($novaSenha, PASSWORD_BCRYPT, ["cost" => 14]);
                    $sql = "UPDATE usuario SET senha_hash = ?, token = ? WHERE token = ? AND email = ?";
                    $stmt = mysqli_stmt_init($conexao);
                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        echo'<div class="login">
                                <input type="text" value="Ocorreu um erro ao resetar a sua senha" disabled/>
                            </div>';
                    } else {
                        $null = null;
                        mysqli_stmt_bind_param($stmt, "ssss", $novaSenhaHash, $null, $token, $email);
                        // Verifica se ocorreu algum erro ao acessar o banco de dados
                        if(!mysqli_stmt_execute($stmt)){
                            echo'<div class="login">
                                    <input type="text" value="Ocorreu um erro ao resetar a sua senha" disabled/>
                                </div>';
                            exit;
                        } else {
                            echo'<div class="mainReset">
                                    <div class="postado-container">
                                        <p><input type="text" value="Senha alterada com sucesso!" disabled/></p>
                                        <p class="pCentralizado"><button type="submit" form="telaLogin">Tela de login</button></p>
                                        <form id="telaLogin" method="POST" action="index.php"></form>
                                    </div>
                                </div>';
                        }  
                    }
                }
            } else {
                echo'<div class="login">
                        <form id="formNovaSenha" class="login-container" method="POST">
                            <p><input name="novaSenha" type="password" placeholder="Digite a sua nova senha" maxlength="80" required/></p>
                            <p><input name="novaSenhaConfirmar" type="password" placeholder="Digite outra vez a sua nova senha" maxlength="90" required/></p>
                            <p><input name="esqueceuSenha" type="submit"></p>   
                        </form> 
                        <textarea id="erro" name="erro" class="erroResetSenha" cols="200" rows="1" disabled hidden></textarea>
                    </div>
                    <input name="emailControle" type="text" value="'.$email.'" form="formNovaSenha" hidden/>
                    <input name="tokenControle" type="text" value="'.$token.'" form="formNovaSenha" hidden/>';
                echo'<script>erro("Digite a mesma senha em ambos os campos");</script>';
                echo'<script>outroMotivo(2);</script>';
            }
        // Verifica se existem as variáveis "email" e "token"   
        } else if (isset($_GET["e"]) && isset($_GET["t"])){
            include 'bdConexao.php';
            $email = mysqli_real_escape_string($conexao, $_GET["e"]);
            $token = mysqli_real_escape_string($conexao, $_GET["t"]);
            $sql = "SELECT token FROM usuario WHERE email = ? AND token = ?";
            $stmt = mysqli_stmt_init($conexao);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo'<div class="login">
                        <input type="text" value="Ocorreu um erro ao resetar a sua senha" disabled/>
                    </div>';
                exit;
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $email, $token);
                // Verifica se ocorreu algum erro ao acessar o banco de dados
                if(!mysqli_stmt_execute($stmt)){
                    echo'<div class="login">
                            <input type="text" value="Ocorreu um erro ao resetar a sua senha" disabled/>
                        </div>';
                    exit;
                } else {
                    $resultado = mysqli_stmt_get_result($stmt);
                    $resultado_array = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
                    $resultadoCont = mysqli_num_rows($resultado);
                }  
            }
            if ($resultadoCont > 0) {
                echo'<div class="login">
                        <form id="formNovaSenha" class="login-container" method="POST">
                            <p><input name="novaSenha" type="password" placeholder="Digite a sua nova senha" maxlength="80" required/></p>
                            <p><input name="novaSenhaConfirmar" type="password" placeholder="Digite outra vez a sua nova senha" maxlength="90" required/></p>
                            <p><input name="esqueceuSenha" type="submit"></p>   
                        </form> 
                    </div>
                    <input name="emailControle" type="text" value="'.$email.'" form="formNovaSenha" hidden/>
                    <input name="tokenControle" type="text" value="'.$token.'" form="formNovaSenha" hidden/>';
            } else {
                echo'<div class="login">
                        <input type="text" value="Algo está errado! Verifique o link enviado para o seu email" disabled/>
                    </div>';
            }
        } else {
            header("Location: index.php");
            exit;
        }
         ?>
    </body>
</html>
