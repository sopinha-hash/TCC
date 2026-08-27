let limite = 100;

function alterarLimite(valor) {
  limite += valor;

  if (limite < 0) {
    limite = 0;
  }

  const elLimite = document.getElementById("limite");
  if (elLimite) {
    elLimite.textContent = limite;
  }
}

// Evento de Pesquisa
const inputPesquisa = document.getElementById('input-pesquisa');
if (inputPesquisa) {
  inputPesquisa.addEventListener('input', function () {
    const termoBusca = this.value.toLowerCase().trim();
    const linhas = document.querySelectorAll('#tabela-alunos-body tr');

    linhas.forEach(linha => {
      const colunaNome = linha.children[1];
      if (colunaNome) {
        const nomeAluno = colunaNome.textContent.toLowerCase();
        if (nomeAluno.includes(termoBusca)) {
          linha.style.display = '';
        } else {
          linha.style.display = 'none';
        }
      }
    });
  });
}

// --- FUNÇÕES DO MODAL/POP-UP ---
function abrirModalEmail() {
  atualizarContadorAlunos();
  const modal = document.getElementById('modal-email');
  if (modal) {
    modal.style.setProperty('display', 'flex', 'important');
  }
}

function fecharModalEmail() {
  const modal = document.getElementById('modal-email');
  if (modal) {
    modal.style.setProperty('display', 'none', 'important');
  }

  // Reset do Formulário (Limpa campos de texto, e-mail e textarea)
  const form = document.getElementById('form-enviar-email');
  if (form) {
    form.reset();
  }

  // Limpa o arquivo anexo
  const campoAnexo = document.getElementById('email-anexo');
  if (campoAnexo) {
    campoAnexo.value = '';
  }

  // Limpa o nome do arquivo que fica visível na tela
  const spanArquivo = document.getElementById('nome-arquivo-anexo');
  if (spanArquivo) {
    spanArquivo.textContent = '';
  }
}

function enviarEmailAlerta(event) {
  event.preventDefault();

  const formData = new FormData();
  formData.append('email', document.getElementById('email-destino').value);
  formData.append('assunto', document.getElementById('email-assunto').value);
  formData.append('mensagem', document.getElementById('email-mensagem').value);

  // Captura o input do arquivo
  const campoAnexo = document.getElementById('email-anexo');
  if (campoAnexo && campoAnexo.files[0]) {
    formData.append('anexo', campoAnexo.files[0]);
  }

  fetch('email.php', {
    method: 'POST',
    body: formData // Importante: não definir headers manuais de Content-Type aqui
  })
  .then(response => response.text())
  .then(resposta => {
    alert(resposta);
    fecharModalEmail();
  })
  .catch(error => {
    alert('Erro ao enviar e-mail.');
    console.error(error);
  });
}

  // Envia via AJAX incluindo a lista de alunos selecionados
  fetch('email.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `email=${encodeURIComponent(emailDestino)}&assunto=${encodeURIComponent(assunto)}&mensagem=${encodeURIComponent(mensagem)}&alunos=${encodeURIComponent(JSON.stringify(alunosMarcados))}`
  })
  .then(response => response.text())
  .then(resposta => {
    alert(resposta);
    fecharModalEmail();
  })
  .catch(error => {
    alert('Erro ao enviar e-mail.');
    console.error(error);
  });


// --- FUNÇÃO PARA BAIXAR EXCEL ---
function baixarLista(formato) { 
  if (formato === 'excel') {
    const linhas = document.querySelectorAll('.students-table tr');
    let conteudoCSV = '';

    linhas.forEach((linha) => {
      if (linha.style.display === 'none') return;

      const colunas = linha.querySelectorAll('th, td');
      const dadosLinha = [];

      colunas.forEach((coluna, index) => {
        if (index === 0) return;
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
    gerarPDF();
  }
}

// --- FUNÇÃO PARA GERAR PDF ---
function gerarPDF() {
  const tabela = document.querySelector('.students-table');
  if (!tabela) return;

  const containerTemp = document.createElement('div');
  containerTemp.style.padding = '20px';
  containerTemp.style.background = '#ffffff';

  containerTemp.innerHTML = `
    <h2 style="font-family: sans-serif; color: #1e3a8a; margin-bottom: 15px;">
      Lista de Alunos - Faltas Escolares
    </h2>
  `;

  const tabelaClonada = tabela.cloneNode(true);
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

  html2pdf()
    .set(opcoes)
    .from(containerTemp)
    .save()
    .then(() => {
      document.body.removeChild(containerTemp);
    });
}

// Função para atualizar o número de alunos selecionados no modal
function atualizarContadorAlunos() {
  const checkboxes = document.querySelectorAll('#tabela-alunos-body input[type="checkbox"]');
  let selecionados = 0;

  checkboxes.forEach(chk => {
    const linha = chk.closest('tr');
    if (chk.checked && linha && linha.style.display !== 'none') {
      selecionados++;
    }
  });

  const elContador = document.getElementById('qtd-alunos-modal');
  if (elContador) {
    elContador.textContent = selecionados;
  }
}

// Listener para escutar as mudanças nos checkboxes da tabela
document.addEventListener('DOMContentLoaded', () => {
  const tabela = document.getElementById('tabela-alunos-body');
  if (tabela) {
    tabela.addEventListener('change', (e) => {
      if (e.target.type === 'checkbox') {
        atualizarContadorAlunos();
      }
    });
  }
});

//enviar arquivo para email
function mostrarNomeArquivo(input) {
  const span = document.getElementById('nome-arquivo-anexo');
  if (input.files && input.files[0]) {
    span.textContent = input.files[0].name;
  } else {
    span.textContent = '';
  }
}
