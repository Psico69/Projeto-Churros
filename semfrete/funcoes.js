// Comando para apagar imagens ao editar um anúncio
function apagaImagem(i){
    var img0 = document.getElementById("img0Exibir").src;
    var img1 = document.getElementById("img1Exibir").src;
    var img2 = document.getElementById("img2Exibir").src;
    var imgx0 = img0.split("/");
    var imgx1 = img1.split("/");
    var imgx2 = img2.split("/");
    switch (i){
        case 0: {
            if (imgx0[4] === 'placeholder.png'){
                document.getElementById("img0ControleApagar").value = '0';
            } else {
                document.getElementById("img0Exibir").src = 'placeholder.png';
                document.getElementById("img0ControleApagar").value = '1';
            }
            break;
        }
        case 1: {
            if (imgx1[4] === 'placeholder.png'){
                document.getElementById("img1ControleApagar").value = '0';
            } else {
                document.getElementById("img1Exibir").src = 'placeholder.png';
                document.getElementById("img1ControleApagar").value = '1';
            }
            break;
        }
        case 2: {
            if (imgx2[4] === 'placeholder.png'){
                document.getElementById("img2ControleApagar").value = '0';
            } else {
                document.getElementById("img2Exibir").src = 'placeholder.png';
                document.getElementById("img2ControleApagar").value = '1';
            }
            break;
        }
    }    
}

// Caso ocorra algum erro de envio de imagem ao postar um novo anúncio, recupera a categoria escolhida anteriormente pelo usuário. Chamada em "anuncio.php"
function selecionarCategoria(index){ 
    document.getElementById("catAnuncio").options.selectedIndex = index;
}

// Caso ocorra algum erro de envio de imagem ao editar um anúncio, recupera a categoria escolhida anteriormente pelo usuário. Chamada em "editarAnuncio.php"
function selecionarCategoriaEditar(index){
    document.getElementById("catEditarAnuncio").options.selectedIndex = index;
}

// Exibe uma mensagem de erro ao enviar imagens para postar um novo anúncio ou aplicar filtro de preço em uma busca por anúncios. Chamada em "anuncio.php" e "index.php"
function erro(msg){
    document.getElementById("erro").value = msg;
}

// Caso ocorra algum erro de envio de imagem ao editar ou postar um anúncio, recupera a condição escolhida anteriormente pelo usuário.
// Chamada em "anuncio.php" e "editarAnuncio.php"
function selecionarCondicao(index){ 
    document.getElementById("condicaoAnuncio").options.selectedIndex = index;
}

// Na interface de visualiar um anúncio, ao clicar nas imagens menores, desenha uma borda ao redor da selecionada e a transfere para a imagem maior.
// Coloca ao atributo "hidden" como true nos espaços onde não existirem imagens.
// Chamada em "function.php"
function bordaImgMenor(i){
    var img0 = document.getElementById("exibirImg0").src;
    var img1 = document.getElementById("exibirImg1").src;
    var img2 = document.getElementById("exibirImg2").src;
    var imgx1 = img1.split("/");
    var imgx2 = img2.split("/");
    
    if(imgx1[4] === 'placeholder.png'){
        document.getElementById("botao1").className = "botaoHidden";
        document.getElementById("botao2").className = "botaoHidden";
        return;
    } else if (imgx2[4] === 'placeholder.png'){
        document.getElementById("botao2").className = "botaoHidden";
        switch (i){
            case 0:{
                document.getElementById("exibirImgMaior").src = img0;
                document.getElementById("botao0").className = "botaoInvisivel cBorda";
                document.getElementById("botao1").className = "botaoInvisivel sBorda";    
                break;
            }
            case 1:{
                document.getElementById("exibirImgMaior").src = img1;
                document.getElementById("botao0").className = "botaoInvisivel sBorda";
                document.getElementById("botao1").className = "botaoInvisivel cBorda";    
                break;    
            }
        }
    } else {
        switch (i){
            case 0:{
                document.getElementById("exibirImgMaior").src = img0;
                document.getElementById("botao0").className = "botaoInvisivel cBorda";
                document.getElementById("botao1").className = "botaoInvisivel sBorda";
                document.getElementById("botao2").className = "botaoInvisivel sBorda";    
                break;
            }
            case 1:{
                document.getElementById("exibirImgMaior").src = img1;
                document.getElementById("botao0").className = "botaoInvisivel sBorda";
                document.getElementById("botao1").className = "botaoInvisivel cBorda";
                document.getElementById("botao2").className = "botaoInvisivel sBorda";     
                break;    
            }
            case 2:{
                document.getElementById("exibirImgMaior").src = img2;
                document.getElementById("botao0").className = "botaoInvisivel sBorda";
                document.getElementById("botao1").className = "botaoInvisivel sBorda";
                document.getElementById("botao2").className = "botaoInvisivel cBorda";     
                break;    
            }
        }
    }
}

// No formulário de feedback, exibe uma textarea para o usuário inserir comentários quando a opção "Outro motivo" for selecionada. 
// Na interface para exibição de anúncio ou cadastro, exibe uma textarea informando ao usuário algum erro que tenha acontecido.
// Chamada em "editarAnuncio.php" e "cadastro.php"
function outroMotivo(i){
    document.getElementById("erro").hidden = true;
    if(i === 1) {
        document.getElementById("paddingComentario").className = false;
        document.getElementById("paddingErro").className = 'sPadding';
        document.getElementById("comentario").hidden = false;
    } else if (i === 0) {
        document.getElementById("paddingComentario").className = 'sPadding';
        document.getElementById("paddingErro").className = 'sPadding';
        document.getElementById("comentario").hidden = true;
      //Erro ao enviar imagem na interface de edição de anúncios
    } else if (i === 2){
        document.getElementById("erro").hidden = false;
    }
}

function senhaAlterar(i){
    if (i === 0){
        document.getElementById("novaSenha").style.display = "block";
        document.getElementById("novaSenhaConfirmar").style.display = "block";
        document.getElementById("novaSenha").className = "mainEditarCadastroInput";
        document.getElementById("novaSenhaConfirmar").className = "mainEditarCadastroInput";
 
    } else if (i === 1){
        document.getElementById("novaSenha").style.display = "none";
        document.getElementById("novaSenhaConfirmar").style.display = "none";
        document.getElementById("novaSenha").value = "";
        document.getElementById("novaSenhaConfirmar").value = "";
    }   
}

// Muda os valores dos campos de título, descrição e preço ao detectar algum erro no envio de imagens
// Muda os valores dos campos do formúlario de cadastro e edição de cadastro
// Chamada em "anuncio.php", "cadastro.php" e "editarCadastro.php"
function mudaValor(i){
    if (i === 1){
        var tit = document.getElementById("controleTit").value;
        var desc = document.getElementById("controleDesc").value;
        var preco = document.getElementById("controlePreco").value;
        document.getElementById("titAnuncio").value = tit;
        document.getElementById("descAnuncio").value = desc;
        document.getElementById("precoAnuncio").value = preco;
    } else if (i === 2) {
        var nome = document.getElementById("controleNome").value;
        var sobrenome = document.getElementById("controleSobrenome").value;
        var email = document.getElementById("controleEmail").value;
        var telefone = document.getElementById("controleTelefone").value;
        var bloco = document.getElementById("controleBloco").value;
        var ap_numero = document.getElementById("controleAp_numero").value;
        var nomeUsuario = document.getElementById("controleNomeUsuario").value;
        document.getElementById("nome").value = nome;
        document.getElementById("sobrenome").value = sobrenome;
        document.getElementById("email").value = email;
        document.getElementById("telefone").value = telefone;
        document.getElementById("bloco").value = bloco;
        document.getElementById("ap_numero").value = ap_numero;
        document.getElementById("nomeUsuario").value = nomeUsuario;
    }
    
}

// Remove os campos que não contenham imagens.
// Chamada em "anuncio.php"
function imgGenerica(cont){ 
    for (var i = 0; i < cont; i++){
        var x = document.getElementsByClassName("imgMenor img1")[i].src;
        var y = document.getElementsByClassName("imgMenor img2")[i].src;
        if(x[x.length - 1] === '%'){
            document.getElementsByClassName("imgMenor img1")[i].hidden = true;
        }
        if(y[y.length - 1] === '%'){
            document.getElementsByClassName("imgMenor img2")[i].hidden = true;
        }
    }   
}

// Muda a classe de estilo dos botões de numeração de página clicados
// Chamada em "index.php"
function paginaLinkClick(i){
    document.getElementById("pagina" + i).className = "paginaLinkClick";
}

// Função para destacar os filtros selecionados. Chamada em "index.php"
function filtrosDestaque(){
    var precoOrdem = document.getElementById("precoOrdem").value;
    var cat = document.getElementById("filtroCategoria").value;
    var cond = document.getElementById("filtroCondicao").value;
    var preco = document.getElementById("filtroPreco").value;
    
    if(precoOrdem !=='%%'){
        switch(precoOrdem){
            case "crescente":{
                document.getElementById("menorPreco").className = "botoesPrecoOrdemClick";
                break;    
            }
            case "decrescente":{
                document.getElementById("maiorPreco").className = "botoesPrecoOrdemClick";    
                break;    
            }
        }
    }
    
    if(cat !== '%%'){
        switch(cat){
            case "Pets_e_acessórios":{
                document.getElementById("1").className = "click";    
                break;
            }
            case "Artigos_infantis":{
                document.getElementById("2").className = "click";    
                break;    
            }
            case "Música_e_hobbies":{
                document.getElementById("3").className = "click";    
                break;     
            }
            case "Moda_e_beleza":{
                document.getElementById("4").className = "click";    
                break;     
            }
            case "Para_a_sua_casa":{
                document.getElementById("5").className = "click";    
                break;     
            }
            case "Esportes_e_lazer":{
                document.getElementById("6").className = "click";    
                break;     
            }
            case "Eletrônicos_e_celulares":{
                document.getElementById("7").className = "click";    
                break;     
            }
            case "Ferramentas":{
                document.getElementById("8").className = "click";    
                break;     
            }
            case "Servicos_autonomos":{
                document.getElementById("9").className = "click";    
                break;     
            }
            case "Veículos_e_acessórios":{
                document.getElementById("10").className = "click";    
                break;     
            }
        }
    }
    
    if(cond !== '%%'){
        switch(cond){
            case "Novo":{
                document.getElementById("11").className = "click";    
                break;      
            }
            case "Usado":{
                document.getElementById("12").className = "click";    
                break;      
            }
        }
    }                                        
    
    if(preco !== '%%'){
        switch(preco){
            case "1000":{
                document.getElementById("13").className = "click";    
                break;      
            }
            case "2500":{
                document.getElementById("14").className = "click";    
                break;      
            }
        }
    }
}

// Função para fazer os filtros do index funcionarem. Chamada em "index.php"
function filtrosIndex (x){
    var busca = document.getElementById("filtroBusca").value;
    var cat = document.getElementById("filtroCategoria").value;
    var cond = document.getElementById("filtroCondicao").value;
    var preco = document.getElementById("filtroPreco").value;
    var precoMin = document.getElementById("precoMinimo").value;
    var precoMax = document.getElementById("precoMaximo").value;
    switch(x){
        case 1:{   
            if(cond !== '%%' || preco !== '%%' || precoMin !== '' || precoMax !== ''){
                if(cond !== '%%' && preco !== '%%'){
                    document.getElementById("1").href = "index.php?busca=" + busca + "&categoria=Pets_e_acessórios&condicao=" + cond + "&preco=" + preco;
                } else if (cond !== '%%' && (precoMin !== '' || precoMax !== '')){
                    document.getElementById("1").href = "index.php?busca=" + busca + "&categoria=Pets_e_acessórios&condicao=" + cond + "&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } else if (cond !== '%%'){
                    document.getElementById("1").href = "index.php?busca=" + busca + "&categoria=Pets_e_acessórios&condicao=" + cond;
                } else if (preco !== '%%'){
                    document.getElementById("1").href = "index.php?busca=" + busca + "&categoria=Pets_e_acessórios&preco=" + preco;
                } else if (precoMin !== '' || precoMax !== ''){
                    document.getElementById("1").href = "index.php?busca=" + busca + "&categoria=Pets_e_acessórios&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                }
            } else {
                return;
            }
            break;    
        }
        case 2:{
            if(cond !== '%%' || preco !== '%%' || precoMin !== '' || precoMax !== ''){
                if(cond !== '%%' && preco !== '%%'){
                    document.getElementById("2").href = "index.php?busca=" + busca + "&categoria=Artigos_infantis&condicao=" + cond + "&preco=" + preco;
                } else if (cond !== '%%' && (precoMin !== '' || precoMax !== '')){
                    document.getElementById("2").href = "index.php?busca=" + busca + "&categoria=Artigos_infantis&condicao=" + cond + "&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } else if (cond !== '%%'){
                    document.getElementById("2").href = "index.php?busca=" + busca + "&categoria=Artigos_infantis&condicao=" + cond;
                } else if (preco !== '%%'){
                    document.getElementById("2").href = "index.php?busca=" + busca + "&categoria=Artigos_infantis&preco=" + preco;
                } else if (precoMin !== '' || precoMax !== ''){
                    document.getElementById("2").href = "index.php?busca=" + busca + "&categoria=Artigos_infantis&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                }
            } else {
                return;
            }
            break;    
        }
        case 3:{
            if(cond !== '%%' || preco !== '%%' || precoMin !== '' || precoMax !== ''){
                if(cond !== '%%' && preco !== '%%'){
                    document.getElementById("3").href = "index.php?busca=" + busca + "&categoria=Música_e_hobbies&condicao=" + cond + "&preco=" + preco;
                } else if (cond !== '%%' && (precoMin !== '' || precoMax !== '')){
                    document.getElementById("3").href = "index.php?busca=" + busca + "&categoria=Música_e_hobbies&condicao=" + cond + "&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } else if (cond !== '%%'){
                    document.getElementById("3").href = "index.php?busca=" + busca + "&categoria=Música_e_hobbies&condicao=" + cond;
                } else if (preco !== '%%'){
                    document.getElementById("3").href = "index.php?busca=" + busca + "&categoria=Música_e_hobbies&preco=" + preco;
                } else if (precoMin !== '' || precoMax !== ''){
                    document.getElementById("3").href = "index.php?busca=" + busca + "&categoria=Música_e_hobbies&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                }
            } else {
                return;
            }
            break;    
        }
        case 4:{
            if(cond !== '%%' || preco !== '%%' || precoMin !== '' || precoMax !== ''){
                if(cond !== '%%' && preco !== '%%'){
                    document.getElementById("4").href = "index.php?busca=" + busca + "&categoria=Moda_e_beleza&condicao=" + cond + "&preco=" + preco;
                } else if (cond !== '%%' && (precoMin !== '' || precoMax !== '')){
                    document.getElementById("4").href = "index.php?busca=" + busca + "&categoria=Moda_e_beleza&condicao=" + cond + "&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } else if (cond !== '%%'){
                    document.getElementById("4").href = "index.php?busca=" + busca + "&categoria=Moda_e_beleza&condicao=" + cond;
                } else if (preco !== '%%'){
                    document.getElementById("4").href = "index.php?busca=" + busca + "&categoria=Moda_e_beleza&preco=" + preco;
                } else if (precoMin !== '' || precoMax !== ''){
                    document.getElementById("4").href = "index.php?busca=" + busca + "&categoria=Moda_e_beleza&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                }
            } else {
                return;
            }
            break;    _        }
        case 5:{
            if(cond !== '%%' || preco !== '%%' || precoMin !== '' || precoMax !== ''){
                if(cond !== '%%' && preco !== '%%'){
                    document.getElementById("5").href = "index.php?busca=" + busca + "&categoria=Para_a_sua_casa&condicao=" + cond + "&preco=" + preco;
                } else if (cond !== '%%' && (precoMin !== '' || precoMax !== '')){
                    document.getElementById("5").href = "index.php?busca=" + busca + "&categoria=Para_a_sua_casa&condicao=" + cond + "&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } else if (cond !== '%%'){
                    document.getElementById("5").href = "index.php?busca=" + busca + "&categoria=Para_a_sua_casa&condicao=" + cond;
                } else if (preco !== '%%'){
                    document.getElementById("5").href = "index.php?busca=" + busca + "&categoria=Para_a_sua_casa&preco=" + preco;
                } else if (precoMin !== '' || precoMax !== ''){
                    document.getElementById("5").href = "index.php?busca=" + busca + "&categoria=Para_a_sua_casa&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                }
            } else {
                return;
            }
            break;    
        }
        case 6:{
            if(cond !== '%%' || preco !== '%%' || precoMin !== '' || precoMax !== ''){
                if(cond !== '%%' && preco !== '%%'){
                    document.getElementById("6").href = "index.php?busca=" + busca + "&categoria=Esportes_e_lazer&condicao=" + cond + "&preco=" + preco;
                } else if (cond !== '%%' && (precoMin !== '' || precoMax !== '')){
                    document.getElementById("6").href = "index.php?busca=" + busca + "&categoria=Esportes_e_lazer&condicao=" + cond + "&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } else if (cond !== '%%'){
                    document.getElementById("6").href = "index.php?busca=" + busca + "&categoria=Esportes_e_lazer&condicao=" + cond;
                } else if (preco !== '%%'){
                    document.getElementById("6").href = "index.php?busca=" + busca + "&categoria=Esportes_e_lazer&preco=" + preco;
                } else if (precoMin !== '' || precoMax !== ''){
                    document.getElementById("6").href = "index.php?busca=" + busca + "&categoria=Esportes_e_lazer&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } 
            } else {
                return;
            }
            break;    
        }
        case 7:{
            if(cond !== '%%' || preco !== '%%' || precoMin !== '' || precoMax !== ''){
                if(cond !== '%%' && preco !== '%%'){
                    document.getElementById("7").href = "index.php?busca=" + busca + "&categoria=Eletrônicos_e_celulares&condicao=" + cond + "&preco=" + preco;
                } else if (cond !== '%%' && (precoMin !== '' || precoMax !== '')){
                    document.getElementById("7").href = "index.php?busca=" + busca + "&categoria=Eletrônicos_e_celulares&condicao=" + cond + "&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } else if (cond !== '%%'){
                    document.getElementById("7").href = "index.php?busca=" + busca + "&categoria=Eletrônicos_e_celulares&condicao=" + cond;
                } else if (preco !== '%%'){
                    document.getElementById("7").href = "index.php?busca=" + busca + "&categoria=Eletrônicos_e_celulares&preco=" + preco;
                } else if (precoMin !== '' || precoMax !== ''){
                    document.getElementById("7").href = "index.php?busca=" + busca + "&categoria=Eletrônicos_e_celulares&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } 
            } else {
                return;
            }
            break;    
        }
        case 8:{
            if(cond !== '%%' || preco !== '%%' || precoMin !== '' || precoMax !== ''){
                if(cond !== '%%' && preco !== '%%'){
                    document.getElementById("8").href = "index.php?busca=" + busca + "&categoria=Ferramentas&condicao=" + cond + "&preco=" + preco;
                } else if (cond !== '%%' && (precoMin !== '' || precoMax !== '')){
                    document.getElementById("8").href = "index.php?busca=" + busca + "&categoria=Ferramentas&condicao=" + cond + "&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } else if (cond !== '%%'){
                    document.getElementById("8").href = "index.php?busca=" + busca + "&categoria=Ferramentas&condicao=" + cond;
                } else if (preco !== '%%'){
                    document.getElementById("8").href = "index.php?busca=" + busca + "&categoria=Ferramentas&preco=" + preco;
                } else if (precoMin !== '' || precoMax !== ''){
                    document.getElementById("8").href = "index.php?busca=" + busca + "&categoria=Ferramentas&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } 
            } else {
                return;
            }
            break;    
        }
        case 9:{
            if(cond !== '%%' || preco !== '%%' || precoMin !== '' || precoMax !== ''){
                if(cond !== '%%' && preco !== '%%'){
                    document.getElementById("9").href = "index.php?busca=" + busca + "&categoria=Servicos_autonomos&condicao=" + cond + "&preco=" + preco;
                } else if (cond !== '%%' && (precoMin !== '' || precoMax !== '')){
                    document.getElementById("9").href = "index.php?busca=" + busca + "&categoria=Servicos_autonomos&condicao=" + cond + "&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } else if (cond !== '%%'){
                    document.getElementById("9").href = "index.php?busca=" + busca + "&categoria=Servicos_autonomos&condicao=" + cond;
                } else if (preco !== '%%'){
                    document.getElementById("9").href = "index.php?busca=" + busca + "&categoria=Servicos_autonomos&preco=" + preco;
                } else if (precoMin !== '' || precoMax !== ''){
                    document.getElementById("9").href = "index.php?busca=" + busca + "&categoria=Servicos_autonomos&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } 
            } else {
                return;
            }
            break;    
        }
        case 10:{
            if(cond !== '%%' || preco !== '%%' || precoMin !== '' || precoMax !== ''){
                if(cond !== '%%' && preco !== '%%'){
                    document.getElementById("10").href = "index.php?busca=" + busca + "&categoria=Veículos_e_acessórios&condicao=" + cond + "&preco=" + preco;
                } else if (cond !== '%%' && (precoMin !== '' || precoMax !== '')){
                    document.getElementById("10").href = "index.php?busca=" + busca + "&categoria=Veículos_e_acessórios&condicao=" + cond + "&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } else if (cond !== '%%'){
                    document.getElementById("10").href = "index.php?busca=" + busca + "&categoria=Veículos_e_acessórios&condicao=" + cond;
                } else if (preco !== '%%'){
                    document.getElementById("10").href = "index.php?busca=" + busca + "&categoria=Veículos_e_acessórios&preco=" + preco;
                } else if (precoMin !== '' || precoMax !== ''){
                    document.getElementById("10").href = "index.php?busca=" + busca + "&categoria=Veículos_e_acessórios&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } 
            } else {
                return;
            }
            break;    
        }
        case 11: {
            if(cat !== '%%' || preco !== '%%' || precoMin !== '' || precoMax !== ''){
                if(cat !== '%%' && preco !== '%%'){
                    document.getElementById("11").href = "index.php?busca=" + busca + "&categoria=" + cat + "&condicao=Novo&preco=" + preco;
                } else if (cat !== '%%' && (precoMin !== '' || precoMax !== '')){
                    document.getElementById("11").href = "index.php?busca=" + busca + "&categoria=" + cat + "&condicao=Novo&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } else if (cat !== '%%'){
                    document.getElementById("11").href = "index.php?busca=" + busca + "&categoria=" + cat + "&condicao=Novo";
                } else if (preco !== '%%'){
                    document.getElementById("11").href = "index.php?busca=" + busca + "&condicao=Novo&preco=" + preco;
                } else if (precoMin !== '' || precoMax !== ''){
                    document.getElementById("11").href = "index.php?busca=" + busca + "&condicao=Novo&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                }
            } else {
                return;
            }
            break;     
        }
        case 12: {
            if(cat !== '%%' || preco !== '%%' || precoMin !== '' || precoMax !== ''){
                if(cat !== '%%' && preco !== '%%'){
                    document.getElementById("12").href = "index.php?busca=" + busca + "&categoria=" + cat + "&condicao=Usado&preco=" + preco;
                } else if (cat !== '%%' && (precoMin !== '' || precoMax !== '')){
                    document.getElementById("12").href = "index.php?busca=" + busca + "&categoria=" + cat + "&condicao=Usado&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } else if (cat !== '%%'){
                    document.getElementById("12").href = "index.php?busca=" + busca + "&categoria=" + cat + "&condicao=Usado";
                } else if (preco !== '%%'){
                    document.getElementById("12").href = "index.php?busca=" + busca + "&condicao=Usado&preco=" + preco;
                } else if (precoMin !== '' || precoMax !== ''){
                    document.getElementById("12").href = "index.php?busca=" + busca + "&condicao=Usado&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                }
            } else {
                return;
            }
            break;     
        }
        case 13:{
            if(cat !== '%%' || cond !== '%%'){
                if(cat !== '%%' && cond !== '%%'){
                    document.getElementById("13").href = "index.php?busca=" + busca + "&categoria=" + cat + "&condicao=" + cond + "&preco=1000";
                } else if (cat !== '%%'){
                    document.getElementById("13").href = "index.php?busca=" + busca + "&categoria=" + cat + "&preco=1000";
                } else if (cond !== '%%'){
                    document.getElementById("13").href = "index.php?busca=" + busca + "&condicao=" + cond + "&preco=1000";
                } 
            } else {
                return;
            }
            break;    
        }
        case 14:{
            if(cat !== '%%' || cond !== '%%'){
                if(cat !== '%%' && cond !== '%%'){
                    document.getElementById("14").href = "index.php?busca=" + busca + "&categoria=" + cat + "&condicao=" + cond + "&preco=2500";
                } else if (cat !== '%%'){
                    document.getElementById("14").href = "index.php?busca=" + busca + "&categoria=" + cat + "&preco=2500";
                } else if (cond !== '%%'){
                    document.getElementById("14").href = "index.php?busca=" + busca + "&condicao=" + cond + "&preco=2500";
                } 
            } else {
                return;
            }
            break;    
        }
        case 15:{
            if (cat !== '%%' || cond !== '%%'){
                if(cat !== '%%' && cond !== '%%'){
                    document.getElementById("15").href = "index.php?busca=" + busca + "&categoria=" + cat + "&condicao=" + cond + "&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } else if (cat !== '%%'){
                    document.getElementById("15").href = "index.php?busca=" + busca + "&categoria=" + cat + "&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } else if (cond !== '%%'){
                    document.getElementById("15").href = "index.php?busca=" + busca + "&condicao=" + cond + "&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
                } 
            } else {
                document.getElementById("15").href = "index.php?busca=" + busca + "&precoMinimo=" + precoMin + "&precoMaximo=" + precoMax;
            }
        break;    
        }
    }
}