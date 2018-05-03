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
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="jquery.mask.js"></script>
        <script src="funcoes.js"></script>
    </head>
    <body>
<?php
if (isset($_SESSION['u_id'])){
    include 'function.php';
    // Botão "Editar" na interface para exibição dos anúncios do usuário logado ou redirecionamento do arquivo "inserirAnuncio.php" caso o anúncio ficar sem imagens ao finalizar a edição
    if(isset($_POST['editarAnuncio']) || isset($_GET['erro'])){
        if(!isset($_GET['erro'])){
            // Contador para encontrar os dados do anúncio selecionado para edição
            $_SESSION['anuncioControle'] = $_POST['anuncioControle'];
        }
        // Formatação do preço e descrição para exibir de forma correta
        setlocale(LC_MONETARY, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
        $precoTemp =  my_money_format('%n', $_SESSION['anuncio'][$_SESSION['anuncioControle']]['preco']);
        $preco = str_replace("R$ ","",$precoTemp);
        $descricao1 = str_replace("\\r\\n", "\n", $_SESSION['anuncio'][$_SESSION['anuncioControle']]['descricao']);
        $descricao2 = str_replace("\'", "'", $descricao1);
        $descricao = str_replace("\\\\", "/", $descricao2);
        // Interface para edição do anúncio
        echo'<div class="mainEditarAnuncio">
                <form id="editarAnuncio" class="editar-container" method="POST" action="inserirAnuncio.php" enctype="multipart/form-data">
                    <p><input name="titEditarAnuncio" type="text" value="'.$_SESSION['anuncio'][$_SESSION['anuncioControle']]['titulo'].'" maxlength="90" required/></p>
                    <p><textarea name="descEditarAnuncio" class="descricao" cols="100" rows="15" maxlength="5000" required>'.$descricao.'</textarea></p>
                    <p><select id="catEditarAnuncio" name="catEditarAnuncio" class="editarOpcoes">
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
                    
                    <select id="condicaoAnuncio" name="condicaoAnuncio" class="editarOpcoes">
                        <option value="Novo">Novo</option>
                        <option value="Usado">Usado</option>
                    </select></p>
                    
                    <p><input id="precoEditarAnuncio" name="precoEditarAnuncio" placeholder="R$:" type="text" value="'.$preco.'" maxlength="16" data-thousands="." data-decimal="," required/></p>
                    <textarea id="erro" name="erro" class="erroAnuncio" cols="200" rows="1" disabled hidden></textarea><br/>
            
                    <div class="imgEditarAnuncio paddingL">
                        <div class="divImgMaior">
                            <img id="img0Exibir" class="imgMaior" src="'.$_SESSION['imagemDestino'][$_SESSION['anuncioControle']][0].'" alt="Primeira imagem do anúncio selecionado."/>
                        </div>
                        <p class="sPadding"><label for="img0EditarAnuncio">Escolher imagem</label>
                        <button id="img0Apagar" name="img0Apagar" type="button" onClick="apagaImagem(0)">Apagar</button></p>
                    </div>
                    <div class="imgEditarAnuncio">
                        <div class="divImgMaior">
                            <img id="img1Exibir" class="imgMaior" src="'.$_SESSION['imagemDestino'][$_SESSION['anuncioControle']][1].'" alt="Segunda imagem do anúncio selecionado."/>
                        </div>
                        <p class="sPadding"><label for="img1EditarAnuncio">Escolher imagem</label>
                        <button id="img1Apagar" name="img1Apagar" type="button" onClick="apagaImagem(1)">Apagar</button></p>
                    </div>               
                    <div class="imgEditarAnuncio">
                        <div class="divImgMaior">
                            <img id="img2Exibir" class="imgMaior" src="'.$_SESSION['imagemDestino'][$_SESSION['anuncioControle']][2].'" alt="Terceira imagem do anúncio selecionado."/>
                        </div>
                        <p class="sPadding"><label for="img2EditarAnuncio">Escolher imagem</label>
                        <button id="img2Apagar" name="img2Apagar" type="button" onClick="apagaImagem(2)">Apagar</button></p>
                    </div>
                    
                    <p><input name="alteracoes" type="submit" value="Salvar Alterações">
                    <input name="cancelarAlteracoes" type="submit" value="Cancelar" form="cancelarAlteracoes"></p>
                </form>
                <form id="cancelarAlteracoes" method="POST" action="inserirAnuncio.php"></form>
            </div>
            <input id="img0EditarAnuncio" name="imgEditarAnuncio[]" type="file" form="editarAnuncio" hidden/>
            <input id="img1EditarAnuncio" name="imgEditarAnuncio[]" type="file" form="editarAnuncio" hidden/>
            <input id="img2EditarAnuncio" name="imgEditarAnuncio[]" type="file" form="editarAnuncio" hidden/>
            <input name="a_idEditarAnuncio" type="text" value="'.$_SESSION['anuncio'][$_SESSION['anuncioControle']]['a_id'].'" form="editarAnuncio" hidden/>
            <input id="pastaControle" name="pastaControle" type="text" value="'.$_SESSION['anuncio'][$_SESSION['anuncioControle']]['imagens'].'" form="editarAnuncio" hidden/>
            <input id="img0ControleApagar" name="img0ControleApagar" type="text" value="0" form="editarAnuncio" hidden/>
            <input id="img1ControleApagar" name="img1ControleApagar" type="text" value="0" form="editarAnuncio" hidden/>
            <input id="img2ControleApagar" name="img2ControleApagar" type="text" value="0" form="editarAnuncio" hidden/>
            <input id="img0Controle" name="imgControle[]" type="text" value="'.$_SESSION['imagemDestino'][$_SESSION['anuncioControle']][0].'" form="editarAnuncio" hidden/>
            <input id="img1Controle" name="imgControle[]" type="text" value="'.$_SESSION['imagemDestino'][$_SESSION['anuncioControle']][1].'" form="editarAnuncio" hidden/>
            <input id="img2Controle" name="imgControle[]" type="text" value="'.$_SESSION['imagemDestino'][$_SESSION['anuncioControle']][2].'" form="editarAnuncio" hidden/>';
            
        // Caso tenha acontecido algum erro ao enviar alguma imagem para o servidor
        if(isset($_SESSION['postarAnuncioImgErro'])){
            echo'<script>outroMotivo(2);</script>';
            switch($_SESSION['postarAnuncioImgErro'][0]){
                case 1:
                    echo'<script>erro("É obrigatório manter ao menos 1 imagem para salvar alterações no anúncio.");</script>';
                    break;
                case 2:
                    echo'<script>erro("Sua imagem é muito grande! Selecione imagens menores que 5MB.");</script>';
                    break;
                case 3:
                    echo'<script>erro("Tipos de imagens permitidas: jpg, jpeg e png.");</script>';
                    break;
                case 4:
                    echo'<script>erro("Ocorreu um erro ao enviar sua imagem!");</script>';
                    break;
            }
        }
                        
        switch($_SESSION['anuncio'][$_SESSION['anuncioControle']]['categoria']){
            case "Pets e acessórios":
                echo '<script>selecionarCategoriaEditar(0);</script>';
                break;
            case "Artigos infantis":
                echo '<script>selecionarCategoriaEditar(1);</script>';
                break;
            case "Música e hobbies":
                echo '<script>selecionarCategoriaEditar(2);</script>';
                break;
            case "Moda e beleza":
                echo '<script>selecionarCategoriaEditar(3);</script>';
                break;
            case "Para a sua casa":
                echo '<script>selecionarCategoriaEditar(4);</script>';
                break;
            case "Esportes e lazer":
                echo '<script>selecionarCategoriaEditar(5);</script>';
                break;
            case "Eletrônicos e celulares":
                echo '<script>selecionarCategoriaEditar(6);</script>';
                break;
            case "Ferramentas":
                echo '<script>selecionarCategoriaEditar(7);</script>';
                break;
            case "Serviços autônomos":
                echo '<script>selecionarCategoriaEditar(8);</script>';
                break;
            case "Veículos e acessórios":
                echo '<script>selecionarCategoriaEditar(9);</script>';
                break;
        }
            
        switch($_SESSION['anuncio'][$_SESSION['anuncioControle']]['condicao']){
            case "Novo":
                echo '<script>selecionarCondicao(0);</script>';
                break;
            case "Usado":
                echo '<script>selecionarCondicao(1);</script>';
                break;
        }
    // Botão "Apagar" na lista de exibição dos anúncios do usuário logado
    } else if (isset($_POST['apagarAnuncio']) || isset($_GET['erroApagar'])){
        if(isset($_POST['apagarAnuncio'])){
            $_SESSION['apagarAnuncio'] = $_POST['apagarAnuncio'];
        }
        if(isset($_POST['anuncioControle'])){
            $_SESSION['i'] = $_POST['anuncioControle'];
        }
        if(isset($_SESSION['postarAnuncioImgErro'][1])){
            $valor = $_SESSION['postarAnuncioImgErro'][1];
        } else {
            $valor = 6;
        }
        // Interface do formulário de opinião antes de apagar um anúncio
        echo'<div class="mainApagarAnuncio">
                <form id="formularioApagarAnuncio" class="editar-container" method="POST" action="inserirAnuncio.php">
                    <p><textarea class="descricao" cols="68" rows="2" disabled>Anuncio: '.$_SESSION['anuncio'][$_SESSION['i']]['titulo'].'</textarea></p>
                    <div class="checkboxApagar">
                        <div class="bordaInterna">
                            <textarea class="descricao sBorda" cols="68" rows="1" disabled>Qual o motivo para apagar esse anúncio?</textarea>
                            <p class="sPadding"><input type="radio" name="motivoApagar" value="Vendi pelo site" onClick="outroMotivo(0)"/>Vendi pelo site</p>
                            <p class="sPadding"><input type="radio" name="motivoApagar" value="Vendi por outro meio" onClick="outroMotivo(0)"/>Vendi por outro meio</p>
                            <p class="sPadding"><input type="radio" name="motivoApagar" value="Desisti de vender" onClick="outroMotivo(0)"/>Desisti de vender</p>
                            <p class="sPadding"><input type="radio" name="motivoApagar" value="Outro motivo" onClick="outroMotivo(1)"/>Outro motivo</p>
                        </div>
                    </div>
                    <p id="paddingComentario" class="sPadding">
                        <textarea class="comentario" id="comentario" name="comentario" cols="68" rows="4" maxlength="500" placeholder="Digite aqui o motivo, sua opinião é muito importante." hidden></textarea>
                    </p>
                    <p id="paddingErro" class="sPadding">
                        <textarea id="erro" name="erro" class="erroAnuncio" cols="100" rows="1" disabled hidden></textarea>
                    </p>
                    
                    <div class="checkboxApagar">
                        <div class="bordaInterna">
                            <textarea class="descricao sBorda" cols="68" rows="1" disabled>Qual o seu nível de satisfação com o site?</textarea> 
                            <p>Péssimo <input type="range" id="avaliacaoEntrada" name="avaliacaoEntrada" value="'.$valor.'" min="1" max="10" onInput="avaliacaoSaida.value = avaliacaoEntrada.value"/> Ótimo
                            <output name="avaliacaoSaida" id="avaliacaoSaida">'.$valor.'</output></p>
                        </div>
                    </div>
                    <p><input name="enviar" type="submit" value="Enviar">
                    <input name="cancelarApagar" type="submit" value="Cancelar" form="cancelarApagar"></p>
                    <input type="number" name="anuncioControle" value="'.$_SESSION['i'].'" hidden/>
                </form>
                <form id="cancelarApagar" method="POST" action="inserirAnuncio.php"></form>
            </div>';
        if(isset($_SESSION['postarAnuncioImgErro'])){
            echo'<script>outroMotivo(2);</script>';
            switch($_SESSION['postarAnuncioImgErro'][0]){
                case 1:
                    echo'<script>erro("Por favor selecione o motivo para apagar o anúncio.");</script>';
                    break;
            }
        }
    // Se o anúncio foi apagado
    } else if(isset($_SESSION['apagarAnuncio']) && $_SESSION['apagarAnuncio'] === "sucesso"){
        unset($_SESSION['postarAnuncioImgErro']);
        unset($_SESSION['apagarAnuncio']);
        unset($_SESSION['i']);
        // Interface de agradecimento após apagar um anúncio
        echo'<div class="mainApagarAnuncio">
                <div class="checkboxApagar agradecimento">
                    <div class="descricao agradecimento">
                        <h1 class="muitoObrigado">Muito obrigado por utilizar os nossos serviços!</h1><br/>
                        <h2 class="h2Padrao">E agora, o que deseja fazer?</h2><br/>
                        Buscar por produtos e serviços em diversas categorias?<br/>
                        Postar um novo anúncio ou editar os seus anúncios atuais?<br/>
                        Basta apenas escolher uma opção abaixo.   
                    </div>
                    
                    <p class="postadoBotoes"><input class="botoesAgradecimento" name="principal" type="submit" value="Principal" form="principal">
                    <input class="botoesAgradecimento" name="anunciar" type="submit" value="Anunciar" form="anunciar">
                    <input class="botoesAgradecimento" name="meusAnuncios" type="submit" value="Meus Anúncios" form="meusAnuncios"></p>
                            
                    <form id="principal" method="POST" action="index.php"></form>
                    <form id="anunciar" method="GET" action="anuncio.php"></form>
                    <form id="meusAnuncios" method="GET" action="anuncio.php"></form>
                </div>
            </div>';
    } else {
        header("Location:index.php"); 
    }
} else {
    header("Location:index.php");
}
?>
        <script>
            $(document).ready(function(){
                $('#precoEditarAnuncio').mask('000.000.000.000.000,00', {reverse: true});
            });
        </script>
    </body>
</html>