<?php
require_once "./paginas/conexao.php";
if (!(isset($_SESSION['is_logged_admin'])) || $_SESSION['is_logged_admin'] !== true) {
    echo "<script>alert('Para acessar esta página, é necessário fazer login.');
    window.location.href = 'home.php?dir=paginas&&file=loginadm';
    </script>";
    exit;
}
$conexao = novaConexao();
$sql= "SELECT Condicao, COUNT(Condicao) AS qntd
        FROM produto
        GROUP BY Condicao
        ORDER BY qntd DESC;";
$resultado = $conexao->query($sql);
$registros = [];

if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $registros[] = $row; 
    }
} elseif ($conexao->error) {
    echo "Erro: " . $conexao->error;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analise de categorias mais cadastradas</title>
</head>
<body>
    <h1 class="titulo">Distribuição por porcentagem das Condições</h1>
    <a href="home_adm.php?dir=paginas_adm&file=tela_inicial_graficos"><img class="voltar" src="assets/de-volta.png" alt="Voltar"></a>
    <div class="container">
        <div class="corpo" id="piechart" style="width: 900px; height: 500px;"></div>
    </div> 
</body>
</html>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Público Alvo', 'Quantidade'],
            <?php
            foreach ($registros as $registro) {
                echo "['" . $registro['Condicao'] . "', " . $registro['qntd'] . "],";
            }
            ?>
        ]);

        var options = {
            title: 'Usado, Semiusado e Novo',
            is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Categoria', 'Quantidade'],
            <?php
            foreach ($registros as $registro) {
                echo "['" . $registro['Categoria'] . "', " . $registro['qntd'] . "],";
            }
            ?>
        ]);

        var options = {
            
            titleTextStyle: { fontSize: 20, bold: true },
            legend: { position: 'right', textStyle: { fontSize: 14 } },
            chartArea: { width: '50%', height: '80%' }
        };


        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
    }
    </script>
<style>


    
.titulo{
    text-align: center;
}
.container {
    margin-top: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

.chart-container {
    width: 80%;
    max-width: 1200px;
    height: 500px;
    padding: 20px;
    background-color: white;
    border-radius: 10px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.voltar {
    position: absolute;
    top: 95px;
    left: 20px;
    width: 30px;
    height: 40px;
    cursor: pointer;
}
</style>
