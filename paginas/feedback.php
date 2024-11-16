<?php
    // Verifica se o usuário está logado
    if (!(isset($_SESSION['is_logged_in'])) || $_SESSION['is_logged_in'] !== true) {
        echo "<script>alert('Para acessar esta página, é necessário fazer login.');
        window.location.href = 'home.php?dir=paginas&&file=loginusu';
        </script>";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Conecta ao banco de dados
        require_once "conexao.php";
        $conexao = novaConexao();

        // Coleta os dados do formulário
        $dados = $_POST;
        $id_cliente = $_SESSION['id_cliente'];

        // Prepara e executa a query para inserir no banco
        $sql = "INSERT INTO Feedback (Nota, Comentario, ID_cliente) VALUES(?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $params = [
            $dados['nota'],
            $dados['textoComent'],
            $id_cliente 
        ]; 

        $stmt->bind_param('isi', ...$params);
        $stmt->execute(); // Executa a query
        // Exibe uma mensagem de sucesso ou redireciona conforme necessário
        echo "<script>alert('Obrigado pelo seu feedback!'); window.location.href = 'home.php?dir=paginas&&file=sobre_nos';</script>";
        exit;
    }
?>
<link rel="stylesheet" href="./CSS/feedback.css">
<h1>Feedback</h1>

<!-- Formulário de Feedback -->
<form id="feedback-form" class="form_feedback" method="POST">
    <div>
        <label for="textoComent">Envie seu feedback de nosso site!</label>
        <textarea name="textoComent" id="textoComent" required></textarea>
    </div>

    <label for="">Faça uma avaliação!</label>
    <div class="avaliacao">
        <span class="star" data-value="1">&#9733;</span>
        <span class="star" data-value="2">&#9733;</span>
        <span class="star" data-value="3">&#9733;</span>
        <span class="star" data-value="4">&#9733;</span>
        <span class="star" data-value="5">&#9733;</span>
        <span class="star" data-value="6">&#9733;</span>
        <span class="star" data-value="7">&#9733;</span>
        <span class="star" data-value="8">&#9733;</span>
        <span class="star" data-value="9">&#9733;</span>
        <span class="star" data-value="10">&#9733;</span>
        <span id="rating-value"></span>
    </div>

    <!-- Campo oculto para armazenar a nota -->
    <input type="hidden" name="nota" id="hidden-rating">

    <button type="submit">Enviar avaliação</button>
</form>

<script>
    // Seleciona todas as estrelas
    const stars = document.querySelectorAll('.star');
    const ratingValue = document.getElementById('rating-value');
    const hiddenRating = document.getElementById('hidden-rating');
    let selectedRating = 0; // Variável para armazenar a avaliação

    // Função para remover a classe 'hover' de todas as estrelas
    function clearHover() {
        stars.forEach(s => {
            s.classList.remove('hover');
        });
    }

    stars.forEach(star => {
        star.addEventListener('mouseover', () => {
            const value = parseInt(star.getAttribute('data-value'));
            clearHover(); // Limpa qualquer hover anterior
            // Adiciona a classe 'hover' até o valor da estrela em destaque
            stars.forEach(s => {
                if (parseInt(s.getAttribute('data-value')) <= value) {
                    s.classList.add('hover');
                }
            });
        });

        star.addEventListener('mouseout', () => {
            clearHover(); // Limpa o hover quando o mouse sai
        });

        star.addEventListener('click', () => {
            selectedRating = parseInt(star.getAttribute('data-value')); // Salva a avaliação selecionada
            hiddenRating.value = selectedRating; // Atualiza o campo oculto com o valor da nota
            // Adiciona a classe 'active' até a estrela clicada
            stars.forEach(s => {
                s.classList.remove('active'); // Limpa a seleção anterior
                if (parseInt(s.getAttribute('data-value')) <= selectedRating) {
                    s.classList.add('active'); // Marca as estrelas ativas
                }
            });
            ratingValue.innerText = `Avaliação: ${selectedRating} estrelas`; // Exibe a avaliação
        });
    });

    const form = document.getElementById('feedback-form');
    form.addEventListener('submit', (event) => {
        if (selectedRating === 0) {
            event.preventDefault(); // Evita o envio do formulário
            alert('Por favor, selecione uma avaliação antes de enviar!');
        }
    });
</script>
<style>
    footer{
        bottom: 0;
        position: relative;
        width: 100%;
        text-align: center;
    }
</style>