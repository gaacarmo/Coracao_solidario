<?php
    if (!(isset($_SESSION['is_logged_in'])) || $_SESSION['is_logged_in'] !== true) {
        echo "<script>alert('Para acessar esta página, é necessário fazer login.');
        window.location.href = 'home.php?dir=paginas&&file=loginusu';
        </script>";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        require_once "conexao.php";
        $conexao = novaConexao();

        $dados = $_POST;
        $id_cliente = $_SESSION['id_cliente'];

        $sql = "INSERT INTO Feedback (Nota, Comentario, ID_cliente) VALUES(?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $params = [
            $dados['nota'],
            $dados['textoComent'],
            $id_cliente 
        ]; 

        $stmt->bind_param('isi', ...$params);
        $stmt->execute();
        echo "<script>alert('Obrigado pelo seu feedback!'); window.location.href = 'home.php?dir=paginas&&file=sobre_nos';</script>";
        exit;
    }
?>
<link rel="stylesheet" href="./CSS/feedback.css">
<h1>Feedback</h1>


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

    <input type="hidden" name="nota" id="hidden-rating">

    <button type="submit">Enviar avaliação</button>
</form>

<script>
    const stars = document.querySelectorAll('.star');
    const ratingValue = document.getElementById('rating-value');
    const hiddenRating = document.getElementById('hidden-rating');
    let selectedRating = 0;

    function clearHover() {
        stars.forEach(s => {
            s.classList.remove('hover');
        });
    }

    stars.forEach(star => {
        star.addEventListener('mouseover', () => {
            const value = parseInt(star.getAttribute('data-value'));
            clearHover();
            stars.forEach(s => {
                if (parseInt(s.getAttribute('data-value')) <= value) {
                    s.classList.add('hover');
                }
            });
        });

        star.addEventListener('mouseout', () => {
            clearHover();
        });

        star.addEventListener('click', () => {
            selectedRating = parseInt(star.getAttribute('data-value'));
            hiddenRating.value = selectedRating; 
            stars.forEach(s => {
                s.classList.remove('active'); 
                if (parseInt(s.getAttribute('data-value')) <= selectedRating) {
                    s.classList.add('active'); 
                }
            });
            ratingValue.innerText = `Avaliação: ${selectedRating} estrelas`; 
        });
    });

    const form = document.getElementById('feedback-form');
    form.addEventListener('submit', (event) => {
        if (selectedRating === 0) {
            event.preventDefault(); 
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
