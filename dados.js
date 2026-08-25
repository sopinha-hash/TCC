let limite = 100;

function alterarLimite(valor) {
    limite += valor;

    if (limite < 0) {
        limite = 0;
    }

    document.getElementById("limite").textContent = limite;
    }

    document.getElementById('input-pesquisa').addEventListener('input', function () {

// Pega o termo digitado e converte para minúsculas
    const termoBusca = this.value.toLowerCase().trim();
    
// Pega todas as linhas de alunos da tabela
    const linhas = document.querySelectorAll('#tabela-alunos-body tr');

    linhas.forEach(linha => {

// Pega a coluna do nome (segunda coluna <td> da tabela)
    const colunaNome = linha.children[1];

    if (colunaNome) {
        const nomeAluno = colunaNome.textContent.toLowerCase();

// Exibe a linha se o nome contiver o texto digitado, senão esconde
    if (nomeAluno.includes(termoBusca)) {
        linha.style.display = '';
        } else {
        linha.style.display = 'none';
        }
    }
    });
});

// Função para baixar a lista completa de alunos
function baixarLista(formato) {
  if (formato === 'excel') {
    // --- LÓGICA PARA EXCEL (CSV) ---
    const linhas = document.querySelectorAll('.students-table tr');
    let conteudoCSV = '';

    linhas.forEach((linha) => {
      if (linha.style.display === 'none') return; // Pula invisíveis pelo filtro

      const colunas = linha.querySelectorAll('th, td');
      const dadosLinha = [];

      colunas.forEach((coluna, index) => {
        if (index === 0) return; // Pula checkbox
        let texto = coluna.innerText.replace(/\n/g, ' ').trim();
        dadosLinha.push(`"${texto}"`);
      });

      if (dadosLinha.length > 0) {
        conteudoCSV += dadosLinha.join(';') + '\n';
      }
    });

    const blob = new Blob(['\uFEFF' + conteudoCSV], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'lista_alunos.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);

  } else if (formato === 'pdf') {
    // --- LÓGICA PARA PDF ---
    const tabela = document.querySelector('.table-container');

    // Configurações do PDF
    const opcoes = {
      margin:       10,
      filename:     'lista_alunos.pdf',
      image:        { type: 'jpeg', quality: 0.98 },
      html2canvas:  { scale: 2 },
      jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };

    // Gera e baixa o arquivo
    html2pdf().set(opcoes).from(tabela).save();
  }
}

//baixa o pdf
function gerarPDF() {
  // Seleciona a tabela diretamente
  const tabela = document.querySelector('.students-table');

  // Cria um container temporário sem limites de altura/scroll
  const containerTemp = document.createElement('div');
  containerTemp.style.padding = '20px';
  containerTemp.style.background = '#ffffff';

  // Opcional: Adiciona um título ao PDF gerado
  containerTemp.innerHTML = `
    <h2 style="font-family: sans-serif; color: #1e3a8a; margin-bottom: 15px;">
      Lista de Alunos - Faltas Escolares
    </h2>
  `;

  // Clona a tabela para não afetar o layout da tela
  const tabelaClonada = tabela.cloneNode(true);
  
  // Garante que a tabela clonada ocupe 100% e não quebre linhas no meio
  tabelaClonada.style.width = '100%';
  tabelaClonada.style.borderCollapse = 'collapse';
  
  containerTemp.appendChild(tabelaClonada);
  document.body.appendChild(containerTemp);

  const opcoes = {
    margin:       [10, 10, 10, 10],
    filename:     'lista_alunos.pdf',
    image:        { type: 'jpeg', quality: 0.98 },
    html2canvas:  { scale: 2, scrollY: 0 },
    jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
    pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] }
  };

  // Gera o PDF e remove o container temporário em seguida
  html2pdf()
    .set(opcoes)
    .from(containerTemp)
    .save()
    .then(() => {
      document.body.removeChild(containerTemp);
    });
}

