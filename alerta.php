<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="alerta.css">
</head>
<style>
  .modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(2px);
    justify-content: center;
    align-items: center;
    z-index: 9999;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
  }

  .modal-box {
    background: #ffffff;
    border-radius: 16px;
    width: 90%;
    max-width: 480px;
    position: relative;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    overflow: hidden;
  }

  /* Cabeçalho */
  .modal-header {
    background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
    padding: 24px 24px 20px 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
  }

  .header-icon {
    width: 56px;
    height: 56px;
    background: #dbeafe;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #1e40af;
    flex-shrink: 0;
  }

  .header-text h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
    color: #1e1b4b;
  }

  .header-text p {
    margin: 4px 0 0 0;
    font-size: 13px;
    color: #475569;
    line-height: 1.4;
  }

  .modal-close {
    position: absolute;
    top: 16px;
    right: 16px;
    background: transparent;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #64748b;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Corpo do Formulário */
  .modal-body {
    padding: 20px 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .form-label {
    font-size: 13px;
    font-weight: 700;
    color: #1e1b4b;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    color: #334155;
    background-color: #f8fafc;
    box-sizing: border-box;
    outline: none;
    transition: border-color 0.2s;
  }

  .form-control:focus {
    border-color: #6366f1;
    background-color: #ffffff;
  }

  textarea.form-control {
    resize: vertical;
    min-height: 100px;
    font-family: inherit;
    line-height: 1.5;
  }

  /* Banner Informativo */
  .info-banner {
    background-color: #eff6ff;
    border: 1px solid #bfdbfe;
    border-left: 4px solid #3b82f6;
    border-radius: 8px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .info-content {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #1e40af;
  }

  .info-content strong {
    font-weight: 700;
  }

  /* Rodapé */
  .modal-footer {
    padding: 0 24px 20px 24px;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
  }

  .btn-cancel {
    background: #ffffff;
    border: 1px solid #c7d2fe;
    color: #4338ca;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
  }

  .btn-send {
    background: #047857;
    border: none;
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .btn-send:hover {
    background: #065f46;
  }
</style>
<body>
    
<!-- alerta.php -->
<div id="modal-email" class="modal-overlay">
  <div class="modal-box">
    
    <!-- Cabeçalho -->
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
          <textarea id="email-mensagem" class="form-control">Prezada Pedagoga,

Segue a lista de alunos que ultrapassaram o limite de faltas estabelecido. Por favor, verifique e tome as devidas providências.

Atenciosamente,
Sistema de Gestão Escolar</textarea>
        </div>

        <!-- Banner Azul -->
        <div class="info-banner">
          <div class="info-content">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="16" x2="12" y2="12"></line>
              <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <span><strong id="qtd-alunos-modal"><?php echo count($alunos_filtrados ?? []); ?></strong> alunos selecionados para envio de alerta.</span>
          </div>
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.5">
            <path d="M22 2L11 13"></path>
            <path d="M22 2l-7 20-4-9-9-4 20-7z"></path>
          </svg>
        </div>

      </div>

      <!-- Rodapé com botões -->
      <div class="modal-footer">
        <button type="button" class="btn-cancel" onclick="fecharModalEmail()">Cancelar</button>
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

<script src="dados.js"></script>
</body>
</html>
