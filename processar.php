<?php

session_start();

header('Content-Type: application/json; charset=utf-8');


/* =========================================================
   BIBLIOTECAS
   ========================================================= */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/database.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use Smalot\PdfParser\Parser;


/* =========================================================
   FUNÇÃO — RESPONDER JSON
   ========================================================= */

function responder(array $dados, int $statusHTTP = 200): void
{
    http_response_code($statusHTTP);

    echo json_encode(
        $dados,
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES
    );

    exit;
}


/* =========================================================
   FUNÇÃO — NORMALIZAR TEXTO
   ========================================================= */

function normalizarTexto(string $texto): string
{
    $texto = trim($texto);

    if ($texto === '') {
        return '';
    }

    $texto = mb_strtolower(
        $texto,
        'UTF-8'
    );

    $textoSemAcentos = iconv(
        'UTF-8',
        'ASCII//TRANSLIT//IGNORE',
        $texto
    );

    if ($textoSemAcentos !== false) {
        $texto = $textoSemAcentos;
    }

    $texto = preg_replace(
        '/[^a-z0-9]+/',
        ' ',
        $texto
    );

    return trim($texto);
}


/* =========================================================
   FUNÇÃO — CONVERTER VALOR DE FALTAS
   ========================================================= */

function converterFaltas($valor): ?int
{
    if ($valor === null) {
        return null;
    }

    if (is_int($valor)) {
        return $valor;
    }

    if (is_float($valor)) {
        return (int) round($valor);
    }

    $valor = trim(
        (string) $valor
    );

    if ($valor === '') {
        return null;
    }

    /*
     * Exemplos aceitos:
     *
     * 10
     * "10"
     * "10 faltas"
     */

    if (
        preg_match(
            '/\d+/',
            $valor,
            $resultado
        )
    ) {
        return (int) $resultado[0];
    }

    return null;
}


/* =========================================================
   FUNÇÃO — CONVERTER FREQUÊNCIA
   ========================================================= */

function converterFrequencia($valor): ?float
{
    if ($valor === null) {
        return null;
    }

    $valor = trim(
        (string) $valor
    );

    if ($valor === '') {
        return null;
    }

    /*
     * Aceita:
     *
     * 87,5%
     * 87.5%
     * 100%
     */

    $valor = str_replace(
        '%',
        '',
        $valor
    );

    $valor = str_replace(
        ',',
        '.',
        $valor
    );

    if (!is_numeric($valor)) {
        return null;
    }

    $frequencia = (float) $valor;

    if (
        $frequencia < 0 ||
        $frequencia > 100
    ) {
        return null;
    }

    return $frequencia;
}


/* =========================================================
   FUNÇÃO — CLASSIFICAR STATUS PELA FREQUÊNCIA
   ========================================================= */

function classificarStatusPorFrequencia(
    ?float $frequencia
): ?string {

    if ($frequencia === null) {
        return null;
    }

    /*
     * CLASSIFICAÇÃO DO PDF
     *
     * A classificação utiliza a frequência apresentada
     * no próprio relatório.
     *
     * Percentual de faltas:
     *
     * menor que 20%  -> Regular
     * de 20% a 25%   -> Atenção
     * acima de 25%   -> Crítico
     */

    $percentualFaltas =
        100 - $frequencia;

    if ($percentualFaltas < 20) {
        return 'Regular';
    }

    if ($percentualFaltas <= 25) {
        return 'Atenção';
    }

    return 'Crítico';
}


/* =========================================================
   FUNÇÃO — CLASSIFICAR STATUS DA PLANILHA
   ========================================================= */

function classificarStatusPorFaltas(
    int $faltas,
    int $limite
): string {

    /*
     * CLASSIFICAÇÃO DA PLANILHA
     *
     * A planilha pode não possuir a quantidade de aulas
     * dadas e, portanto, não permite calcular a frequência.
     *
     * Nesse caso, o sistema utiliza o limite definido
     * pelo usuário como referência operacional.
     *
     * Até 125% do limite -> Regular
     * Até 150% do limite -> Atenção
     * Acima de 150%      -> Crítico
     */

    if ($limite <= 0) {
        return 'Regular';
    }

    $percentualDoLimite =
        ($faltas / $limite) * 100;

    if ($percentualDoLimite <= 125) {
        return 'Regular';
    }

    if ($percentualDoLimite <= 150) {
        return 'Atenção';
    }

    return 'Crítico';
}


/* =========================================================
   FUNÇÃO — LOCALIZAR CABEÇALHOS DA PLANILHA
   ========================================================= */

function localizarCabecalhos(array $linha): array
{
    $colunas = [
        'aluno' => null,
        'turma' => null,
        'faltas' => null
    ];

    foreach ($linha as $indice => $valor) {

        if ($valor === null) {
            continue;
        }

        $cabecalho = normalizarTexto(
            (string) $valor
        );


        /* -------------------------------------------------
           ALUNO
           ------------------------------------------------- */

        if (
            $cabecalho === 'aluno' ||
            $cabecalho === 'nome' ||
            $cabecalho === 'nome aluno' ||
            $cabecalho === 'estudante'
        ) {
            $colunas['aluno'] = $indice;
            continue;
        }


        /* -------------------------------------------------
           TURMA
           ------------------------------------------------- */

        if (
            $cabecalho === 'turma' ||
            $cabecalho === 'nome da turma' ||
            $cabecalho === 'classe'
        ) {
            $colunas['turma'] = $indice;
            continue;
        }


        /* -------------------------------------------------
           FALTAS
           ------------------------------------------------- */

        /*
         * É importante NÃO aceitar automaticamente
         * "faltas justificadas".
         *
         * Queremos o total de faltas.
         */

        if (
            $cabecalho === 'faltas' ||
            $cabecalho === 'total de faltas' ||
            $cabecalho === 'total faltas' ||
            $cabecalho === 'faltas total'
        ) {
            $colunas['faltas'] = $indice;
            continue;
        }
    }

    return $colunas;
}


/* =========================================================
   FUNÇÃO — LER PLANILHA
   ========================================================= */

function lerPlanilha(
    string $arquivoTemporario,
    int $limite
): array {

    $spreadsheet = IOFactory::load(
        $arquivoTemporario
    );

    $alunos = [];

    $totalLidos = 0;

    $estruturaReconhecida = false;


    /* =====================================================
       PERCORRER TODAS AS ABAS
       ===================================================== */

    foreach (
        $spreadsheet->getWorksheetIterator()
        as $worksheet
    ) {

        $linhas = $worksheet->toArray(
            null,
            true,
            true,
            false
        );

        $cabecalhos = null;


        /* =================================================
           PROCURAR A LINHA DOS CABEÇALHOS
           ================================================= */

        foreach (
            array_slice(
                $linhas,
                0,
                20,
                true
            )
            as $indice => $linha
        ) {

            $colunas = localizarCabecalhos(
                $linha
            );

            if (
                $colunas['aluno'] !== null &&
                $colunas['turma'] !== null &&
                $colunas['faltas'] !== null
            ) {

                $cabecalhos = [
                    'indice' => $indice,
                    'colunas' => $colunas
                ];

                $estruturaReconhecida = true;

                break;
            }
        }


        /* =================================================
           ABA SEM ESTRUTURA RECONHECIDA
           ================================================= */

        if ($cabecalhos === null) {
            continue;
        }

        $colunas =
            $cabecalhos['colunas'];

        $linhaInicial =
            $cabecalhos['indice'] + 1;


        /* =================================================
           LER ALUNOS
           ================================================= */

        for (
            $i = $linhaInicial;
            $i < count($linhas);
            $i++
        ) {

            $linha = $linhas[$i];


            /* -------------------------------------------------
               NOME
               ------------------------------------------------- */

            $nome =
                isset(
                    $linha[$colunas['aluno']]
                )
                    ? trim(
                        (string)
                        $linha[$colunas['aluno']]
                    )
                    : '';


            /* -------------------------------------------------
               TURMA
               ------------------------------------------------- */

            $turma =
                isset(
                    $linha[$colunas['turma']]
                )
                    ? trim(
                        (string)
                        $linha[$colunas['turma']]
                    )
                    : '';


            /* -------------------------------------------------
               FALTAS
               ------------------------------------------------- */

            $faltas =
                isset(
                    $linha[$colunas['faltas']]
                )
                    ? converterFaltas(
                        $linha[$colunas['faltas']]
                    )
                    : null;


            /*
             * Ignorar linhas vazias ou incompletas.
             */

            if (
                $nome === '' ||
                $faltas === null
            ) {
                continue;
            }

            $totalLidos++;


            /*
             * Se não houver turma preenchida,
             * mantemos uma informação neutra.
             */

            if ($turma === '') {
                $turma = 'Não informada';
            }


            /*
             * Só entram no resultado os alunos
             * que atingiram ou ultrapassaram o limite.
             */

            if ($faltas < $limite) {
                continue;
            }


            /* -------------------------------------------------
               CLASSIFICAR STATUS DA PLANILHA
               ------------------------------------------------- */

            $status =
                classificarStatusPorFaltas(
                    $faltas,
                    $limite
                );


            /* -------------------------------------------------
               ADICIONAR ALUNO AO RESULTADO
               ------------------------------------------------- */

            $alunos[] = [
                'nome' => $nome,
                'turma' => $turma,
                'faltas' => $faltas,
                'status' => $status
            ];
        }
    }

        if (!$estruturaReconhecida) {

        throw new RuntimeException(
            'PLANILHA_SEM_ESTRUTURA'
        );
    }

    return [
        'alunos' => $alunos,
        'total_lidos' => $totalLidos
    ];
}


/* =========================================================
   FUNÇÃO — LER PDF
   ========================================================= */

function lerPDF(
    string $arquivoTemporario,
    int $limite
): array {

    $parser = new Parser();

    $pdf = $parser->parseFile(
        $arquivoTemporario
    );

    $texto = $pdf->getText();


    if (trim($texto) === '') {

        throw new RuntimeException(
            'Não foi possível extrair texto do PDF.'
        );
    }


    $linhas = preg_split(
        '/\R/u',
        $texto
    );

    $alunos = [];

    $totalLidos = 0;

    $turmaAtual = 'Não informada';


    /* =====================================================
       EXPRESSÃO DO RESULTADO PARCIAL
       ===================================================== */

    /*
     * Estrutura observada no relatório de exemplo:
     *
     * NOME
     * + dados de aproveitamento
     * + faltas por trimestre
     * + TOTAL DE FALTAS
     * + horas
     * + frequência
     *
     * Exemplo:
     *
     * 14 GIOVANA RAFALSKI DAS CANDEIA
     * 18 13 --- 31 --- ---
     * 2 8 0 10 8:20 87,5%
     *
     * O padrão abaixo identifica o registro completo
     * quando ele estiver apresentado em uma única linha.
     */

    $padraoAluno =
        '/^\s*'
        . '(\d+)'
        . '\s+'
        . '(.+?)'
        . '\s+'
        . '(?:\d+|---)\s+'
        . '(?:\d+|---)\s+'
        . '(?:\d+|---)\s+'
        . '(?:\d+|---)\s+'
        . '(?:\d+|---)\s+'
        . '(?:\d+|---)\s+'
        . '(\d+)\s+'
        . '(\d+)\s+'
        . '(\d+)\s+'
        . '(\d+)\s+'
        . '(\d+:\d+)\s+'
        . '([\d.,]+%)'
        . '\s*$/u';


    /* =====================================================
       PERCORRER TEXTO DO PDF
       ===================================================== */

    foreach ($linhas as $linha) {

        $linha = trim($linha);

        if ($linha === '') {
            continue;
        }


        /* -------------------------------------------------
           IDENTIFICAR TURMA
           ------------------------------------------------- */

        if (
            preg_match(
                '/Turma:\s*([^|]+)/iu',
                $linha,
                $resultadoTurma
            )
        ) {

            $turmaAtual = trim(
                $resultadoTurma[1]
            );
        }


        /* -------------------------------------------------
           IDENTIFICAR ALUNO
           ------------------------------------------------- */

        if (
            !preg_match(
                $padraoAluno,
                $linha,
                $resultado
            )
        ) {
            continue;
        }


        $nome = trim(
            $resultado[2]
        );


        /*
         * O quarto valor do bloco de faltas
         * é o TOTAL DE FALTAS.
         *
         * Os grupos são:
         *
         * 1º trimestre
         * 2º trimestre
         * 3º trimestre
         * TOTAL
         * horas
         * frequência
         */

        $faltas =
            (int) $resultado[6];


        /*
         * O último valor do registro corresponde
         * à frequência.
         */

        $frequencia =
            converterFrequencia(
                $resultado[8]
            );


        /*
         * Classificação baseada na frequência
         * existente no próprio PDF.
         */

        $status =
            classificarStatusPorFrequencia(
                $frequencia
            );


        $totalLidos++;


        /*
         * Só entram no resultado os alunos
         * que atingiram ou ultrapassaram o limite.
         */

        if ($faltas < $limite) {
            continue;
        }


        $alunos[] = [
            'nome' => $nome,
            'turma' => $turmaAtual,
            'faltas' => $faltas,
            'status' => $status
        ];
    }

    if ($totalLidos === 0) {

    throw new RuntimeException(
        'PDF_SEM_ALUNOS'
    );
}


    return [
        'alunos' => $alunos,
        'total_lidos' => $totalLidos
    ];
}


/* =========================================================
   VERIFICAR SE FOI ENVIADO UM ARQUIVO
   ========================================================= */

if (!isset($_FILES['arquivo'])) {

    responder([
        'sucesso' => false,
        'mensagem' => 'Nenhum arquivo foi enviado.'
    ], 400);
}


/* =========================================================
   RECEBER DADOS
   ========================================================= */

$arquivo = $_FILES['arquivo'];

$limite =
    isset($_POST['limite'])
        ? (int) $_POST['limite']
        : 0;


/* =========================================================
   VALIDAR LIMITE
   ========================================================= */

if ($limite < 1) {

    responder([
        'sucesso' => false,
        'mensagem' =>
            'O limite de faltas informado é inválido.'
    ], 400);
}


if ($limite > 999) {

    responder([
        'sucesso' => false,
        'mensagem' =>
            'O limite de faltas informado é inválido.'
    ], 400);
}


/* =========================================================
   VERIFICAR ERRO DO UPLOAD
   ========================================================= */

if (
    !isset($arquivo['error']) ||
    $arquivo['error'] !== UPLOAD_ERR_OK
) {

    responder([
        'sucesso' => false,
        'mensagem' =>
            'Ocorreu um erro ao enviar o arquivo.'
    ], 400);
}


/* =========================================================
   INFORMAÇÕES DO ARQUIVO
   ========================================================= */

$nomeArquivo =
    $arquivo['name'];

$tamanhoArquivo =
    $arquivo['size'];

$arquivoTemporario =
    $arquivo['tmp_name'];


/* =========================================================
   VERIFICAR TAMANHO
   ========================================================= */

$TAMANHO_MAXIMO =
    20 * 1024 * 1024;


if ($tamanhoArquivo > $TAMANHO_MAXIMO) {

    responder([
        'sucesso' => false,
        'mensagem' =>
            'O arquivo ultrapassa o tamanho máximo permitido de 20 MB.'
    ], 400);
}


/* =========================================================
   IDENTIFICAR EXTENSÃO
   ========================================================= */

$extensao =
    strtolower(
        pathinfo(
            $nomeArquivo,
            PATHINFO_EXTENSION
        )
    );


$formatosPermitidos = [
    'pdf',
    'xls',
    'xlsx'
];


if (
    !in_array(
        $extensao,
        $formatosPermitidos,
        true
    )
) {

    responder([
        'sucesso' => false,
        'mensagem' =>
            'Formato de arquivo não permitido.'
    ], 400);
}


/* =========================================================
   PROCESSAR ARQUIVO
   ========================================================= */

try {

    if (
        $extensao === 'xls' ||
        $extensao === 'xlsx'
    ) {

        $resultado =
            lerPlanilha(
                $arquivoTemporario,
                $limite
            );

        $formato =
            'planilha';

    } elseif ($extensao === 'pdf') {

        $resultado =
            lerPDF(
                $arquivoTemporario,
                $limite
            );

        $formato =
            'pdf';
    }

} catch (Throwable $erro) {

    error_log(
        'Erro ao processar arquivo: '
        . $erro->getMessage()
    );


    if (
        $erro->getMessage() ===
        'PLANILHA_SEM_ESTRUTURA'
    ) {

        responder([
            'sucesso' => false,
            'mensagem' =>
                'Não foi possível identificar os dados da planilha. Verifique se ela possui as colunas de aluno, turma e faltas.'
        ], 400);
    }


    if (
        $erro->getMessage() ===
        'PDF_SEM_ALUNOS'
    ) {

        responder([
            'sucesso' => false,
            'mensagem' =>
                'Não foi possível identificar os dados dos alunos no PDF. Verifique se o arquivo corresponde ao formato esperado.'
        ], 400);
    }


    responder([
        'sucesso' => false,
        'mensagem' =>
            'Não foi possível ler o arquivo. Verifique se ele está íntegro e em um formato compatível.'
    ], 500);
}

/* =========================================================
   IDENTIFICAR A SESSÃO DESTA ABA
   ========================================================= */

$idSessao =
    $_POST['id_sessao']
    ?? '';


/* =========================================================
   VALIDAR IDENTIFICADOR DA SESSÃO
   ========================================================= */

if (
    !preg_match(
        '/^[a-f0-9-]{36}$/i',
        $idSessao
    )
) {

    responder([
        'sucesso' => false,
        'mensagem' =>
            'A sessão atual não foi encontrada.'
    ], 401);
}


/* =========================================================
   VERIFICAR SESSÃO NO BANCO
   ========================================================= */

$sqlSessao = "
    SELECT
        id_sessao,
        data_fim,
        ultima_atividade
    FROM sessoes
    WHERE id_sessao = :id_sessao
    LIMIT 1
";

$stmtSessao =
    $pdo->prepare($sqlSessao);

$stmtSessao->execute([
    ':id_sessao' => $idSessao
]);

$sessao =
    $stmtSessao->fetch(PDO::FETCH_ASSOC);


/* =========================================================
   VALIDAR EXISTÊNCIA E EXPIRAÇÃO
   ========================================================= */

if (
    !$sessao ||
    $sessao['data_fim'] !== null
) {

    responder([
        'sucesso' => false,
        'mensagem' =>
            'A sessão atual não foi encontrada ou já foi encerrada.'
    ], 401);
}


/* =========================================================
   VERIFICAR 12 HORAS DE INATIVIDADE
   ========================================================= */

$ultimaAtividade =
    strtotime(
        $sessao['ultima_atividade']
    );

if (
    $ultimaAtividade <=
    time() - (12 * 60 * 60)
) {

    responder([
        'sucesso' => false,
        'mensagem' =>
            'Sua sessão expirou após 12 horas sem atividade.'
    ], 401);
}


/* =========================================================
   REGISTRAR ATIVIDADE
   ========================================================= */

$sqlAtividade = "
    UPDATE sessoes
    SET ultima_atividade = CURRENT_TIMESTAMP
    WHERE id_sessao = :id_sessao
      AND data_fim IS NULL
";

$stmtAtividade =
    $pdo->prepare($sqlAtividade);

$stmtAtividade->execute([
    ':id_sessao' => $idSessao
]);

/* =========================================================
   SALVAR ANÁLISE NO BANCO DE DADOS
   ========================================================= */

try {

    $pdo->beginTransaction();


    /* -----------------------------------------------------
       CRIAR REGISTRO DA ANÁLISE
       ----------------------------------------------------- */

    $sqlAnalise = "
        INSERT INTO analises (
            id_sessao,
            nome_arquivo,
            limite_faltas,
            email_enviado
        )
        VALUES (
            :id_sessao,
            :nome_arquivo,
            :limite_faltas,
            FALSE
        )
    ";

    $stmtAnalise = $pdo->prepare($sqlAnalise);

    $stmtAnalise->execute([
        ':id_sessao' =>
            $idSessao,

        ':nome_arquivo' =>
            $nomeArquivo,

        ':limite_faltas' =>
            $limite
    ]);


    /* -----------------------------------------------------
       PEGAR ID DA ANÁLISE
       ----------------------------------------------------- */

    $idAnalise =
        (int) $pdo->lastInsertId();

        $caminhoPasta =
    __DIR__ . '/../uploads/analises/';

if (!is_dir($caminhoPasta)) {
    mkdir(
        $caminhoPasta,
        0755,
        true
    );
}

$nomeArquivoSalvo =
    $idAnalise . '.' . $extensao;

$caminhoCompleto =
    $caminhoPasta . $nomeArquivoSalvo;

if (!move_uploaded_file(
    $_FILES['arquivo']['tmp_name'],
    $caminhoCompleto
)) {

    throw new RuntimeException(
        'Não foi possível salvar o arquivo original.'
    );
}

$caminhoBanco =
    'uploads/analises/' .
    $nomeArquivoSalvo;

$sqlArquivo = "
    UPDATE analises
    SET
        arquivo_caminho = :arquivo_caminho,
        arquivo_mime = :arquivo_mime
    WHERE id_analise = :id_analise
";

$stmtArquivo = $pdo->prepare($sqlArquivo);

$stmtArquivo->execute([
    ':arquivo_caminho' => $caminhoBanco,
    ':arquivo_mime' =>
        $_FILES['arquivo']['type'] ?? null,
    ':id_analise' => $idAnalise
]);

    /* -----------------------------------------------------
       SALVAR ALUNOS DA ANÁLISE
       ----------------------------------------------------- */

    $sqlAluno = "
        INSERT INTO relatorio_temp (
            id_analise,
            id_aluno,
            aluno,
            turma,
            total_faltas,
            status
        )
        VALUES (
            :id_analise,
            NULL,
            :aluno,
            :turma,
            :total_faltas,
            :status
        )
    ";

    $stmtAluno = $pdo->prepare($sqlAluno);


    foreach (
        $resultado['alunos'] as $aluno
    ) {

        $stmtAluno->execute([
            ':id_analise' =>
                $idAnalise,

            ':aluno' =>
                $aluno['nome'],

            ':turma' =>
                $aluno['turma'],

            ':total_faltas' =>
                $aluno['faltas'],

            ':status' =>
                $aluno['status']
        ]);
    }


    /* -----------------------------------------------------
       CONFIRMAR TRANSAÇÃO
       ----------------------------------------------------- */

    $pdo->commit();


} catch (Throwable $erro) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log(
        'Erro ao salvar análise no banco: '
        . $erro->getMessage()
    );

    responder([
        'sucesso' => false,
        'mensagem' =>
            'O arquivo foi processado, mas não foi possível salvar a análise.'
    ], 500);
}

/* =========================================================
   GUARDAR RESULTADO TEMPORARIAMENTE NA SESSÃO
   ========================================================= */

$_SESSION['resultado_alerta'] = [

    'id_sessao' =>
        $idSessao,

    'id_analise' =>
        $idAnalise,

    'arquivo' => [

        'nome' =>
            $nomeArquivo,

        'tamanho' =>
            $tamanhoArquivo,

        'extensao' =>
            $extensao,

        'formato' =>
            $formato
    ],

    'limite' =>
        $limite,

    'total_alunos_lidos' =>
        $resultado['total_lidos'],

    'total_alunos_acima_limite' =>
        count(
            $resultado['alunos']
        ),

    'alunos' =>
        $resultado['alunos']
];

/* =========================================================
   RESPOSTA PARA O DASHBOARD
   ========================================================= */

responder([

    'sucesso' =>
        true,

    'mensagem' =>
        'Arquivo processado com sucesso.',

    'id_sessao' =>
        $idSessao,

    'id_analise' =>
        $idAnalise,

    'arquivo' => [

        'nome' =>
            $nomeArquivo,

        'tamanho' =>
            $tamanhoArquivo,

        'extensao' =>
            $extensao,

        'formato' =>
            $formato,

        'limite' =>
            $limite
    ],

    'total_alunos_lidos' =>
        $resultado['total_lidos'],

    'total_alunos_acima_limite' =>
        count(
            $resultado['alunos']
        ),

    'alunos' =>
        $resultado['alunos']

]);
