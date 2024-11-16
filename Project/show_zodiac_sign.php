<?php include('layouts/header.php'); ?>

<div class="container mt-5">
    <h1>Qual seu signo?</h1>
    <?php
    $data_nascimento = $_POST['data_nascimento'];
    $signos = simplexml_load_file("signos.xml");
    $data_nascimento = DateTime::createFromFormat('Y-m-d', $data_nascimento); // Certifique-se de que o formato do input seja Y-m-d
    if (!$data_nascimento) {
        echo "<p>Data de nascimento inválida. Verifique o formato (YYYY-MM-DD).</p>";
        exit;
    }
    $signo_encontrado = false;

    foreach ($signos->signo as $signo) {
        $data_inicio = DateTime::createFromFormat('d/m', (string)$signo->dataInicio);
        $data_fim = DateTime::createFromFormat('d/m', (string)$signo->dataFim);

        if (!$data_inicio || !$data_fim) {
            echo "<p>Erro ao processar as datas do signo: {$signo->signoNome}</p>";
            continue;
        }

        // Ajusta os anos das datas de início e fim
        $data_inicio->setDate($data_nascimento->format('Y'), $data_inicio->format('m'), $data_inicio->format('d'));
        $data_fim->setDate($data_nascimento->format('Y'), $data_fim->format('m'), $data_fim->format('d'));

        // Lida com signos que cruzam o limite de ano
        if ($data_inicio > $data_fim) {
            $data_fim->modify('+1 year');
        }

        // Verifica se a data de nascimento está dentro do intervalo
        if ($data_nascimento >= $data_inicio && $data_nascimento <= $data_fim) {
            echo "<h2>{$signo->signoNome}</h2>";
            echo "<p>{$signo->descricao}</p>"; // Exibe a descrição do signo
            $signo_encontrado = true;
            break;
        }
    }

    if (!$signo_encontrado) {
        echo "<p>Não foi possível determinar seu signo. Verifique a data informada.</p>";
    }
    ?>
    <a href="index.php" class="btn btn-secondary mt-3">Voltar</a>
</div>
<?php include('layouts/footer.php'); ?>
