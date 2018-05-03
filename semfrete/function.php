<?php
    // Organizar e exibir as informações dos anúncios retornados pela query
    function carregarAnuncios($resultado, $z, $pagina){
        unset($_SESSION['anuncio']);
        $resultadoCont = mysqli_num_rows($resultado);
        
        // Verifica se a query retornou algum anúncio
        if ($resultadoCont > 0){
            // Preparação das variáveis de limite de anúncios caso o comando recebido seja de exibir lista de anúncios gerais
            if($z === 'listaAnuncio'){
                //Limite de anúncios por página
                $limite = 30;
                // Calcula quantas páginas de 30 anúncios serão necessárias
                $pagCheia = intdiv($resultadoCont, $limite);
                // Calcula se é necessário uma página extra para exibir os anúncios restantes
                $pagNCheia = $resultadoCont % $limite;
                // Contador para quais anúncios deve exibir dependendo da página selecionada
                $cont = ($limite * $pagina) - $limite;
                if($pagina <= $pagCheia){
                    $limPag = $cont + $limite;
                } else {
                    $limPag = $cont + $pagNCheia;
                }
            // Preparação das variáveis de limite de anúncios caso o comando recebido seja de exibir lista de anúncios do usuário logado
            } else if ($z === 'meuAnuncio'){
                // Limite de anúncios por página
                $limite = 10;
                // Calcula quantas páginas de 10 anúncios serão necessárias
                $pagCheia = intdiv($resultadoCont, $limite);
                // Calcula se é necessário uma página extra para exibir os anúncios restantes
                $pagNCheia = $resultadoCont % $limite;
                // Contador para quais anúncios deve exibir dependendo da página selecionada
                $cont = ($limite * $pagina) - $limite;
                if($pagina <= $pagCheia){
                    $limPag = $cont + $limite;
                } else {
                    $limPag = $cont + $pagNCheia;
                }
            // Caso o comando recebido seja de exibir algum anúncio da lista geral
            } else {
                $cont = 0;
                $limPag = $resultadoCont;
            }
            
            // Preparação para exibição
            $_SESSION['anuncio'] = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
            for($i=$cont; $i < $limPag; $i++){
                $imagemNome = array_slice(scandir($_SESSION['anuncio'][$i]['imagens']), 2);
                $imagemQuantidade = count($imagemNome); 
                // Carrega as imagens do anúncio atual
                for($x=0; $x < $imagemQuantidade; $x++ ){
                    $_SESSION['imagemDestino'][$i][$x] = $_SESSION['anuncio'][$i]['imagens'].'/'.$imagemNome[$x];
                }
                // Insere a imagem "placeholder.png" nas tags que não tiverem imagem
                if($imagemQuantidade < 3){
                    $_SESSION['imagemDestino'][$i][2] = 'placeholder.png';
                    if($imagemQuantidade < 2){
                        $_SESSION['imagemDestino'][$i][1] = 'placeholder.png';
                        if($imagemQuantidade < 1){
                            $_SESSION['imagemDestino'][$i][0] = 'placeholder.png';
                        }
                    }                                 
                }
                
                // Exibir anúncios postados pelo usuário logado
                if($z === 'meuAnuncio'){
                    unset($_SESSION['postarAnuncioImgErro']);
                    setlocale(LC_MONETARY, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
                    $preco =  my_money_format('%n', $_SESSION['anuncio'][$i]['preco']);
                    // Interface para exibir os anúncios do usuário logado
                    echo'<div class="meuAnuncio">
                            <div class="divImgMaior">
                                <img class="imgMaior" src="'.$_SESSION['imagemDestino'][$i][0].'" alt="Imagem principal do anúncio '.($i + 1).' do usuário logado."/>
                            </div>
                            <div class="precoTitulo">
                                <div class="listaEditarAnuncio">
                                    <h2>'.$_SESSION['anuncio'][$i]['titulo'].'</h2>
                                </div>
                                <div class="listaEditarAnuncio">
                                    <h1>'.$preco.'</h1>
                                </div>
                            </div> 
                            <form id="editarAnuncio'.$i.'" method="POST" action="editarAnuncio.php">
                                <p><input name="editarAnuncio" type="submit" value="Editar">
                                <input name="apagarAnuncio" type="submit" value="Apagar"></p>
                            </form>
                        </div>
                        <input name="anuncioControle" type="number" value="'.$i.'" form="editarAnuncio'.$i.'" hidden/><br/>';
                }
                
                // Exibir anúncios postados por todos os usuários
                if ($z === 'listaAnuncio'){
                    setlocale(LC_MONETARY, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
                    $preco =  my_money_format('%n', $_SESSION['anuncio'][$i]['preco']);
                    echo'<div class="divListaAnuncio">
                            <form class="listaAnuncio" id="listaAnuncio'.$i.'" method="GET" action="anuncio.php">
                                <button class="mostraAnuncio" name="mostraAnuncio" type="submit">
                                    <img class="imgListaAnuncio" src="'.$_SESSION['imagemDestino'][$i][0].'" alt="Imagem principal do anúncio '.($i + 1).'. Exibe o anúncio ao clicar."/>
                                </button>
                                <input name="a_idControle" type="text" value="'.$_SESSION['anuncio'][$i]['a_id'].'" hidden/>
                                <input name="tituloControle" type="text" value="'.$_SESSION['anuncio'][$i]['titulo'].'" hidden/>
                            </form>
                            <div class="divSub"><br/>
                                <textarea name="listaPreco" class="listaPreco" cols="19" rows="1" disabled>'.$preco.'</textarea><br/>
                                <textarea name="listaTitulo" class="listaTitulo" cols="30" rows="3" disabled>'.$_SESSION['anuncio'][$i]['titulo'].'</textarea><br/>
                            </div>
                        </div>';
                }
                
                // Ao clicar para exibir algum anúncio da lista geral
                // Preparação das variáveis para exibição
                if ($z === 'mostraAnuncio'){
                    $descricao1 = str_replace("\\r\\n", "<br/>", $_SESSION['anuncio'][0]['descricao']);
                    $descricao = str_replace("\'", "'", $descricao1);
                    setlocale(LC_MONETARY, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');
                    $preco =  my_money_format('%n', $_SESSION['anuncio'][0]['preco']);
                    // Formatação para exibição do número de telefone do dono do anúncio exibido
                    // Caso o telefone tenha 11 dígitos
                    if(strlen($_SESSION['usuario'][0]['telefone']) === 11){
                        $prefixoTel = '(' . $_SESSION['usuario'][0]['telefone'][0] . $_SESSION['usuario'][0]['telefone'][1] . ')';
                        $tel1 = NULL;
                        $tel2 = NULL;
                        for($x=2; $x < 7; $x++){
                            $tel1 = $tel1 . $_SESSION['usuario'][0]['telefone'][$x];
                        }
                        for($x=7; $x < 11; $x++){
                            $tel2 = $tel2 . $_SESSION['usuario'][0]['telefone'][$x];
                        }
                    // Caso o telefone tenha 10 dígitos
                    } else {
                        $prefixoTel = '(' . $_SESSION['usuario'][0]['telefone'][0] . $_SESSION['usuario'][0]['telefone'][1] . ')';
                        $tel1 = NULL;
                        $tel2 = NULL;
                        for($x=2; $x < 6; $x++){
                            $tel1 = $tel1 . $_SESSION['usuario'][0]['telefone'][$x];
                        }
                        for($x=6; $x < 10; $x++){
                            $tel2 = $tel2 . $_SESSION['usuario'][0]['telefone'][$x];
                        }
                    }
                    
                    // Formatação para exibição da data de publicação ou última edição do anúncio exibido
                    $_SESSION['anuncio'][0]['data'][0] = strtoupper($_SESSION['anuncio'][0]['data'][0]);
                    $situacao = NULL;
                    $data = NULL;
                    $hora = NULL;
                    for($x=0; $x < 7; $x++){
                        $situacao = $situacao . $_SESSION['anuncio'][0]['data'][$x];
                    }
                    for($x=7; $x < 15; $x++){
                        $data = $data . $_SESSION['anuncio'][0]['data'][$x];
                    }
                    for($x=15; $x < 19; $x++){
                        $hora = $hora . $_SESSION['anuncio'][0]['data'][$x];
                    }
                    $data = $data[0].$data[1]."/".$data[2].$data[3]."/".$data[4].$data[5].$data[6].$data[7];
                    $hora = $hora[0].$hora[1].":".$hora[2].$hora[3];
                    
                    if(($_SESSION['usuario'][0]['bloco'] || $_SESSION['usuario'][0]['ap_numero']) == NULL){
                        $_SESSION['usuario'][0]['bloco'] = "Não informado";
                    }
                    
                    // Interface para exibir um anúncio da lista geral
                    echo'<div class="topExibirAnuncio">
                            <div class="exibirTitulo"><p class="titulo">'.$_SESSION['anuncio'][0]['titulo'].'</p><p class="data">'.$situacao.': '.$data.' às '.$hora.'</p></div>
                            <div class="exibirPreco">'.$preco.'</div>
                        </div><br/>
                        
                        <div class="midExibirAnuncio">
                            <div style="display: inline-block;">
                                <div class="exibirDivImgMaior">
                                    <img class="exibirImgMaior" id="exibirImgMaior" src="'.$_SESSION['imagemDestino'][0][0].'"/>
                                </div>
                            </div>
                            <div class="contato">'.$_SESSION['usuario'][0]['nome'].' '.$_SESSION['usuario'][0]['sobrenome'].'<br/><br/>
                            '.$prefixoTel.' '.$tel1.'-'.$tel2.'<br/><br/>
                            Endereço: '.$_SESSION['usuario'][0]['bloco'].' '.$_SESSION['usuario'][0]['ap_numero'].'
                            </div>
                        </div>
                        
                        <button id="botao0" class="botaoInvisivel cBorda" type="button" onClick="bordaImgMenor(0)">
                            <div class="exibirDivImgMenor">
                                <img class="exibirImgMenor" id="exibirImg0" src="'.$_SESSION['imagemDestino'][0][0].'"/>
                            </div>
                        </button>
                        <button id="botao1" class="botaoInvisivel sBorda" type="button" onClick="bordaImgMenor(1)">
                            <div class="exibirDivImgMenor">
                                <img class="exibirImgMenor" id="exibirImg1" src="'.$_SESSION['imagemDestino'][0][1].'"/>
                            </div>
                        </button>
                        <button id="botao2" class="botaoInvisivel sBorda" type="button" onClick="bordaImgMenor(2)">
                            <div class="exibirDivImgMenor">
                                <img class="exibirImgMenor" id="exibirImg2" src="'.$_SESSION['imagemDestino'][0][2].'"/>
                            </div>
                        </button>
                        
                        <div class="botExibirAnuncio">
                            <div class="exibirPrecoMenor">Preço: '.$preco.'</div>
                            <div class="exibirPrecoMenor">'.$_SESSION['anuncio'][0]['condicao'].'</div>
                            <div class="exibirDescricao">'.$descricao.'</div>
                            <div class="exibirDescricao categoria">Categoria: '.$_SESSION['anuncio'][0]['categoria'].'</div>
                        </div><br/>';
                    echo '<script>bordaImgMenor(3);</script>';
                }
            }
            
            // Indicador de quantas páginas existem no fim da página atual
            if($z === 'listaAnuncio' || $z === 'meuAnuncio'){
                $url1 = explode("/", $_SERVER['REQUEST_URI']);
                $url = explode("&pagina", $url1[2]);
                if($pagNCheia > 0){
                    $paginaQtd = $pagCheia + 1;
                } else {
                    $paginaQtd = $pagCheia;
                }
                if($paginaQtd > 1){
                    if($url[0] === 'index.php'){
                        $url[0] = 'index.php?';
                    }
                    echo'<div class="divPaginasNum">
                            <ul class="paginasNum">';
                    for($i=1; $i <= $paginaQtd; $i++){
                        echo'<li class="pagina">
                                <a id="pagina'.$i.'" class="paginaLink" href="'.$url[0].'&pagina='.$i.'">'.$i.'</a>
                            </li>';
                    }
                    echo'    </ul>
                        </div>';
                } else {
                    $_SESSION['pagina'] = 0;
                }
            }
        // Se nenhum anúncio for retornado pela query
        } else {
            echo '<div class="exibirTitulo">Nenhum anúncio encontrado.</div>';
        }
    }
    
    
    
    
    
    // Salvar a imagem no servidor
    function imagem ($imagem, $imagemPasta, $prefixoImagem, $acao){
        // Verifica se algum arquivo foi enviado, caso negativo, sai da função
        $imagemErro = $imagem['error'];
        if($imagemErro === 4){
            return;
        }
        // Verifica se ocorreu algum erro na transferência da imagem
        elseif ($imagemErro === 0){
        //Recebe os dados da imagem selecionada pelo usuário
        $imagemNome = $imagem['name'];
	$imagemNomeTemp = $imagem['tmp_name'];
	$imagemTamanho = $imagem['size'];
	$imagemTipo = $imagem['type'];
        // Filtra a extenção da imagem
        $imagemExt = explode('.', $imagemNome);
	$imagemExtFinal = strtolower(end($imagemExt));
        // Extensões permitidas
        $permitido = array('jpg', 'jpeg', 'png');     
                // Verifica se a extensão da imagem é permitida
		if (in_array($imagemExtFinal, $permitido)){
                        // Verifica se o tamanho da imagem é menor que o máximo permitido
			if ($imagemTamanho < 4000000){
                            // Verifica se a pasta das imagens do anúncio foi criada anteriormente
                            if(!is_dir($imagemPasta)){
                                mkdir($imagemPasta);
                            } 
                            // Renomeia, redimensiona e move a imagem transferida para o seu destino final
                            $imagemNovoNome = uniqid($prefixoImagem, true).".".$imagemExtFinal;
                            $imagemDestino = $imagemPasta.'/'.$imagemNovoNome;
                            smart_resize_image($imagemNomeTemp,
                              $string             = null,
                              $width              = 600, 
                              $height             = 450, 
                              $proportional       = true, 
                              $output             = $imagemDestino, 
                              $delete_original    = true, 
                              $use_linux_commands = false,
  			      $quality = 100);
			} else {
                            anuncioImgErro(2, $acao);
			}
		} else {
                    anuncioImgErro(3, $acao);
		}
        } else {
            anuncioImgErro(4, $acao);
	}
    }
    
    
    
    
    
    // Reordenar vetores de imagens
    function reordenarImagem(&$imagemPost) {
        $imagemArray = array();
        $imagemQuantidade = count($imagemPost['name']);
        $imagemChaves = array_keys($imagemPost);
        for ($i=0; $i<$imagemQuantidade; $i++) {
            foreach ($imagemChaves as $chave) {
                $imagemArray[$i][$chave] = $imagemPost[$chave][$i];
            }
        }
      return $imagemArray;
    }
    
    
    
    
    
    // Retorno após verificado que o anúncio postado ou editado está sem imagens
    function anuncioImgErro($erro, $acao){
        // Caso a função seja chamada ao postar um novo anúncio
        if ($acao === 'anunciar'){
            unset($_SESSION['postarAnuncioImgErro']);
            $_SESSION['postarAnuncioImgErro'][0] = $erro;
            $_SESSION['postarAnuncioImgErro'][1] = $_POST['titAnuncio'];
            $_SESSION['postarAnuncioImgErro'][2] = $_POST['descAnuncio'];
            $_SESSION['postarAnuncioImgErro'][3] = $_POST['catAnuncio'];
            $_SESSION['postarAnuncioImgErro'][4] = $_POST['precoAnuncio'];
            $_SESSION['postarAnuncioImgErro'][5] = $_POST['condicaoAnuncio'];
            header("Location: anuncio.php?anunciar");
            exit();
        }
        // Caso a função seja chamada ao editar um anúncio
        if ($acao === 'editar'){
            unset($_SESSION['postarAnuncioImgErro']);
            $_SESSION['postarAnuncioImgErro'][0] = $erro;
            header("Location: editarAnuncio.php?erro");
            exit();
        }
        // Caso a função seja chamada ao apagar um anúncio
        if ($acao === 'apagar'){
            unset($_SESSION['postarAnuncioImgErro']);
            $_SESSION['postarAnuncioImgErro'][0] = $erro;
            $_SESSION['postarAnuncioImgErro'][1] = $_POST['avaliacaoEntrada'];
            header("Location: editarAnuncio.php?erroApagar");
            exit();
        }
    }
    
    
    
    
    
    // Apagar um diretório e todo o seu conteúdo
    function rrmdir($diretorio){
        if (is_dir($diretorio)) { 
            $objetos = scandir($diretorio); 
            foreach ($objetos as $objeto) { 
                if ($objeto != "." && $objeto != "..") { 
                    if (is_dir($diretorio."/".$objeto)){
                        rrmdir($diretorio."/".$objeto);
                    } else {
                        unlink($diretorio."/".$objeto);
                    } 
                } 
            }
            rmdir($diretorio); 
        }
    }
        
        
        
        
        
    // Editar o preço do anúncio e mostrar no formato normal (Ex: R$ 1.999,99)
    function my_money_format($formato, $valor) {

    // Se a funcao money_format esta disponivel: usa-la
    if (function_exists('money_format')) {
        return money_format($formato, $valor);
    }

    // Se nenhuma localidade foi definida, formatar com number_format
    if (setlocale(LC_MONETARY, 0) == 'C') {
        return number_format($valor, 2);
    }

    // Obter dados da localidade
    $locale = localeconv();

    // Extraindo opcoes do formato
    $regex = '/^'.             // Inicio da Expressao
             '%'.              // Caractere %
             '(?:'.            // Inicio das Flags opcionais
             '\=([\w\040])'.   // Flag =f
             '|'.
             '([\^])'.         // Flag ^
             '|'.
             '(\+|\()'.        // Flag + ou (
             '|'.
             '(!)'.            // Flag !
             '|'.
             '(-)'.            // Flag -
             ')*'.             // Fim das flags opcionais
             '(?:([\d]+)?)'.   // W  Largura de campos
             '(?:#([\d]+))?'.  // #n Precisao esquerda
             '(?:\.([\d]+))?'. // .p Precisao direita
             '([in%])'.        // Caractere de conversao
             '$/';             // Fim da Expressao

    if (!preg_match($regex, $formato, $matches)) {
        trigger_error('Formato invalido: '.$formato, E_USER_WARNING);
        return $valor;
    }

    // Recolhendo opcoes do formato
    $opcoes = array(
        'preenchimento'   => ($matches[1] !== '') ? $matches[1] : ' ',
        'nao_agrupar'     => ($matches[2] == '^'),
        'usar_sinal'      => ($matches[3] == '+'),
        'usar_parenteses' => ($matches[3] == '('),
        'ignorar_simbolo' => ($matches[4] == '!'),
        'alinhamento_esq' => ($matches[5] == '-'),
        'largura_campo'   => ($matches[6] !== '') ? (int)$matches[6] : 0,
        'precisao_esq'    => ($matches[7] !== '') ? (int)$matches[7] : false,
        'precisao_dir'    => ($matches[8] !== '') ? (int)$matches[8] : $locale['int_frac_digits'],
        'conversao'       => $matches[9]
    );

    // Sobrescrever $locale
    if ($opcoes['usar_sinal'] && $locale['n_sign_posn'] == 0) {
        $locale['n_sign_posn'] = 1;
    } elseif ($opcoes['usar_parenteses']) {
        $locale['n_sign_posn'] = 0;
    }
    if ($opcoes['precisao_dir']) {
        $locale['frac_digits'] = $opcoes['precisao_dir'];
    }
    if ($opcoes['nao_agrupar']) {
        $locale['mon_thousands_sep'] = '';
    }

    // Processar formatacao
    $tipo_sinal = $valor >= 0 ? 'p' : 'n';
    if ($opcoes['ignorar_simbolo']) {
        $simbolo = '';
    } else {
        $simbolo = $opcoes['conversao'] == 'n' ? $locale['currency_symbol']
                                               : $locale['int_curr_symbol'];
    }
    $numero = number_format(abs($valor), $locale['frac_digits'], $locale['mon_decimal_point'], $locale['mon_thousands_sep']);

/*
//TODO: dar suporte a todas as flags
    list($inteiro, $fracao) = explode($locale['mon_decimal_point'], $numero);
    $tam_inteiro = strlen($inteiro);
    if ($opcoes['precisao_esq'] && $tam_inteiro < $opcoes['precisao_esq']) {
        $alinhamento = $opcoes['alinhamento_esq'] ? STR_PAD_RIGHT : STR_PAD_LEFT;
        $numero = str_pad($inteiro, $opcoes['precisao_esq'] - $tam_inteiro, $opcoes['preenchimento'], $alinhamento).
                  $locale['mon_decimal_point'].
                  $fracao;
    }
*/

    $sinal = $valor >= 0 ? $locale['positive_sign'] : $locale['negative_sign'];
    $simbolo_antes = $locale[$tipo_sinal.'_cs_precedes'];

    // Espaco entre o simbolo e o numero
    $espaco1 = $locale[$tipo_sinal.'_sep_by_space'] == 1 ? ' ' : '';

    // Espaco entre o simbolo e o sinal
    $espaco2 = $locale[$tipo_sinal.'_sep_by_space'] == 2 ? ' ' : '';

    $formatado = '';
    switch ($locale[$tipo_sinal.'_sign_posn']) {
    case 0:
        if ($simbolo_antes) {
            $formatado = '('.$simbolo.$espaco1.$numero.')';
        } else {
            $formatado = '('.$numero.$espaco1.$simbolo.')';
        }
        break;
    case 1:
        if ($simbolo_antes) {
            $formatado = $sinal.$espaco2.$simbolo.$espaco1.$numero;
        } else {
            $formatado = $sinal.$numero.$espaco1.$simbolo;
        }
        break;
    case 2:
        if ($simbolo_antes) {
            $formatado = $simbolo.$espaco1.$numero.$sinal;
        } else {
            $formatado = $numero.$espaco1.$simbolo.$espaco2.$sinal;
        }
        break;
    case 3:
        if ($simbolo_antes) {
            $formatado = $sinal.$espaco2.$simbolo.$espaco1.$numero;
        } else {
            $formatado = $numero.$espaco1.$sinal.$espaco2.$simbolo;
        }
        break;
    case 4:
        if ($simbolo_antes) {
            $formatado = $simbolo.$espaco2.$sinal.$espaco1.$numero;
        } else {
            $formatado = $numero.$espaco1.$simbolo.$espaco2.$sinal;
        }
        break;
    }

    // Se a string nao tem o tamanho minimo
    if ($opcoes['largura_campo'] > 0 && strlen($formatado) < $opcoes['largura_campo']) {
        $alinhamento = $opcoes['alinhamento_esq'] ? STR_PAD_RIGHT : STR_PAD_LEFT;
        $formatado = str_pad($formatado, $opcoes['largura_campo'], $opcoes['preenchimento'], $alinhamento);
    }

    return $formatado;
}




   
// Redimensionamento de imagens
/**
 * easy image resize function
 * @param  $file - file name to resize
 * @param  $string - The image data, as a string
 * @param  $width - new image width
 * @param  $height - new image height
 * @param  $proportional - keep image proportional, default is no
 * @param  $output - name of the new file (include path if needed)
 * @param  $delete_original - if true the original image will be deleted
 * @param  $use_linux_commands - if set to true will use "rm" to delete the image, if false will use PHP unlink
 * @param  $quality - enter 1-100 (100 is best quality) default is 100
 * @return boolean|resource
 * Ex call: smart_resize_image($file,
                          $string             = null,
                          $width              = 0, 
                          $height             = 0, 
                          $proportional       = false, 
                          $output             = 'file', 
                          $delete_original    = true, 
                          $use_linux_commands = false,
  			  $quality = 100);
 */
  function smart_resize_image($file, $string, $width, $height, $proportional, $output, $delete_original, $use_linux_commands, $quality) {
      
    if ( $height <= 0 && $width <= 0 ) return false;
    if ( $file === null && $string === null ) return false;
 
    # Setting defaults and meta
    $info                         = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
    $image                        = '';
    $final_width                  = 0;
    $final_height                 = 0;
    list($width_old, $height_old) = $info;
	$cropHeight = $cropWidth = 0;
 
    # Calculating proportionality
    if ($proportional) {
      if      ($width  == 0)  $factor = $height/$height_old;
      elseif  ($height == 0)  $factor = $width/$width_old;
      else                    $factor = min( $width / $width_old, $height / $height_old );
 
      $final_width  = round( $width_old * $factor );
      $final_height = round( $height_old * $factor );
    }
    else {
      $final_width = ( $width <= 0 ) ? $width_old : $width;
      $final_height = ( $height <= 0 ) ? $height_old : $height;
	  $widthX = $width_old / $width;
	  $heightX = $height_old / $height;
	  
	  $x = min($widthX, $heightX);
	  $cropWidth = ($width_old - $width * $x) / 2;
	  $cropHeight = ($height_old - $height * $x) / 2;
    }
 
    # Loading image to memory according to type
    switch ( $info[2] ) {
      case IMAGETYPE_JPEG:  $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);  break;
      case IMAGETYPE_GIF:   $file !== null ? $image = imagecreatefromgif($file)  : $image = imagecreatefromstring($string);  break;
      case IMAGETYPE_PNG:   $file !== null ? $image = imagecreatefrompng($file)  : $image = imagecreatefromstring($string);  break;
      default: return false;
    }
    
    
    # This is the resizing/resampling/transparency-preserving magic
    $image_resized = imagecreatetruecolor( $final_width, $final_height );
    if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
      $transparency = imagecolortransparent($image);
      $palletsize = imagecolorstotal($image);
 
      if ($transparency >= 0 && $transparency < $palletsize) {
        $transparent_color  = imagecolorsforindex($image, $transparency);
        $transparency       = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
        imagefill($image_resized, 0, 0, $transparency);
        imagecolortransparent($image_resized, $transparency);
      }
      elseif ($info[2] == IMAGETYPE_PNG) {
        imagealphablending($image_resized, false);
        $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
        imagefill($image_resized, 0, 0, $color);
        imagesavealpha($image_resized, true);
      }
    }
    imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);
	
	
    # Taking care of original, if needed
    if ( $delete_original ) {
      if ( $use_linux_commands ) exec('rm '.$file);
      else @unlink($file);
    }
 
    # Preparing a method of providing result
    switch ( strtolower($output) ) {
      case 'browser':
        $mime = image_type_to_mime_type($info[2]);
        header("Content-type: $mime");
        $output = NULL;
      break;
      case 'file':
        $output = $file;
      break;
      case 'return':
        return $image_resized;
      break;
      default:
      break;
    }
    
    # Writing image according to type to the output destination and image quality
    switch ( $info[2] ) {
      case IMAGETYPE_GIF:   imagegif($image_resized, $output);    break;
      case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output, $quality);   break;
      case IMAGETYPE_PNG:
        $quality = 9 - (int)((0.9*$quality)/10.0);
        imagepng($image_resized, $output, $quality);
        break;
      default: return false;
    }
 
    return true;
  }