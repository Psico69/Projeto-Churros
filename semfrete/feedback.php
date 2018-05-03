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
        if(isset($_SESSION['u_id'])){
            include 'function.php';
            include 'bdConexao.php';
            // Caso as críticas ou sugestões sejam enviadas com sucesso, exibe uma mensagem de agradecimento
            if (isset($_SESSION['critica']) && $_SESSION['critica'] == "sucesso"){
                unset ($_SESSION['critica']);
                echo'<div class="mainPostado">
                        <div class="postado-container">
                            <p class="postado">Mensagem enviada com sucesso.</p>
                            <p class="postado">Sua opinião é muito importante para nós, muito obrigado!</p>
                            
                            <p class="pCentralizado">
                                <input name="principal" type="submit" value="Principal" form="principal">
                            </p>
                            
                            <form id="principal" method="POST" action="index.php"></form>
                        </div>   
                    </div>';
            // Interface para envio de críticas ou sugestões    
            } else {
                echo'<div class="mainNovoAnuncio">
                    <form class="login-container" method="POST" action="inserirAnuncio.php" enctype="multipart/form-data">
                        <p><textarea name="feedback" class="descricao" cols="100" rows="15" maxlength="5000" placeholder="Digite aqui as suas críticas e sugestões." required></textarea></p>
                        <p class="pCentralizado"><input name="enviarFeedback" type="submit" value="Enviar">
                    </form>
                </div>';    
            }
            
        }
        ?>
    </body>
</html>