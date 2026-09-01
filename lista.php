<?php

session_start();

header('Content-Type: text/html; charset=utf-8');

if (
    !isset($_GET['id_sessao']) ||
    !isset($_SESSION['resultado_alerta'])
) {
    die('Não foi possível localizar a análise.');
}

$dados = $_SESSION['resultado_alerta'];

if (
    $_GET['id_sessao'] !== $dados['id_sessao']
) {
    die('Sessão de análise inválida.');
}

/*
 * =========================================================
 * CRITÉRIO DE STATUS
 * =========================================================
 *
 * O critério apresentado ao usuário depende do formato
 * do arquivo analisado.
 */

if ($dados['arquivo']['formato'] === 'pdf') {

    $criterioStatus =
        'Percentual de infrequência, conforme parâmetros da SEDU/ES.';

} else {

    $criterioStatus =
        'Quantidade de faltas em relação ao limite definido nesta análise. Esta classificação é um indicador interno do sistema e não representa um percentual oficial de frequência da SEDU/ES.';

}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>
        Resultado da análise
    </title>

</head>

<body>

    <h1>
        Resultado da análise
    </h1>

    <p>

        Arquivo:

        <?= htmlspecialchars(
            $dados['arquivo']['nome']
        ) ?>

    </p>

    <p>

        Limite:

        <?= $dados['limite'] ?>

    </p>

    <p>

        Alunos encontrados:

        <?= $dados['total_alunos_acima_limite'] ?>

    </p>

    <!-- =====================================================
         CRITÉRIO DE STATUS
         ===================================================== -->

    <p>

        <strong>
            Critério de status:
        </strong>

        <?= htmlspecialchars(
            $criterioStatus
        ) ?>

    </p>

    <hr>

    <?php foreach ($dados['alunos'] as $aluno): ?>

        <p>

            <strong>

                <?= htmlspecialchars(
                    $aluno['nome']
                ) ?>

            </strong>

            —

            <?= htmlspecialchars(
                $aluno['turma']
            ) ?>

            —

            <?= $aluno['faltas'] ?>

            faltas

            —

            <?= htmlspecialchars(
                $aluno['status'] ?? 'Não informado'
            ) ?>

        </p>

    <?php endforeach; ?>

</body>

</html>
