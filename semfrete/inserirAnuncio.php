<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
if(isset($_SESSION['u_id'])){
    include 'function.php';
    
    // Botão "Anunciar" na interface para criar um novo anúncio
    if (isset($_POST['anunciar'])) {
        include 'bdConexao.php';
        // Contador para saber quantas imagens foram enviadas
        $a = 0;
        for($b=0; $b < 3; $b++){
            if($_FILES['imgAnuncio']['error'][$b] === 4){
                $a++;
            }
        }
        $catAnuncio = mysqli_real_escape_string($conexao, $_POST['catAnuncio']);
        // Verifica se o usuário selecionou uma categoria
        if($catAnuncio === 'selecione'){
            anuncioImgErro(5, 'anunciar');
        }
        // Caso nenhuma imagem for enviada
        if($a === 3){
            anuncioImgErro(1, 'anunciar');
        } else {
            // Recebe os dados da imagem selecionada pelo usuário
	    $imagem = reordenarImagem($_FILES['imgAnuncio']);
            // Cria uma pasta para a imagem
            $prefixoPasta = date('dmY');
            $imagemPasta = "anuncios/" . uniqid($prefixoPasta, true);
            for($i=0; $i < 3; $i++){
                $prefixoImagem = $i;
                // Chama a função para salvar a imagem no servidor
                imagem($imagem[$i], $imagemPasta, $prefixoImagem, 'anunciar');
            }
            // Gera o dia e horário que o anúncio foi postado
            $data = "postado".date("dmYHi");
            // Insere as informações do anúncio no banco de dados
            $titAnuncio = mysqli_real_escape_string($conexao, $_POST['titAnuncio']);
            $descAnuncio = mysqli_real_escape_string($conexao, $_POST['descAnuncio']);
            $precoTemp1 = mysqli_real_escape_string($conexao, $_POST['precoAnuncio']);
            $precoTemp2 = str_replace(".","",$precoTemp1);
            $preco = str_replace(",",".",$precoTemp2);
            $condicao = mysqli_real_escape_string($conexao, $_POST['condicaoAnuncio']);
            $sql = "INSERT INTO anuncio (titulo, descricao, imagens, categoria, u_id, preco, condicao, data) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conexao);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo 'Ocorreu um erro ao postar o seu anúncio!';
            } else {
                mysqli_stmt_bind_param($stmt, "ssssssss", $titAnuncio, $descAnuncio, $imagemPasta , $catAnuncio, $_SESSION['u_id'], $preco, $condicao, $data);
                // Verifica se ocorreu algum erro ao inserir as informações do anúncio no banco de dados
                if(!mysqli_stmt_execute($stmt)){
                    echo 'Ocorreu um erro ao postar o seu anúncio!';
                } else {
                unset($_SESSION['postarAnuncioImgErro']);
                $_SESSION['anunciar'] = "sucesso";
                header("Location: anuncio.php");
                exit();
                }  
            }    
        }
    }
    
    
    
    // Botão "Salvar Alterações" na interface para edição de anúncio
    if(isset($_POST['alteracoes'])){
        // Recebe os dados das imagens selecionadas pelo usuário para serem apagadas ou substituídas
        $img0Apagar = $_POST['img0ControleApagar'];
        $img1Apagar = $_POST['img1ControleApagar'];
        $img2Apagar = $_POST['img2ControleApagar'];
	$imagemEdit = reordenarImagem($_FILES['imgEditarAnuncio']);
        $imagemDeletar = $_POST['imgControle'];
        $imagemPasta = $_POST['pastaControle'];
        // Contador para saber quantas imagens foram enviadas
        $a = 0;
        for($i=0; $i < 3; $i++){
            if($imagemEdit[$i]['error'] === 4){
                $a++;        
            }
        }
        // Se nenhuma imagem for enviada
        if($a === 3){
            // Contador para saber quantas imagens foram apagadas
            $b = 0;
            $pastaScan = array_diff(scandir($imagemPasta), array('..', '.'));
            // Definição da posição dos comandos para apagar as imagens
            $imgApagarPosicao[0] = $img0Apagar;
            $imgApagarPosicao[1] = $img1Apagar;
            $imgApagarPosicao[2] = $img2Apagar;
            $pastaScanCont = count($pastaScan);
            // Definição da posição das imagens existentes na pasta do anúncio
            if($pastaScanCont === 0){
                $imgPosicao[0] = 0;
                $imgPosicao[1] = 0;
                $imgPosicao[2] = 0;
            } else {
                if($pastaScanCont === 1){
                    $imgPosicao[0] = 1;
                    $imgPosicao[1] = 0;
                    $imgPosicao[2] = 0;
                } else {
                    if($pastaScanCont === 2){
                        $imgPosicao[0] = 1;
                        $imgPosicao[1] = 1;
                        $imgPosicao[2] = 0;
                    } else {
                        if($pastaScanCont === 3){
                            $imgPosicao[0] = 1;
                            $imgPosicao[1] = 1;
                            $imgPosicao[2] = 1;
                        }
                    }
                }
            }
            // Verificação dos comandos para apagar imagem em relação a posição das imagens existentes
            for($i=0; $i < 3; $i++){
                if($imgPosicao[$i] === 1 && $imgApagarPosicao[$i] === '1'){
                    $b++;        
                }
            }
            // Caso um número de imagens maior ou igual ao existente na pasta do anúncio for apagado, deixando o anúncio sem imagens
            if($pastaScanCont <= $b){
                anuncioImgErro(1, 'editar');
            }
        }
        
        // Envia as imagens selecionadas para o servidor
        for($i=0; $i < 3; $i++){
            $prefixoImagem = $i;
            // Chama a função para salvar a imagem no servidor
            imagem($imagemEdit[$i], $imagemPasta, $prefixoImagem, 'editar'); 
            if($imagemEdit[$i]['error'] !== 4){
                // Deleta a imagem que será substituída
                if(file_exists($imagemDeletar[$i]) && $imagemDeletar[$i] !== 'placeholder.png'){
                    unlink($imagemDeletar[$i]);
                }
            } 
        }
        
        // Caso o comando para apagar a primeira imagem seja enviado
        if($img0Apagar === '1'){
            if(file_exists($imagemDeletar[0])){
                unlink($imagemDeletar[0]);
            }
        }
        // Caso o comando para apagar a segunda imagem seja enviado
        if($img1Apagar === '1'){
            if(file_exists($imagemDeletar[1])){
                unlink($imagemDeletar[1]);
            }
        }
        // Caso o comando para apagar a terceira imagem seja enviado
        if($img2Apagar === '1'){
            if(file_exists($imagemDeletar[2])){
                unlink($imagemDeletar[2]);
            }
        }
        // Gera o dia e horário que o anúncio foi postado
        $data = "editado".date("dmYHi");
        // Insere as novas informações do anúncio no banco de dados
        include 'bdConexao.php';
        $a_id = mysqli_real_escape_string($conexao, $_POST['a_idEditarAnuncio']);
        $titulo = mysqli_real_escape_string($conexao, $_POST['titEditarAnuncio']);
        $descricao = mysqli_real_escape_string($conexao, $_POST['descEditarAnuncio']);
        $categoria = mysqli_real_escape_string($conexao, $_POST['catEditarAnuncio']);
        $precoTemp1 = mysqli_real_escape_string($conexao, $_POST['precoEditarAnuncio']);
        $precoTemp2 = str_replace(".","",$precoTemp1);
        $preco = str_replace(",",".",$precoTemp2);
        $condicao = mysqli_real_escape_string($conexao, $_POST['condicaoAnuncio']);
        $sql = "UPDATE anuncio SET titulo=?, descricao=?, categoria=?, preco=?, condicao=?, data=? WHERE a_id=?";
        $stmt = mysqli_stmt_init($conexao);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo 'Ocorreu um erro ao editar o seu anúncio!';
        } else {
            mysqli_stmt_bind_param($stmt, "sssssss", $titulo, $descricao, $categoria, $preco, $condicao, $data, $a_id);
            // Verifica se ocorreu algum erro ao inserir as novas informações do anúncio no banco de dados
            if(!mysqli_stmt_execute($stmt)){
                echo 'Ocorreu um erro ao editar o seu anúncio!';
            } else {
                unset($_SESSION['postarAnuncioImgErro']);
                header("Location: anuncio.php?meusAnuncios");
                exit();
            }  
        }
    }
    
    // Botão "Cancelar" na interface para edição de anúncio, na interface para postar um novo anúncio ou na interface para apagar um anúncio
    if(isset($_POST['cancelarAlteracoes']) || isset($_POST['cancelar']) || isset($_POST['cancelarApagar'])){
        if(isset($_POST['cancelarAlteracoes']) || isset($_POST['cancelarApagar'])){
            unset($_SESSION['postarAnuncioImgErro']);
            header("Location: anuncio.php?meusAnuncios");
            exit();
        } else if (isset($_POST['cancelar'])){
            header("Location: index.php");
            exit();
        }   
    }
    
    // Botão "Enviar" no formulário de feedback antes de apagar um anúncio
    if(isset($_POST['enviar'])){
        $_SESSION['apagarAnuncio'] = "sucesso";
        include 'bdConexao.php';
        $motivo = mysqli_real_escape_string($conexao, $_POST['motivoApagar']);
        $avaliacao = mysqli_real_escape_string($conexao, $_POST['avaliacaoEntrada']);
        $i = $_POST['anuncioControle'];
        $comentario = '';
        if($motivo === ''){
            anuncioImgErro(1, 'apagar');
        }
        if($motivo === 'Outro motivo' && isset($_POST['comentario'])){
            $comentario = mysqli_real_escape_string($conexao, $_POST['comentario']);
        }
        
        // Deleta do banco de dados todas as informações do anúncio selecionado
        $sql = "DELETE FROM anuncio WHERE a_id=?";
        $stmt = mysqli_stmt_init($conexao);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo 'Ocorreu um erro ao apagar o seu anúncio!';
        } else {
            mysqli_stmt_bind_param($stmt, "s", $_SESSION['anuncio'][$i]['a_id']);
            // Verifica se ocorreu algum erro ao inserir as novas informações do anúncio no banco de dados
            if(!mysqli_stmt_execute($stmt)){
                echo 'Ocorreu um erro ao apagar o seu anúncio!';
            } else {
                // Chama a função para apagar o diretório e todas as imagens armazenadas do anúncio selecionado
                rrmdir($_SESSION['anuncio'][$i]['imagens']);
            }  
        }
        // Insere as informações do formulário de opinião no banco de dados
        $sql = "INSERT INTO opiniao (motivo, avaliacao, comentario, a_id, a_titulo) VALUES (?, ?, ?, ?, ?)";
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo 'Ocorreu um erro ao apagar o seu anúncio!';
        } else {
            mysqli_stmt_bind_param($stmt, "sssss", $motivo, $avaliacao, $comentario, $_SESSION['anuncio'][$i]['a_id'], $_SESSION['anuncio'][$i]['titulo']);
            // Verifica se ocorreu algum erro ao inserir as novas informações do formulário de opinião no banco de dados
            if(!mysqli_stmt_execute($stmt)){
                echo 'Ocorreu um erro ao apagar o seu anúncio!';
            } else {
                $_SESSION['apagarAnuncio'] = "sucesso";
                header("Location: editarAnuncio.php");
                exit();
            }  
        }
    }
    
    // Botão "Enviar" no formulário de críticas e sugestões acessado pelo link no rodapé
    if(isset($_POST['enviarFeedback'])){
        include 'bdConexao.php';
        $feedback = mysqli_real_escape_string($conexao, $_POST['feedback']);
        // Insere as informações do formulário de críticas e sugestões no banco de dados
        $sql = "INSERT INTO criticas (u_nome, u_sobrenome, u_email, feedback) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conexao);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo 'Ocorreu um erro ao enviar a sua crítica!';
        } else {
            mysqli_stmt_bind_param($stmt, "ssss", $_SESSION['u_nome'], $_SESSION['u_sobrenome'], $_SESSION['u_email'], $feedback);
            // Verifica se ocorreu algum erro ao inserir as novas informações do formulário de críticas e sugestões no banco de dados
            if(!mysqli_stmt_execute($stmt)){
                echo 'Ocorreu um erro ao enviar a sua crítica!';
            } else {
                $_SESSION['critica'] = "sucesso";
                header("Location: feedback.php");
                exit();
            }
        }   
    }
} else {
    header("Location: index.php");
    exit();
}