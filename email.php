<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Usa __DIR__ para pegar o caminho exato do projeto no seu computador
require __DIR__ . '/PHPMailer/Exception.php';
require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailDestino = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $assunto      = $_POST['assunto'] ?? 'Alerta de Faltas';
    $mensagemBase = $_POST['mensagem'] ?? '';
    $alunosJson   = $_POST['alunos'] ?? '[]';
    
    $alunos = json_decode($alunosJson, true);

    if (!$emailDestino) {
        echo "Por favor, informe um e-mail válido.";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // Configuração do Servidor SMTP (Exemplo: Gmail)
//EMAIL REMETENTE. MUDAR FUTURAMENTE PARA UM EMAIL DA ESCOLA
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sophia.faria@aluno.edu.es.gov.br';    
        $mail->Password   = 'pwgw faoe tedh qmcz';        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // Remetente e Destinatário
        $mail->setFrom('sophia.faria@aluno.edu.es.gov.br', 'Sistema Escolar');
        $mail->addAddress($emailDestino);

        // Corpo da mensagem
        $corpoHtml = "<h2>" . htmlspecialchars($assunto) . "</h2>";
        $corpoHtml .= "<p>" . nl2br(htmlspecialchars($mensagemBase)) . "</p>";

        if (!empty($alunos)) {
            $corpoHtml .= "<h3>Alunos Selecionados:</h3><ul>";
            foreach ($alunos as $aluno) {
                $corpoHtml .= "<li>" . htmlspecialchars($aluno) . "</li>";
            }
            $corpoHtml .= "</ul>";
        }

        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body    = $corpoHtml;

        $mail->send();
        echo "E-mail enviado com sucesso para " . $emailDestino . "!";
    } catch (Exception $e) {
        echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
    }
}
?>
