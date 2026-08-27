<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="alerta.css">
</head>

<body>
    
<!-- alerta.php -->
<div class="modal-footer" style="display: flex; align-items: center; justify-content: flex-end; gap: 10px;">
        
        <!-- Campo para anexar arquivo -->
        <label for="email-anexo" class="btn-cancel" style="cursor: pointer; display: flex; align-items: center; gap: 6px; margin: 0; background-color: #f8fafc;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
          </svg>
          Anexar Arquivo
        </label>
        <input type="file" id="email-anexo" accept=".pdf, .xlsx, .xls, .csv" style="display: none;" onchange="mostrarNomeArquivo(this)">
        
        <!-- Exibe o nome do arquivo selecionado -->
        <span id="nome-arquivo-anexo" style="font-size: 12px; color: #475569; max-width: 130px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"></span>

        <button type="button" class="btn-cancel" onclick="fecharModalEmail()">Cancelar</button>
        <button type="submit" class="btn-send">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 2L11 13"></path>
            <path d="M22 2l-7 20-4-9-9-4 20-7z"></path>
          </svg>
          Enviar Agora
        </button>
      </div>

<script src="script.js"></script>
</body>
</html>
