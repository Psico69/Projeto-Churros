<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <teste></teste>
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
            
            // Botão "Anunciar" no index
            if(isset($_GET['anunciar']) || isset($_SESSION['postarAnuncioImgErro'])){
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
                // Interface para inserir anúncio
                echo'<div class="mainNovoAnuncio">
                        <form class="login-container" method="POST" action="inserirAnuncio.php" enctype="multipart/form-data">
                            <p><input id="titAnuncio" name="titAnuncio" type="text" placeholder="Título" maxlength="90" required/></p>
                            <p><textarea id="descAnuncio" name="descAnuncio" class="descricao" cols="100" rows="15" maxlength="5000" placeholder="Descrição" required></textarea></p>
                            <p><select id="catAnuncio" name="catAnuncio" class="opcoes">
                                <option value="selecione" selected>Selecione uma categoria</option>
                                <option value="Pets e acessórios">Pets e acessórios</option>
                                <option value="Artigos infantis">Artigos infantis</option>
                                <option value="Música e hobbies">Música e hobbies</option>
                                <option value="Moda e beleza">Moda e beleza</option>
                                <option value="Para a sua casa">Para a sua casa</option>
                                <option value="Esportes e lazer">Esportes e lazer</option>
                                <option value="Eletrônicos e celulares">Eletrônicos e celulares</option>
                                <option value="Ferramentas">Ferramentas</option>
                                <option value="Serviços autônomos">Serviços autônomos</option>
                                <option value="Veículos e acessórios">Veículos e acessórios</option>
                            </select>
                            <select id="condicaoAnuncio" name="condicaoAnuncio" class="opcoes">
                                <option value="Novo">Novo</option>
                                <option value="Usado">Usado</option>
                            </select></p>
                            <p><input id="precoAnuncio" name="precoAnuncio" type="text" placeholder="R$:" maxlength="16" data-thousands="." data-decimal="," required/></p>
                            <p><textarea id="erro" name="erro" class="erroAnuncio" cols="200" rows="1" disabled hidden></textarea></p>
                            <p><input name="imgAnuncio[]" class="apagarAnuncio" type="file"/></p>
                            <p><input name="imgAnuncio[]" class="apagarAnuncio" type="file"/></p>
                            <p><input name="imgAnuncio[]" class="apagarAnuncio" type="file"/></p>
                            <p><input name="anunciar" type="submit" value="Anunciar">
                            <input name="cancelar" type="submit" value="Cancelar" form="cancelarNovoAnuncio"></p>
                        </form>
                        <form id="cancelarNovoAnuncio" method="POST" action="inserirAnuncio.php"></form>
                    </div>
                    
                    <footer id="rodape" class="footer">
                        <a class="feedback" href="feedback.php">Críticas ou sugestões? Clique aqui e nos diga.</a>
                    </footer>';
                    
                    // Caso algum erro aconteça ao postar o anúncio
                    if(isset($_SESSION['postarAnuncioImgErro'])){
                        echo'<input id="controleTit" type="text" value="'.$_SESSION['postarAnuncioImgErro'][1].'" hidden/>
                            <textarea id="controleDesc" disabled hidden>'.$_SESSION['postarAnuncioImgErro'][2].'</textarea>
                            <input id="controlePreco" type="text" value="'.$_SESSION['postarAnuncioImgErro'][4].'" hidden/>';
                        echo'<script>outroMotivo(2);</script>
                            <script>mudaValor(1);</script>';
                        
                        // Exibe a mensagem de erro correspondente ao erro
                        switch($_SESSION['postarAnuncioImgErro'][0]){
                            case 1:
                                echo'<script>erro("Envie ao menos 1 imagem para postar um novo anúncio.");</script>';
                                break;
                            case 2:
                                echo'<script>erro("Sua imagem é muito grande! Selecione imagens menores que 4MB.");</script>';
                                break;
                            case 3:
                                echo'<script>erro("Tipos de imagens permitidas: jpg, jpeg e png.");</script>';
                                break;
                            case 4:
                                echo'<script>erro("Ocorreu um erro ao enviar sua imagem!");</script>';
                                break;
                            case 5:
                                echo'<script>erro("Por favor, selecione uma categoria.");</script>';
                                break;
                        }
                        
                        // Recupera a categoria selecionada pelo usuário
                        switch($_SESSION['postarAnuncioImgErro'][3]){
                            case "Pets e acessórios":
                                echo '<script>selecionarCategoria(1);</script>';
                                break;
                            case "Artigos infantis":
                                echo '<script>selecionarCategoria(2);</script>';
                                break;
                            case "Música e hobbies":
                                echo '<script>selecionarCategoria(3);</script>';
                                break;
                            case "Moda e beleza":
                                echo '<script>selecionarCategoria(4);</script>';
                                break;
                            case "Para a sua casa":
                                echo '<script>selecionarCategoria(5);</script>';
                                break;
                            case "Esportes e lazer":
                                echo '<script>selecionarCategoria(6);</script>';
                                break;
                            case "Eletrônicos e celulares":
                                echo '<script>selecionarCategoria(7);</script>';
                                break;
                            case "Ferramentas":
                                echo '<script>selecionarCategoria(8);</script>';
                                break;
                            case "Serviços autônomos":
                                echo '<script>selecionarCategoria(9);</script>';
                                break;
                            case "Veículos e acessórios":
                                echo '<script>selecionarCategoria(10);</script>';
                                break;
                        }
                        
                        // Recupera a condição selecionada pelo usuário
                        switch($_SESSION['postarAnuncioImgErro'][5]){
                            case "Novo":
                                echo '<script>selecionarCondicao(0);</script>';
                                break;
                            case "Usado":
                                echo '<script>selecionarCondicao(1);</script>';
                                break;
                        }
                    }
                    
            // Se não acontecer algum erro ao postar o anúncio
            } else if (isset($_SESSION['anunciar']) && $_SESSION['anunciar'] === "sucesso"){
                unset($_SESSION['anunciar']);
                // Interface para indicar que o anúncio do usuário foi postado com sucesso
                echo'<div class="mainPostado">
                        <div class="postado-container">
                            <p class="postado">Anúncio postado com sucesso!</p>
                            <p class="postado">Você pode editar os seus anúncios a qualquer momento</p>
                            
                            <p class="postadoBotoes">
                                <input name="principal" type="submit" value="Principal" form="principal">
                                <input name="anunciar" type="submit" value="Anunciar" form="anunciar">
                                <input name="meusAnuncios" type="submit" value="Meus Anúncios" form="meusAnuncios">
                            </p>
                            
                            <form id="principal" method="POST" action="index.php"></form>
                            <form id="anunciar" method="GET" action="anuncio.php"></form>
                            <form id="meusAnuncios" method="GET" action="anuncio.php"></form>
                        </div>   
                    </div>';
                
            // Botão "Meus Anúncios" no index ou edição bem sucedida de anuncio
            } else if (isset($_GET['meusAnuncios'])){
                // Verica se foi enviada a página a ser exibida pela URL
                if(isset($_GET['pagina'])){
                    $_SESSION['pagina'] = $_GET['pagina'];
                // Caso nenhuma página seja enviada pela URL, padrão página 1
                } else {
                    $_SESSION['pagina'] = '1';
                }
                // Busca no banco de dados os anúncios do usuário logado
                $sql = "SELECT * FROM anuncio WHERE u_id=? ORDER BY a_id DESC";
                $stmt = mysqli_stmt_init($conexao);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    echo'Ocorreu um erro ao exibir o anúncio';
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $_SESSION['u_id']);
                    mysqli_stmt_execute($stmt);
                    $resultado = mysqli_stmt_get_result($stmt);
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
                            echo'<div class="mainMeuAnuncio">';
                    // Função que exibe os anúncios de acordo com a opção escolhida, nesse caso é a lista de anúncios do usuário logado
                    carregarAnuncios($resultado, 'meuAnuncio', $_SESSION['pagina']);
                    // Script JS para mudar a cor do índice da pagina selecionada, exibido no fim da página atual
                    echo'<script>paginaLinkClick('.$_SESSION['pagina'].');</script>';
                    echo'</div>
                        <footer id="rodape" class="footer">
                            <a class="feedback" href="feedback.php">Críticas ou sugestões? Clique aqui e nos diga.</a>
                        </footer>';
                }
                
            // Interface exibida ao clicar em algum anúncio da lista de anúncios gerais (exibir anúncio)
            } else if (isset($_GET['a_idControle']) AND isset($_GET['tituloControle'])){
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
                echo'<div class="mainExibirAnuncio">';
                // Variáveis para busca dos dados do usuário e do anúncio para exibição
                $a_id = $_GET['a_idControle'];
                $titulo = $_GET['tituloControle'];
                $sql = "SELECT u.nome, u.sobrenome, u.telefone, u.bloco, u.ap_numero FROM usuario u JOIN anuncio a ON u.id = a.u_id AND a.a_id=? AND a.titulo=?";
                $stmt = mysqli_stmt_init($conexao);
                // Caso ocorra algum erro na preparação da busca pelos dados do usuário
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    echo'Ocorreu um erro ao exibir o anúncio';
                } else {
                    // Caso a preparação da busca pelos dados do usuário seja bem-sucedida
                    mysqli_stmt_bind_param($stmt, "ss", $a_id, $titulo);
                    mysqli_stmt_execute($stmt);
                    $resultadoUsuario = mysqli_stmt_get_result($stmt);
                    $_SESSION['usuario'] = mysqli_fetch_all($resultadoUsuario, MYSQLI_ASSOC);
                }
                $sql = "SELECT a_id, titulo, descricao, imagens, categoria, preco, condicao, data FROM anuncio WHERE a_id=? AND titulo=?";
                $stmt = mysqli_stmt_init($conexao);
                // Caso ocorra algum erro na preparação da busca pelos dados do anúncio
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    echo'Ocorreu um erro ao exibir o anúncio';
                } else {
                    // Caso a preparação da busca pelos dados do anúncio seja bem-sucedida
                    mysqli_stmt_bind_param($stmt, "ss", $a_id, $titulo);
                    mysqli_stmt_execute($stmt);
                    $resultado = mysqli_stmt_get_result($stmt);
                    // Função que exibe os anúncios de acordo com a opção escolhida, nesse caso, exibe o anúncio da lista geral selecionado pelo usuário
                    carregarAnuncios($resultado, 'mostraAnuncio', 0);
                    echo'</div>
                        <footer id="rodape" class="footer">
                            <a class="feedback" href="feedback.php">Críticas ou sugestões? Clique aqui e nos diga.</a>
                        </footer>';
                }
            } else {
            header("Location: index.php");
            }
        }    
        ?>
        <script>
            $(document).ready(function(){
                $('#precoAnuncio').mask('000.000.000.000.000,00', {reverse: true});
            });
        </script>
    </body>
</html>