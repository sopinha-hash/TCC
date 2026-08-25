//enviar o email
function abrirModalEmail() {
  const modal = document.getElementById('modal-email');
  if (modal) {
    modal.style.display = 'flex'; // Força o modal a aparecer centralizado
  }
}

function fecharModalEmail() {
  const modal = document.getElementById('modal-email');
  if (modal) {
    modal.style.display = 'none'; // Esconde o modal
  }
}

function enviarEmailAlerta(event) {
  event.preventDefault();
  
  const emailDestino = document.getElementById('email-destino').value;

  // Envia os dados para o arquivo PHP no servidor
  fetch('enviar_email.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'email=' + encodeURIComponent(emailDestino)
  })
  .then(response => response.text())
  .then(resposta => {
    alert(resposta); // Exibe o alerta com a resposta real do PHP
    fecharModalEmail();
  })
  .catch(error => {
    alert('Ocorreu um erro ao tentar enviar o e-mail.');
    console.error(error);
  });
}
