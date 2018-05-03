<!DOCTYPE html>
<html>
    <head>
        <title>Condominio - Cadastro</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="loginEstilo.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="funcoes.js"></script>
        <script src="jquery.mask.js"></script>
    </head>
    <body>
        <?php
        if(isset($_GET['e'], $_GET['t'])){
            include 'bdConexao.php';
            $email = mysqli_real_escape_string($conexao, $_GET["e"]);
            $tokenCadastro = mysqli_real_escape_string($conexao, $_GET["t"]);
            $sql = "SELECT token, uso FROM cadastro WHERE email = ? AND token = ?";
            $stmt = mysqli_stmt_init($conexao);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo'<div class="login">
                        <input type="text" value="Ocorreu um erro ao resetar a sua senha" disabled/>
                    </div>';
                exit;
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $email, $tokenCadastro);
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
                // Se o link para cadastro foi utilizado anteriormente, exibe uma mensagem de aviso
                if(isset($resultado_array[0]['uso']) && $resultado_array[0]['uso'] === '1'){
                    echo'<div class="login">
                            <input type="text" value="Este email ja foi cadastrado anteriormente" disabled/>
                        </div>';
                    exit();
                }
            }
            if(isset($_POST['cadastrar'])){
                // Recebe os dados submetidos pelo formulário de cadastro
                $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
                $sobrenome = mysqli_real_escape_string($conexao, $_POST['sobrenome']);
                $email = mysqli_real_escape_string($conexao, $_POST['email']);
                $telefoneTemp = mysqli_real_escape_string($conexao, $_POST['telefone']);
                $bloco = mysqli_real_escape_string($conexao, $_POST['bloco']);
                $ap_numero = mysqli_real_escape_string($conexao, $_POST['ap_numero']);
                $nomeUsuario = mysqli_real_escape_string($conexao, $_POST['nomeUsuario']);
                $senha = mysqli_real_escape_string($conexao, $_POST['senha']);
                $senhaConfirmar = mysqli_real_escape_string($conexao, $_POST['senhaConfirmar']);
                $substituir = array("(", ")", " ", "-");
                $telefone = str_replace($substituir,"",$telefoneTemp);
                
                // Procura por entradas com o mesmo email no banco de dados
                $sql = "SELECT email FROM usuario WHERE email = ?";
                $stmt = mysqli_stmt_init($conexao);
                mysqli_stmt_prepare($stmt, $sql);
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $resultado = mysqli_stmt_get_result($stmt);
                $resultado_array = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
                $resultadoCont = mysqli_num_rows($resultado);
                mysqli_stmt_close($stmt);
                
                // Verifica se os dados recebidos estão no formato correto
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $erro = '1';
                } else if (strlen($telefone) < 10){
                    $erro = '2'; 
                } else if ($senha !== $senhaConfirmar){
                    $erro = '3'; 
                } else if (strlen($senha) < 6){
                    $erro = '4';                
                } else if ($resultadoCont > 0){
                    $erro = '5';
                } else { 
                    $hash = password_hash($senha, PASSWORD_BCRYPT, ["cost" => 14]);
                    $sql = "INSERT INTO usuario (nome, sobrenome, email, nome_usuario, senha_hash, telefone, token, bloco, ap_numero) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $sql2 = "UPDATE cadastro SET uso = '1' WHERE token = ?";
                    $stmt = mysqli_stmt_init($conexao);
                    $stmt2 = mysqli_stmt_init($conexao);
                    if(!mysqli_stmt_prepare($stmt, $sql) || !mysqli_stmt_prepare($stmt2, $sql2)){
                        echo 'Ocorreu um erro ao realizar o seu cadastro!';
                    } else {
                        $token = '';
                        mysqli_stmt_bind_param($stmt, "sssssssss", $nome, $sobrenome, $email , $nomeUsuario, $hash, $telefone, $token, $bloco, $ap_numero);
                        mysqli_stmt_bind_param($stmt2, "s", $tokenCadastro);
                        // Verifica se ocorreu algum erro ao inserir as informações do usuário cadastrado no banco de dados
                        if(!mysqli_stmt_execute($stmt) || !mysqli_stmt_execute($stmt2)){
                            echo 'Ocorreu um erro ao realizar o seu cadastro!';
                        } else {
                            echo'<div class="mainReset">
                                    <div class="postado-container">
                                        <p><input type="text" value="Cadastro realizado com sucesso!" disabled/></p>
                                        <p class="pCentralizado"><button type="submit" form="telaLogin">Tela de login</button></p>
                                        <form id="telaLogin" method="POST" action="index.php"></form>
                                    </div>
                                </div>';
                            mysqli_stmt_close($stmt);
                            mysqli_stmt_close($stmt2);
                            exit();
                        }  
                    }
                }   
            }
            
            if ((!isset($_GET['cadastrar']) && $resultadoCont === 1 ) || isset($erro)) {
                 // Interface para cadastro
                echo'<div class="mainNovoAnuncio cadastro">
                    <form id="cadastro" class="login-container" method="POST">
                        <p><input id="nome" name="nome" type="text" placeholder="Nome" required/></p>
                        <p><input id="sobrenome" name="sobrenome" type="text" placeholder="Sobrenome" required/></p>
                        <p><input id="email" name="email" type="text" placeholder="E-mail" required/></p>
                        <p><input id="telefone" name="telefone" type="text" placeholder="Telefone" required/></p>
                        <p><input id="bloco" name="bloco" type="text" placeholder="Bloco/Nome do prédio (Opcional)"/></p>
                        <p><input id="ap_numero" name="ap_numero" type="text" placeholder="Número do apartamento (Opcional)"/></p>
                        <p><input id="nomeUsuario" name="nomeUsuario" type="text" placeholder="Nome de usuário" required/></p>
                        <p><input name="senha" type="password" placeholder="Digite a sua senha" required/></p>
                        <p><input name="senhaConfirmar" type="password" placeholder="Digite outra vez a sua senha" required/></p>
                        <p><textarea id="erro" name="erro" class="erroAnuncio" cols="200" rows="1" disabled hidden></textarea></p>
                        <p class="pCentralizado"><input name="cadastrar" type="submit" value="Cadastrar"></p>
                    </form>
                </div>';
                
                // Verifica se ocorreu algum erro nos dados digitados
                if(isset($erro)){
                    echo'<input id="controleNome" type="text" value="'.$nome.'" hidden/>
                        <input id="controleSobrenome" value="'.$sobrenome.'" hidden/>
                        <input id="controleEmail" type="text" value="'.$email.'" hidden/>
                        <input id="controleTelefone" type="text" value="'.$telefone.'" hidden/>
                        <input id="controleBloco" type="text" value="'.$bloco.'" hidden/>
                        <input id="controleAp_numero" type="text" value="'.$ap_numero.'" hidden/>
                        <input id="controleNomeUsuario" type="text" value="'.$nomeUsuario.'" hidden/>';
                    echo'<script>mudaValor(2);</script>';
                    if($erro === '1'){
                        echo'<script>erro("Por favor, digite um email válido");</script>';
                        echo'<script>outroMotivo(2);</script>';
                        unset ($erro);
                    } else if($erro === '2'){
                        echo'<script>erro("O telefone digitado tem poucos dígitos");</script>';
                        echo'<script>outroMotivo(2);</script>';
                        unset ($erro);
                    } else if($erro === '3'){
                        echo'<script>erro("Digite a mesma senha em ambos os campos");</script>';
                        echo'<script>outroMotivo(2);</script>';
                        unset ($erro);
                    } else if($erro === '4'){
                        echo'<script>erro("A senha deve ter no mínimo 6 caracteres");</script>';
                        echo'<script>outroMotivo(2);</script>';
                        unset ($erro);
                    } else if ($erro === '5'){
                        echo'<script>erro("Esse email ja foi cadastrado.");</script>';
                        echo'<script>outroMotivo(2);</script>';
                        unset ($erro);
                    }
                }
            } else if ($resultadoCont !== 1){
                echo'<div class="login">
                        <input type="text" value="Algo está errado! Verifique o link enviado para o seu email" disabled/>
                    </div>';
            }
        } else {
            echo'<div class="login">
                    <input type="text" value="Algo está errado! Verifique o link enviado para o seu email" disabled/>
                </div>';
        }
        ?>
        <script>
            var maskBehavior = function (val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            },
            options = {onKeyPress: function(val, e, field, options) {
                field.mask(maskBehavior.apply({}, arguments), options);
                }
            };
            $('#telefone').mask(maskBehavior, options);
        </script>
    </body>
</html>