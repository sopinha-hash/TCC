<?php
// MUDAR QUANDO FOR ENVIAR A PARTE DA BIA!!!!!!!!!!
$alunos = [
    ["nome" => "Ana Clara Silva Souza", "turma" => "1º A", "faltas" => 18, "justificadas" => 3],
    ["nome" => "Bruno Matheus Lima", "turma" => "1º B", "faltas" => 16, "justificadas" => 5],
    ["nome" => "Carlos Eduardo Pereira", "turma" => "2º A", "faltas" => 15, "justificadas" => 0],
    ["nome" => "Eduarda Fernandes Costa", "turma" => "1º A", "faltas" => 14, "justificadas" => 2],
    ["nome" => "Gabriel Henrique Martins", "turma" => "2º B", "faltas" => 13, "justificadas" => 1],
    ["nome" => "Isabela Victoria Ramos", "turma" => "3º A", "faltas" => 12, "justificadas" => 4],
];

// Captura a turma selecionada na URL
$turma_selecionada = $_GET['turma'] ?? '';

// Extrai as turmas únicas para o <select>
$turmas_disponiveis = array_unique(array_column($alunos, 'turma'));

// Faz a ordenação dos filtros
$ordenacao = $_GET['ordenacao'] ?? '';
if ($ordenacao === 'maior_faltas') {
    usort($alunos, function($a, $b) {
        return $b['faltas'] <=> $a['faltas'];
    });
} elseif ($ordenacao === 'menor_faltas') {
    usort($alunos, function($a, $b) {
        return $a['faltas'] <=> $b['faltas'];
    });
} elseif ($ordenacao === 'alfabetica') {
    usort($alunos, function($a, $b) {
        return strcmp($a['nome'], $b['nome']);
    });
}

// Aplica o filtro de turma
$alunos_filtrados = array_filter($alunos, function($aluno) use ($turma_selecionada) {
    if ($turma_selecionada === '') return true;
    return $aluno['turma'] === $turma_selecionada;
});
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="dados.css">
  <link rel="dados.js" href="dados.js">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <title>Sistema de Alerta de Faltas Escolares</title>
</head>

<body>
<img class="logo" src="../TCC26/img/brasao.png" alt="Brasão ASN">

<header class="header">
  <div class="header-content">
    <div class="header-title">
      Sistema de Alerta<br>
      de <span>Faltas Escolares</span>
    </div>
  </div>
</header>

<main class="container">

  <!-- Card Principal -->
  <div class="card">
    
    <!-- Título da Seção -->
    <div class="section-title">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line>
        <line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line>
        <line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line>
        <line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line>
        <line x1="17" y1="16" x2="23" y2="16"></line>
      </svg>
      <span>LISTA DE ALUNOS ACIMA DO LIMITE</span>
    </div>

    <!-- Caixa de Alerta Vermelha (Valores Dinâmicos) -->
    <div class="alert-banner">
      <div class="alert-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
          <line x1="12" y1="9" x2="12" y2="13"></line>
          <line x1="12" y1="17" x2="12.01" y2="17"></line>
        </svg>
      </div>
      <div class="alert-text">
        <h2><span id="total-alunos"><?php echo count($alunos_filtrados); ?></span> alunos encontrados acima do limite de faltas.</h2>
        <p>Limite estabelecido: <strong id="limite-atual">10</strong> faltas</p>
      </div>
    </div>

    <!-- Barra de Filtros com Envio Automático -->
    <form method="GET" action="" class="controls-bar">

<div class="search-box">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>
  </svg>
  <input type="text" id="input-pesquisa" placeholder="Pesquisar aluno...">
</div>

        <!-- Select Dinâmico de Turmas -->
<select class="custom-select" name="turma" onchange="this.form.submit()">
  <option value="">Todas as turmas</option>
    <?php foreach ($turmas_disponiveis as $turma): ?>
      <option value="<?php echo $turma; ?>" <?php echo ($turma_selecionada === $turma) ? 'selected' : ''; ?>>
        <?php echo $turma; ?>
  </option>
        <?php endforeach; ?>
</select>

<select class="custom-select" id="ordem-faltas" name="ordenacao" onchange="this.form.submit()">
      <option value="">Filtros</option>
      <option value="maior_faltas" <?php echo ($ordenacao === 'maior_faltas') ? 'selected' : ''; ?>>
        Maior Quantidade 
      </option>
      <option value="menor_faltas" <?php echo ($ordenacao === 'menor_faltas') ? 'selected' : ''; ?>>
        Menor Quantidade
      </option>
      <option value="alfabetica" <?php echo ($ordenacao === 'alfabetica') ? 'selected' : ''; ?>>
        Ordem Alfabética (A-Z)
      </option>
    </select>

      </div>
    </form>

    <!-- Tabela populada pelo PHP -->
<div class="table-container">
  <table class="students-table">
    <thead>
      <tr>
        <th width="40"><input type="checkbox" id="check-all-table"></th>
        <th>Nome do Aluno</th>
        <th>Turma</th>
      <div class="students-center">
        <th class="text-center">Total de Faltas</th>
        <th class="text-center">Faltas Justificadas</th>
      </div>
      </tr>
    </thead>
    <tbody id="tabela-alunos-body">
      <?php if (empty($alunos_filtrados)): ?>
        <tr>
          <td colspan="6" class="text-center">Nenhum aluno encontrado para esta turma.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($alunos_filtrados as $aluno): ?>
          <tr>
            <td><input type="checkbox" checked></td>
            <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
            <td><?php echo htmlspecialchars($aluno['turma']); ?></td>
            <td class="text-center highlight-red"><?php echo $aluno['faltas']; ?></td>
            <td class="text-center"><?php echo $aluno['justificadas']; ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

  </div>

  <!-- Botões de Ação -->
  <div class="footer-actions">
    <button class="btn-outline" onclick="history.back()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline>
      </svg> VOLTAR PARA INÍCIO
    </button>

    <div class="right-actions">
<div class="download-group" style="display: inline-flex; gap: 8px;">
  <button type="button" class="btn-outline" onclick="baixarLista('excel')">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
      <polyline points="7 10 12 15 17 10"></polyline>
      <line x1="12" y1="15" x2="12" y2="3"></line>
    </svg>
    EXCEL
  </button>

  <button type="button" class="btn-outline" onclick="baixarLista('pdf')">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
      <polyline points="14 2 14 8 20 8"></polyline>
    </svg>
    PDF
  </button>
</div>

      <button class="btn-primary" id="btn-enviar-email">
        <div class="btn-icon">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
            <polyline points="22,6 12,13 2,6"></polyline>
          </svg>
        </div>
        <div class="btn-text">
          <span>ENVIAR E-MAIL DE ALERTA</span>
          <small>Gerar arquivo com alunos selecionados</small>
        </div>
      </button>
    </div>
  </div>

</main>

</body>
<script src="dados.js"></script>
</html>
