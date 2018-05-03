<?php
    session_start();
    // Preparação de variáveis, estas devem ser feitas antes da exibição de qualquer código HTML por causa dos redirecionamentos "header()"
    if (isset($_GET['menorPreco'], $_SERVER['HTTP_REFERER'])){
        $_SESSION['precoOrdem'] = $_GET['menorPreco'];
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    } else if (isset($_GET['maiorPreco'], $_SERVER['HTTP_REFERER'])){
        $_SESSION['precoOrdem'] = $_GET['maiorPreco'];
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
    }
    if (isset($_GET['busca'])){
        include 'bdConexao.php';
        $busca = mysqli_real_escape_string($conexao, $_GET['busca']);
    } else {
        $busca = "";
    }
    if (isset ($_GET['LF']) || isset($_GET['submitBusca'])){
        if(isset($_SESSION['precoOrdem'])){
            unset($_SESSION['precoOrdem']);
        }
        if(isset($_SESSION['pagina'])){
            unset($_SESSION['pagina']);
        }
        header("Location: index.php?busca=$busca");
        exit;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Condominio</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="loginEstilo.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="funcoes.js"></script>
    </head>
    <body>
        <?php 
        if (isset($_SESSION['u_id'])){
            // Desfaz o índice de erros de imagem da sessão
            unset($_SESSION['postarAnuncioImgErro']);
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
            include 'function.php';
                // Preparação das variáveis envolvidas nas buscas do banco de dados e exibição da página principal
               /*if (isset($_GET['menorPreco'], $_SERVER['HTTP_REFERER'])){
                    $_SESSION['precoOrdem'] = $_GET['menorPreco'];
                    header("Location: {$_SERVER['HTTP_REFERER']}");
                    exit;
                } else if (isset($_GET['maiorPreco'], $_SERVER['HTTP_REFERER'])){
                    $_SESSION['precoOrdem'] = $_GET['maiorPreco'];
                    header("Location: {$_SERVER['HTTP_REFERER']}");
                    exit;
                }*/
                if(isset($_GET['pagina'])){
                    $_SESSION['pagina'] = $_GET['pagina'];
                } else {
                    $_SESSION['pagina'] = '1';
                }
                /*if (isset($_GET['busca'])){
                    $busca = mysqli_real_escape_string($conexao, $_GET['busca']);
                } else {
                    $busca = "";
                }
                if (isset ($_GET['LF']) || isset($_GET['submitBusca'])){
                    if(isset($_SESSION['precoOrdem'])){
                        unset($_SESSION['precoOrdem']);
                    }
                    if(isset($_SESSION['pagina'])){
                        unset($_SESSION['pagina']);
                    }
                    header("Location: index.php?busca=$busca");
                    exit;
                }*/
                if (isset($_GET['categoria'])){
                    $categoria = mysqli_real_escape_string($conexao, $_GET['categoria']);
                } else {
                    $categoria = "%%";
                }
                if (isset($_GET['condicao'])){
                    $condicao = mysqli_real_escape_string($conexao, $_GET['condicao']);
                } else {
                    $condicao = "%%";
                }
                if (isset($_GET['preco'])){
                    $preco = mysqli_real_escape_string($conexao, $_GET['preco']);
                } else {
                    $preco = "%%";
                }
                if (isset($_GET['precoMinimo'])){
                    $precoMin = mysqli_real_escape_string($conexao, $_GET['precoMinimo']);
                } else {
                    $precoMin = "";
                }
                if (isset($_GET['precoMaximo'])){
                    $precoMax = mysqli_real_escape_string($conexao, $_GET['precoMaximo']);
                } else {
                    $precoMax = "";
                }
                if(isset($_SESSION['precoOrdem'])){
                    $precoOrdem = $_SESSION['precoOrdem'];
                } else {
                    $precoOrdem = '%%';
                }
                // Divisão principal da página, contém a lista de anúncios e os filtros laterais
                echo'<div class="main">';
                // Filtros laterais
                echo'<aside id="filtrosLateral">
                        <h2 class="h2Padrao"><a id="0" class="categoriasLateral" href="index.php?busca='.$busca.'&LF">Limpar filtros</a></h2>
                        <h1 class="filtrosLaterais">Categorias</h1>
                        <h2 class="h2Padrao"><a id="1" class="categoriasLateral" href="index.php?busca='.$busca.'&categoria=Pets_e_acessórios" onClick="filtrosIndex(1)">Pets e acessórios</a></h2>
                        <h2 class="h2Padrao"><a id="2" class="categoriasLateral" href="index.php?busca='.$busca.'&categoria=Artigos_infantis" onClick="filtrosIndex(2)">Artigos infantis</a></h2>
                        <h2 class="h2Padrao"><a id="3" class="categoriasLateral" href="index.php?busca='.$busca.'&categoria=Música_e_hobbies" onClick="filtrosIndex(3)">Música e hobbies</a><br/></h2>
                        <h2 class="h2Padrao"><a id="4" class="categoriasLateral" href="index.php?busca='.$busca.'&categoria=Moda_e_beleza" onClick="filtrosIndex(4)">Moda e beleza</a></h2>
                        <h2 class="h2Padrao"><a id="5" class="categoriasLateral" href="index.php?busca='.$busca.'&categoria=Para_a_sua_casa" onClick="filtrosIndex(5)">Para a sua casa</a></h2>
                        <h2 class="h2Padrao"><a id="6" class="categoriasLateral" href="index.php?busca='.$busca.'&categoria=Esportes_e_lazer" onClick="filtrosIndex(6)">Esportes e lazer</a></h2>
                        <h2 class="h2Padrao"><a id="7" class="categoriasLateral" href="index.php?busca='.$busca.'&categoria=Eletrônicos_e_celulares" onClick="filtrosIndex(7)">Eletrônicos e celulares</a></h2>
                        <h2 class="h2Padrao"><a id="8" class="categoriasLateral" href="index.php?busca='.$busca.'&categoria=Ferramentas" onClick="filtrosIndex(8)">Ferramentas</a></h2>
                        <h2 class="h2Padrao"><a id="9" class="categoriasLateral" href="index.php?busca='.$busca.'&categoria=Servicos_autonomos" onClick="filtrosIndex(9)">Serviços autônomos</a></h2>
                        <h2 class="h2Padrao"><a id="10" class="categoriasLateral" href="index.php?busca='.$busca.'&categoria=Veículos_e_acessórios" onClick="filtrosIndex(10)">Veículos e acessórios</a></h2>
                        <h1 class="filtrosLaterais">Condição</h1>
                        <h2 class="h2Padrao"><a id="11" class="categoriasLateral" href="index.php?busca='.$busca.'&condicao=Novo" onClick="filtrosIndex(11)">Novo</a></h2>
                        <h2 class="h2Padrao"><a id="12" class="categoriasLateral" href="index.php?busca='.$busca.'&condicao=Usado" onClick="filtrosIndex(12)">Usado</a></h2>
                        <h1 class="filtrosLaterais">Preço</h1>
                        <form id="formPrecoOrdem" method="GET">
                            <button id="menorPreco" class="botoesPrecoOrdem" name="menorPreco" type="submit" value="crescente">Menor Preço</button><br/>
                            <button id="maiorPreco" class="botoesPrecoOrdem" name="maiorPreco" type="submit" value="decrescente">Maior Preço</button>
                        </form>
                        <h2 class="h2Padrao"><a id="13" class="categoriasLateral" href="index.php?busca='.$busca.'&preco=1000" onClick="filtrosIndex(13)">Até R$1.000</a></h2>
                        <h2 class="h2Padrao"><a id="14" class="categoriasLateral" href="index.php?busca='.$busca.'&preco=2500" onClick="filtrosIndex(14)">Mais de R$2.500</a></h2>
                        <div class="precoRangeEspaco"><input id="precoMinimo" class="precoRange" name="precoRangeMinimo" type="number" value="'.$precoMin.'" placeholder="Mínimo"/> - 
                        <input id="precoMaximo" class="precoRange" name="precoRangeMaximo" type="number" value="'.$precoMax.'" placeholder="Máximo"/>
                        <a id="15" class="categoriasLateral" href="#" onClick="filtrosIndex(15)"> > </a>
                        <textarea id="erro" name="erro" class="erroPrecoRange" cols="25" rows="3" disabled></textarea></div><br/>
                    </aside>';
                // Variáveis de controle de dados
                echo'<input id="filtroBusca" type="text" value="'.$busca.'" hidden/>
                    <input id="filtroCategoria" type="text" value="'.$categoria.'" hidden/>
                    <input id="filtroCondicao" type="text" value="'.$condicao.'" hidden/>
                    <input id="filtroPreco" type="text" value="'.$preco.'" hidden/>
                    <input id="mostrarMinimo" type="text" value="'.$precoMin.'" hidden/>
                    <input id="mostrarMaximo" type="text" value="'.$precoMax.'" hidden/>
                    <input id="precoOrdem" type="text" value="'.$precoOrdem.'" hidden/>';
                echo'<script>filtrosDestaque();</script>';
                // Divisão contendo a lista de anúncios
                echo'<div id="listaPrincipalAnuncio">';
                $parametroBusca = "%$busca%";
                // Verifica se existe um range de preço selecionado pelo usuário
                if (isset($_GET['precoMinimo'], $_GET['precoMaximo'])){
                    // Exibe uma mensagem instruindo o usuário a inserir um valor mínimo e máximo caso ele tenha deixado um desses valores em branco ao utilizar o filtro de range de preço
                    if($_GET['precoMinimo'] === '' OR $_GET['precoMaximo'] === ''){
                        echo'<script>erro("Coloque um valor mínimo e máximo para realizar a busca.");</script>';
                    } else {
                        // Verifica se o usuário selecionou o filtro "Menor Preço" após o filtro de range de preço
                        if (isset($_SESSION['precoOrdem']) && $_SESSION['precoOrdem'] === 'crescente'){
                            $sql = "SELECT a_id, titulo, imagens, preco FROM anuncio WHERE (titulo LIKE ? OR descricao LIKE ?) AND categoria LIKE ? AND condicao LIKE ? AND preco BETWEEN ? AND ? ORDER BY preco";
                        // Verifica se o usuário selecionou o filtro "Maior Preço" após o filtro de range de preço
                        } else if (isset($_SESSION['precoOrdem']) && $_SESSION['precoOrdem'] === 'decrescente'){
                            $sql = "SELECT a_id, titulo, imagens, preco FROM anuncio WHERE (titulo LIKE ? OR descricao LIKE ?) AND categoria LIKE ? AND condicao LIKE ? AND preco BETWEEN ? AND ? ORDER BY preco DESC";
                        // Caso o usuário tenha selecionado o filtro de range de preço sem ordernar por "Maior Preço" ou "Menor Preço"
                        } else {
                            $sql = "SELECT a_id, titulo, imagens, preco FROM anuncio WHERE (titulo LIKE ? OR descricao LIKE ?) AND categoria LIKE ? AND condicao LIKE ? AND preco BETWEEN ? AND ? ORDER BY a_id DESC";
                        }
                        // Prepara a query ao banco de dados
                        $stmt = mysqli_stmt_init($conexao);
                        // Verifica se ocorreu algum erro ao preparar a query
                        if(!mysqli_stmt_prepare($stmt, $sql)){
                            echo'Ocorreu um erro ao realizar a busca!';
                        } else {
                            mysqli_stmt_bind_param($stmt, "ssssss", $parametroBusca, $parametroBusca, $categoria, $condicao, $precoMin, $precoMax);
                            // Verifica se ocorreu algum erro ao buscar as informações dos anúncios no banco de dados
                            if(!mysqli_stmt_execute($stmt)){
                                echo'Ocorreu um erro ao realizar a busca!';
                            } else {
                                $resultado = mysqli_stmt_get_result($stmt);
                                // Função que exibe os anúncios de acordo com a opção escolhida, nesse caso é a lista de anúncios gerais
                                carregarAnuncios($resultado, 'listaAnuncio', $_SESSION['pagina']);
                            }  
                        }
                    }
                // Verifica se o filtro de preços "Até R$1.000" ou "Mais de R$2.500" foi selecionado
                } else if (isset($_GET['preco'])){
                    // Verifica se o filtro "Até R$1.000" foi selecionado
                    if ($_GET['preco'] === '1000'){
                        $x = 1000;
                        // Verifica se o filtro de preços "Até R$1.000" e "Menor Preço" foram selecionados
                        if (isset($_SESSION['precoOrdem']) && $_SESSION['precoOrdem'] === 'crescente'){
                            $sql = "SELECT a_id, titulo, imagens, preco FROM anuncio WHERE (titulo LIKE ? OR descricao LIKE ?) AND categoria LIKE ? AND condicao LIKE ? AND preco <= ? ORDER BY preco";
                        // Verifica se o filtro de preços "Até R$1.000" e "Maior Preço" foram selecionados
                        } else if (isset($_SESSION['precoOrdem']) && $_SESSION['precoOrdem'] === 'decrescente'){
                            $sql = "SELECT a_id, titulo, imagens, preco FROM anuncio WHERE (titulo LIKE ? OR descricao LIKE ?) AND categoria LIKE ? AND condicao LIKE ? AND preco <= ? ORDER BY preco DESC";
                        } else {
                            $sql = "SELECT a_id, titulo, imagens, preco FROM anuncio WHERE (titulo LIKE ? OR descricao LIKE ?) AND categoria LIKE ? AND condicao LIKE ? AND preco <= ? ORDER BY a_id DESC";
                        }
                    // Verifica se o filtro "Mais de R$2.500" foi selecionado
                    } else if ($_GET['preco'] === '2500' ){
                        $x = 2500;
                        // Verifica se o filtro de preços "Mais de R$2.500" e "Menor Preço" foram selecionados
                        if (isset($_SESSION['precoOrdem']) && $_SESSION['precoOrdem'] === 'crescente'){
                            $sql = "SELECT a_id, titulo, imagens, preco FROM anuncio WHERE (titulo LIKE ? OR descricao LIKE ?) AND categoria LIKE ? AND condicao LIKE ? AND preco > ? ORDER BY preco";
                        // Verifica se o filtro de preços "Mais de R$2.500" e "Maior Preço" foram selecionados
                        } else if (isset($_SESSION['precoOrdem']) && $_SESSION['precoOrdem'] === 'decrescente'){
                            $sql = "SELECT a_id, titulo, imagens, preco FROM anuncio WHERE (titulo LIKE ? OR descricao LIKE ?) AND categoria LIKE ? AND condicao LIKE ? AND preco > ? ORDER BY preco DESC";
                        // Caso nenhum filtro de preço seja selecionado, exibe do anúncio mais recente para o mais antigo
                        } else {
                            $sql = "SELECT a_id, titulo, imagens, preco FROM anuncio WHERE (titulo LIKE ? OR descricao LIKE ?) AND categoria LIKE ? AND condicao LIKE ? AND preco > ? ORDER BY a_id DESC";
                        }
                    // Caso o filtro de preço seja selecionado porém esteja com um valor fora do previsto, retorna para a página principal
                    } else {
                        header("Location: index.php");
                    }
                    $stmt = mysqli_stmt_init($conexao);
                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        echo'Ocorreu um erro ao realizar a busca!';
                    } else {
                        mysqli_stmt_bind_param($stmt, "sssss", $parametroBusca, $parametroBusca, $categoria, $condicao, $x);
                        // Verifica se ocorreu algum erro ao buscar as informações dos anúncios no banco de dados
                        if(!mysqli_stmt_execute($stmt)){
                            echo'Ocorreu um erro ao realizar a busca!';
                        } else {
                            $resultado = mysqli_stmt_get_result($stmt);
                            carregarAnuncios($resultado, 'listaAnuncio', $_SESSION['pagina']);
                        }  
                    }
                } else {
                    // Verifica se o filtro "Menor Preço" foi selecionado
                    if (isset($_SESSION['precoOrdem']) && $_SESSION['precoOrdem'] === 'crescente'){
                        $sql = "SELECT a_id, titulo, imagens, preco FROM anuncio WHERE (titulo LIKE ? OR descricao LIKE ?) AND categoria LIKE ? AND condicao LIKE ? ORDER BY preco";
                        // Verifica se o filtro "Maior Preço" foi selecionado
                        } else if (isset($_SESSION['precoOrdem']) && $_SESSION['precoOrdem'] === 'decrescente'){
                            $sql = "SELECT a_id, titulo, imagens, preco FROM anuncio WHERE (titulo LIKE ? OR descricao LIKE ?) AND categoria LIKE ? AND condicao LIKE ? ORDER BY preco DESC";
                        // Caso nenhum filtro seja selecionado e nenhuma busca seja realizada, exibe os anúncios na ordem de postagem limitado para 150 resultados
                        } else if (($busca === '%%' || $busca === '') && $categoria === '%%' && $condicao === '%%'){
                            $sql = "SELECT a_id, titulo, imagens, preco FROM anuncio WHERE (titulo LIKE ? OR descricao LIKE ?) AND categoria LIKE ? AND condicao LIKE ? ORDER BY a_id DESC LIMIT 0, 150";
                        // Realiza uma query baseada na busca feita pelo usuário
                        } else {
                            $sql = "SELECT a_id, titulo, imagens, preco FROM anuncio WHERE (titulo LIKE ? OR descricao LIKE ?) AND categoria LIKE ? AND condicao LIKE ? ORDER BY a_id DESC";
                        }
                    $stmt = mysqli_stmt_init($conexao);
                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        echo'Ocorreu um erro ao realizar a busca!';
                    } else {
                        mysqli_stmt_bind_param($stmt, "ssss", $parametroBusca, $parametroBusca, $categoria, $condicao);
                        // Verifica se ocorreu algum erro ao buscar as informações dos anúncios no banco de dados
                        if(!mysqli_stmt_execute($stmt)){
                            echo'Ocorreu um erro ao realizar a busca!';
                        } else {
                            $resultado = mysqli_stmt_get_result($stmt);
                            carregarAnuncios($resultado, 'listaAnuncio', $_SESSION['pagina']);
                        }  
                    }
                }
                echo'</div>';
                echo'</div>';
                if ($_SESSION['pagina'] !== 0){
                    echo'<script>paginaLinkClick('.$_SESSION['pagina'].');</script>'; 
                }
        echo'<footer id="rodape" class="footer">
                <a class="feedback" href="feedback.php">Críticas ou sugestões? Clique aqui e nos diga.</a>
            </footer>';
        } else {
            // Interface antes de logar
            echo'<div class="login">
                     <div class="login-triangle"></div>
                     <h2 class="login-header">Nome do Condomínio</h2>
                     <form class="login-container" method="POST" action="login.php">
                         <p><input name="usuario" type="text" placeholder="Nome de usuário ou Email" required></p>
                         <p><input name="senha" type="password" placeholder="Senha" required></p>
                         <p><input name="entrar" type="submit" value="Entrar"></p>
                         <a class="esqueceuSenha" id="esqueceuSenha" href="esqueceuSenha.php">Esqueceu a senha?</a>
                     </form>
                     <textarea id="erro" name="erro" class="erroLogin" cols="200" rows="2" disabled hidden></textarea>
                </div>';
            if(isset($_GET['erro'])){
                echo'<script>outroMotivo(2);</script><br/>
                    <script>erro("Ocorreu um erro ao realizar o login!\nVerifique seu nome de usuário ou email e a senha.");</script><br/>';
            }
            if(isset($_GET['vazio'])){
                echo'<script>outroMotivo(2);</script><br/>
                    <script>erro("Insira o seu nome de usuário ou email e a senha nos campos acima para realizar o login.");</script><br/>';
            }
        }
        ?>
    </body>
</html>