<!DOCTYPE html>
<html>
    <head>
        <title>Condominio - Esqueci a senha</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="loginEstilo.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="funcoes.js"></script>
    </head>
    <body>
        <?php
        date_default_timezone_set('America/Sao_Paulo');
        if (isset($_POST["esqueceuSenha"])){
            include 'bdConexao.php';
	    // Recebe o email e verifica se é válido
	    if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
	        $email = mysqli_real_escape_string($conexao, $_POST["email"]);
                $sql = "SELECT email, id FROM usuario WHERE email = ?";
	        $stmt = mysqli_stmt_init($conexao);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    echo'<div class="login">
                            <input type="text" value="Ocorreu um erro ao resetar a sua senha" disabled/>
                        </div>';
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $email);
                    mysqli_stmt_execute($stmt);
                    $resultado = mysqli_stmt_get_result($stmt);
                    $emailUsuario = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
                }
                // Verifica se algum usuário com o email digitado foi encontrado  
                if ($emailUsuario){
                    // Cria um prefixo para o token baseado no horário atual
                    $hora = date('H');
                    if ($hora >= 00){
                        if ($hora >= 06){
                            if ($hora >= 12){
                                if ($hora >= 18){
                                    if ((24 - $hora) > 02){
                                        $prefixoToken = '00';
                                    } else {
                                        $prefixoToken = '06';
                                    }
                                } else {
                                    if ((18 - $hora) > 02){
                                        $prefixoToken = '18';
                                    } else {
                                        $prefixoToken = '00';
                                    }
                                }
                            } else {
                                if ((12 - $hora) > 02){
                                    $prefixoToken = '12';
                                } else {
                                    $prefixoToken = '18';
                                }
                            }
                        } else {
                            if ((06 - $hora) > 02){
                                $prefixoToken = '06';
                            } else {
                                $prefixoToken = '12';
                            }
                        }
                    }
                    // Cria um token único usado para resetar a senha
                    $token = uniqid($prefixoToken, true);
                    $expira = $prefixoToken . ':00';
	            // Cria um endereço para resetar a senha
	            $resetUrl = "http://localhost/Condominio/resetSenha.php?e=".$emailUsuario[0]['email']."&t=".$token;	         
	            // Prepara a mensagem exibida no email recebido pelo usuário
	            $msgEmail = "Olá,\n\nClique no link abaixo para recuperar a sua senha. Se não puder clicar no link, copie-o e cole-o na barra de endereços do seu navegador.\n\n".$resetUrl."\n\nEsse link é válido até as ".$expira.".\n\nSe você não requisitou a recuperação da sua senha, desconsidere esse email. ";
                    $headers = 'From: naoresponda.teste.teste@gmail.com';
                    // Insere o token no registro do usuário que requisitou o reset da senha e cria um evento para deleta-lo depois de 1 hora
                    $deleteToken = 'deleteToken' . $emailUsuario[0]['id'];
                    $sql = "UPDATE usuario SET token = ? WHERE email = ? AND id = ?";
                    $sqlEventOn = "SET GLOBAL event_scheduler = ON";
                    $sqlCreateEvent = "CREATE EVENT IF NOT EXISTS ".$deleteToken." ON SCHEDULE AT (NOW() + INTERVAL 1 HOUR) DO UPDATE usuario SET token = NULL WHERE email = '".$emailUsuario[0]['email']."' AND id = '".$emailUsuario[0]['id']."'";
                    $stmt = mysqli_stmt_init($conexao);
                    $stmtEventOn = mysqli_stmt_init($conexao);
                
                    if(!mysqli_stmt_prepare($stmt, $sql) || !mysqli_stmt_prepare($stmtEventOn, $sqlEventOn)){
                        echo'<div class="login">
                                <input type="text" value="Ocorreu um erro ao resetar a sua senha" disabled/>
                            </div>';
                    } else {
                        mysqli_stmt_bind_param($stmt, "sss", $token, $emailUsuario[0]['email'], $emailUsuario[0]['id']);
                        // Verifica se ocorreu algum erro ao inserir o token no banco de dados
                        if(!mysqli_stmt_execute($stmt) || !mysqli_stmt_execute($stmtEventOn)){
                           echo'<div class="login">
                                    <input type="text" value="Ocorreu um erro ao resetar a sua senha" disabled/>
                                </div>';
                        } else {
                            if(!mysqli_query($conexao, $sqlCreateEvent)){
                                echo'<div class="login">
                                        <input type="text" value="Ocorreu um erro ao resetar a sua senha" disabled/>
                                    </div>';
                            } else {
                                // Caso nenhum erro ocorra, envia o email para o usuário e fecha as conexões criadas com o banco de dados
                                mail($emailUsuario[0]['email'], "=?utf-8?Q?Recuperação de senha?=", $msgEmail, $headers);
                                mysqli_stmt_close ($stmt);
                                mysqli_stmt_close ($stmtEventOn);
                                echo'<div class="login">
                                        <input type="text" value="Um link para recuperar a senha foi enviado para o seu email" disabled/>
                                    </div>';
                                exit();
                            }
                        }  
                    }
                // Caso nenhum usuário com o email digitado seja encontrado    
                } else {
                    $erro = '2';
                }
            // Caso o email digitado não esteja em um formato válido    
	    } else {
                $erro = '1';
            }
        }
        // Interface requisitando um email para recuperação de conta
        echo'<div class="login">
                    <form class="login-container" method="POST">
	                <p><input type="text" name="email" size="20" placeholder="Digite aqui o seu email cadastrado" required/></p>
                        <p><input name="esqueceuSenha" type="submit" value="Enviar"></p>
	            </form>
                    <textarea id="erro" name="erro" class="erroResetSenha" cols="200" rows="1" disabled hidden></textarea>
                </div>';
        // Verifica se ocorreu algum erro com o email digitado
        if(isset($erro)){
            if($erro === '1'){
                echo'<script>erro("Por favor, digite um email válido");</script>';
                echo'<script>outroMotivo(2);</script>';
            } else if($erro === '2'){
                echo'<script>erro("Nenhum usuário encontrado com esse email");</script>';
                echo'<script>outroMotivo(2);</script>';
            }
        }
         ?>
    </body>
</html>