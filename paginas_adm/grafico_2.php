<?php
require_once "conexao.php";
$conexao = novaConexao();
$sql= "SELECT Categoria, COUNT(Categoria) AS qntd
        FROM produto
        GROUP BY Categoria
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
    <title>Document</title>
</head>
<body>
<a href="home_adm.php?dir=paginas_adm&file=tela_inicial_graficos"><img class="voltar" src="assets/de-volta.png" alt="Voltar"></a>
    <div class="corpo" id="piechart" style="width: 1000px; height: 500px;"></div>
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
                echo "['" . $registro['Categoria'] . "', " . $registro['qntd'] . "],";
            }
            ?>
        ]);

        var options = {
            title: 'Distribuição por porcentagem do Público Alvo'
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
            ['Público Alvo', 'Quantidade'],
            <?php
            foreach ($registros as $registro) {
                echo "['" . $registro['Categoria'] . "', " . $registro['qntd'] . "],";
            }
            ?>
        ]);

        var options = {
            title: 'Distribuição por porcentagem do Público Alvo'
        };


        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
      }
    </script>
    <style></style>