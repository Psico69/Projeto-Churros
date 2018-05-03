<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Condominio - Anuncio</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="loginEstilo.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="jquery.mask.js"></script>
        <script src="funcoes.js"></script>
    </head>
    <body>
        <?php
        if (isset($_SESSION['u_id'])){
            // Cabeçalho de busca e navegação
            echo'<div class="header">
                        <ul id="headerParent" class="headerDesktop">
                            <li>
                                <ul id="headerBusca">
                                    <li id="header1">
                                        <form name="busca" method="GET" action="index.php">'; 
                                        // Verifica se alguma busca foi realizada para não exibir erro de índice indefinido na variável "$_GET['busca']"
                                        if (isset($_GET['busca'])){
                                            // Exibe o campo de busca com o valor da busca anterior realizada
                                            echo'    <input id="busca" name="busca" type="text" placeholder="Buscar por palavra-chave" value="'.$_GET['busca'].'"/>';
                                        } else {
                                            // Exibe o campo de busca sem valor, pois nenhuma busca anterior foi realizada
                                            echo'    <input id="busca" name="busca" type="text" placeholder="Buscar por palavra-chave"/>';
                                        }        
                                        echo'    <button id="submitBusca" name="submitBusca" type="submit" class="fa fa-search"></button>    
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <ul id="headerBotoes">
                                    <li id="headerBtn1"><a class="headerBotao" href="index.php">Principal</a></li>
                                    <li id="headerBtn2"><a class="headerBotao" href="anuncio.php?anunciar">Anunciar</a></li>
                                    <li id="headerBtn3" class="float-right">
                                        <div class="dropdown">
                                            <button class="dropbtn">Olá, '.$_SESSION['u_nome'].'</button>
                                            <div class="dropdown-content">
                                                <a href="anuncio.php?meusAnuncios">Meus Anúncios</a>
                                                <a href="editarCadastro.php">Minha Conta</a>
                                                <a href="logout.php?sair">Sair</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                </div>';
                            
            include 'bdConexao.php';
            $sql = "SELECT * FROM usuario WHERE id = ? AND email = ?";
            $stmt = mysqli_stmt_init($conexao);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo'<div class="login">
                        <input type="text" value="Ocorreu um erro ao editar seus dados!" disabled/>
                    </div>';
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $_SESSION['u_id'], $_SESSION['u_email']);
                if(!mysqli_stmt_execute($stmt)){
                    echo'<div class="login">
                            <input type="text" value="Ocorreu um erro ao editar seus dados!" disabled/>
                        </div>';
                    exit();
                }
            }
            $resultado = mysqli_stmt_get_result($stmt);
            $resultado_array = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
            
            if(isset($_POST['alteracoesCadastro'])){
                // Recebe os dados submetidos pelo formulário de cadastro
                $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
                $sobrenome = mysqli_real_escape_string($conexao, $_POST['sobrenome']);
                $email = mysqli_real_escape_string($conexao, $_POST['email']);
                $telefoneTemp = mysqli_real_escape_string($conexao, $_POST['telefone']);
                $bloco = mysqli_escape_string($conexao, $_POST['bloco']);
                $ap_numero = mysqli_escape_string($conexao, $_POST['ap_numero']);
                $nomeUsuario = mysqli_real_escape_string($conexao, $_POST['nomeUsuario']);
                $novaSenha = mysqli_real_escape_string($conexao, $_POST['novaSenha']);
                $novaSenhaConfirmar = mysqli_real_escape_string($conexao, $_POST['novaSenhaConfirmar']);
                $senha = mysqli_real_escape_string($conexao, $_POST['senha']);
                // Substitui caracteres desnecessários do telefone para salvar no banco de dados
                $substituir = array("(", ")", " ", "-");
                $telefone = str_replace($substituir,"",$telefoneTemp);
                
                // Procura por entradas com o mesmo email no banco de dados
                $sql = "SELECT email FROM usuario WHERE email = ? AND id != ?;";
                $stmtEmail = mysqli_stmt_init($conexao);
                mysqli_stmt_prepare($stmtEmail, $sql);
                mysqli_stmt_bind_param($stmtEmail, "ss", $email, $_SESSION['u_id']);
                mysqli_stmt_execute($stmtEmail);
                $resultadoEmail = mysqli_stmt_get_result($stmtEmail);
                $resultadoEmailCont = mysqli_num_rows($resultadoEmail);
                
                // Verifica se os dados recebidos estão no formato correto
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $erro = '1';
                } else if (strlen($telefone) < 10) {
                    $erro = '2'; 
                } else if ($novaSenha !=='' && $novaSenhaConfirmar !== '' && $novaSenha !== $novaSenhaConfirmar){
                    $erro = '3'; 
                } else if ($novaSenha !=='' && $novaSenhaConfirmar !== '' && strlen($novaSenha) < 6){
                    $erro = '4';                
                } else if (!password_verify($senha, $resultado_array['0']['senha_hash'])){
                    $erro = '5';
                } else if ($resultadoEmailCont > 0) {
                    $erro = '6';
                } else {
                    if($novaSenha !=='' && $novaSenhaConfirmar !== ''){
                        $novaSenha_hash = password_hash($novaSenha, PASSWORD_BCRYPT, ["cost" => 14]);
                        $sql = "UPDATE usuario SET nome=?, sobrenome=?, email=?, nome_usuario=?, senha_hash=?, telefone=?, bloco=?, ap_numero=? WHERE id=? AND email=?";
                    } else {
                        $sql = "UPDATE usuario SET nome=?, sobrenome=?, email=?, nome_usuario=?, telefone=?, bloco=?, ap_numero=? WHERE id=? AND email=?";
                    }
                    
                    $stmt = mysqli_stmt_init($conexao);
                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        echo'<div class="login">
                                <input type="text" value="Ocorreu um erro ao editar o seu cadastro" disabled/>
                            </div>';
                    } else {
                        if($novaSenha !=='' && $novaSenhaConfirmar !== ''){
                            mysqli_stmt_bind_param($stmt, "ssssssssss", $nome, $sobrenome, $email , $nomeUsuario, $novaSenha_hash, $telefone, $bloco, $ap_numero, $_SESSION['u_id'], $_SESSION['u_email']);
                        } else {
                            mysqli_stmt_bind_param($stmt, "sssssssss", $nome, $sobrenome, $email , $nomeUsuario, $telefone, $bloco, $ap_numero, $_SESSION['u_id'], $_SESSION['u_email']);
                        }
                        // Verifica se ocorreu algum erro ao inserir as informações do usuário cadastrado no banco de dados
                        if(!mysqli_stmt_execute($stmt)){
                            echo'<div class="login">
                                    <input type="text" value="Ocorreu um erro ao editar o seu cadastro" disabled/>
                                </div>';
                        } else {
                            // Alterar dados da sessão do usuário logado de acordo com os dados editados
                            $_SESSION['u_nome'] = $nome;
                            $_SESSION['u_sobrenome'] = $sobrenome;
                            $_SESSION['u_email'] = $email;
                            $_SESSION['u_usuario'] = $nomeUsuario;
                            echo'<div class="mainReset">
                                    <div class="postado-container">
                                        <p><input type="text" value="Alterações realizadas!" disabled/></p>
                                        <p class="pCentralizado"><button type="submit" form="paginainicial">Página Inicial</button></p>
                                        <form id="paginainicial" method="POST" action="index.php"></form>
                                    </div>
                                </div>';
                            exit();
                        }  
                    }
                }
            }
            // Interface para edição de cadastro
            echo'<div class="mainEditarCadastro">
                    <form id="cadastro" class="login-container" method="POST">
                        <p><input id="nome" name="nome" class="mainEditarCadastroInput" type="text" placeholder="Nome" required/></p>
                        <p><input id="sobrenome" name="sobrenome" class="mainEditarCadastroInput" type="text" placeholder="Sobrenome" required/></p>
                        <p><input id="email" name="email" class="mainEditarCadastroInput" type="text" placeholder="E-mail" required/></p>
                        <p><input id="telefone" name="telefone" class="mainEditarCadastroInput" type="text" placeholder="Telefone" required/></p>
                        <p><input id="bloco" name="bloco" class="mainEditarCadastroInput" type="text" placeholder="Bloco/Nome do prédio (Opcional)"/></p>
                        <p><input id="ap_numero" name="ap_numero" class="mainEditarCadastroInput" type="text" placeholder="Número do apartamento (Opcional)"/></p>
                        <p><input id="nomeUsuario" name="nomeUsuario" class="mainEditarCadastroInput" type="text" placeholder="Nome de usuário" required/></p>
                        
                        <div class="checkboxApagar">
                        <h1 class="h1EditarCadastro"> Alterar Senha? </h1>
                            <div class="bordaInterna">
                                <p class="sPadding"><input type="radio" name="alterarSenha" value="Sim" onClick="senhaAlterar(0)"/>Sim</p>
                                <p class="sPadding"><input type="radio" name="alterarSenha" value="Não" onClick="senhaAlterar(1)" checked/>Não</p>
                            </div>
                        </div>
                        
                        <p><input id="novaSenha" name="novaSenha" type="password" placeholder="Digite a sua nova senha" maxlength="80" hidden/></p>
                        <p><input id="novaSenhaConfirmar" name="novaSenhaConfirmar" type="password" placeholder="Digite outra vez a sua nova senha" maxlength="80" hidden/></p>
                        
                        <h1 class="h1EditarCadastro"> Digite sua senha atual para confirmar as alterações </h1>
                        <p><input name="senha" class="mainEditarCadastroInput" type="password" placeholder="Digite a sua senha atual" required/></p>
                        <p><textarea id="erro" name="erro" class="erroAnuncio" cols="200" rows="1" disabled hidden></textarea></p>
                        <p class="pCentralizado"><input name="alteracoesCadastro" class="mainEditarCadastroInput" type="submit" value="Salvar Alterações"></p>
                    </form>
                </div>';
            
            echo'<input id="controleNome" type="text" value="'.$resultado_array['0']['nome'].'" hidden/>
                <input id="controleSobrenome" value="'.$resultado_array['0']['sobrenome'].'" hidden/>
                <input id="controleEmail" type="text" value="'.$resultado_array['0']['email'].'" hidden/>
                <input id="controleTelefone" type="text" value="'.$resultado_array['0']['telefone'].'" hidden/>
                <input id="controleBloco" type="text" value="'.$resultado_array['0']['bloco'].'" hidden/>
                <input id="controleAp_numero" type="text" value="'.$resultado_array['0']['ap_numero'].'" hidden/>
                <input id="controleNomeUsuario" type="text" value="'.$resultado_array['0']['nome_usuario'].'" hidden/>';
            echo'<script>mudaValor(2);</script>';
                
            // Verifica se ocorreu algum erro nos dados digitados
            if(isset($erro)){
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
                    echo'<script>erro("A nova senha deve ter no mínimo 6 caracteres");</script>';
                    echo'<script>outroMotivo(2);</script>';
                    unset ($erro);
                } else if($erro === '5'){
                    echo'<script>erro("Senha incorreta");</script>';
                    echo'<script>outroMotivo(2);</script>';
                    unset ($erro);
                } else if ($erro === '6'){
                    echo'<script>erro("Esse email ja foi cadastrado.");</script>';
                    echo'<script>outroMotivo(2);</script>';
                    unset ($erro);
                }
            }
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