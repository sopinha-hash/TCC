<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta de Faltas</title>
    <link rel="stylesheet" href="alerta.css">
</head>
<body>

    <!-- MODAL DE ENVIAR E-MAIL -->
    <div id="modal-email" class="modal-overlay" style="display: none;">
      <div class="modal-box">
        
        <!-- Cabeçalho do Modal -->
        <div class="modal-header">
          <button type="button" class="modal-close" onclick="fecharModalEmail()">&times;</button>
          <div class="header-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
              <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
          </div>
          <div class="header-text">
            <h3>Enviar Alerta por E-mail</h3>
            <p>Envie um e-mail de alerta para a pedagoga sobre os alunos selecionados.</p>
          </div>
        </div>

        <!-- Formulário -->
        <form id="form-enviar-email" onsubmit="enviarEmailAlerta(event)">
          <div class="modal-body">
            
            <!-- Para -->
            <div class="form-group">
              <label class="form-label">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4338ca" stroke-width="2">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                  <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Para (Pedagoga)
              </label>
              <input type="email" id="email-destino" class="form-control" value="pedagoga@escola.com" required>
            </div>

            <!-- Assunto -->
            <div class="form-group">
              <label class="form-label">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4338ca" stroke-width="2">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                  <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                Assunto
              </label>
              <input type="text" id="email-assunto" class="form-control" value="Alerta de Faltas – Alunos Acima do Limite" required>
            </div>

            <!-- Mensagem -->
            <div class="form-group">
              <label class="form-label">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4338ca" stroke-width="2">
                  <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                  <polyline points="14 2 14 8 20 8"></polyline>
                  <line x1="16" y1="13" x2="8" y2="13"></line>
                  <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                Mensagem
              </label>
              <textarea id="email-mensagem" class="form-control" rows="4">Prezada Pedagoga,

Segue a lista de alunos que ultrapassaram o limite de faltas estabelecido. Por favor, verifique e tome as devidas providências.

Atenciosamente,
Sistema de Gestão Escolar</textarea>
            </div>

            <!-- Banner Azul com quantidade -->
            <div class="info-banner">
              <div class="info-content">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                  <circle cx="12" cy="12" r="10"></circle>
                  <line x1="12" y1="16" x2="12" y2="12"></line>
                  <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span>
                  <strong id="qtd-alunos-modal">0</strong> alunos selecionados para envio de alerta.
                </span>
              </div>
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.5">
                <path d="M22 2L11 13"></path>
                <path d="M22 2l-7 20-4-9-9-4 20-7z"></path>
              </svg>
            </div>

          </div> <!-- Fim modal-body -->

          <!-- Rodapé com botões internos do modal -->
          <div class="modal-footer" style="display: flex; align-items: center; justify-content: flex-end; gap: 10px; padding: 15px 20px;">
            
            <!-- Botão de Anexo -->
            <label for="email-anexo" class="btn-cancel" style="cursor: pointer; display: flex; align-items: center; gap: 6px; margin: 0; background-color: #f8fafc;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
              </svg>
              Anexar Arquivo
            </label>
            <input type="file" id="email-anexo" accept=".pdf, .xlsx, .xls, .csv" style="display: none;" onchange="mostrarNomeArquivo(this)">
            
            <!-- Exibe nome do arquivo selecionado -->
            <span id="nome-arquivo-anexo" style="font-size: 12px; color: #475569; max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></span>

            <!-- Botão Cancelar -->
            <button type="button" class="btn-cancel" onclick="fecharModalEmail()">Cancelar</button>
            
            <!-- Botão Enviar -->
            <button type="submit" class="btn-send">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 2L11 13"></path>
                <path d="M22 2l-7 20-4-9-9-4 20-7z"></path>
              </svg>
              Enviar Agora
            </button>
          </div>

        </form>
      </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
