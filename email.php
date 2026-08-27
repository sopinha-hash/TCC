<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
        // Configurações SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sophia.faria@aluno.edu.es.gov.br';       
        $mail->Password   = 'pwgw faoe tedh qmcz';           
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port       = 587;                          
        $mail->Timeout    = 15;

        // Ignora falhas na verificação do certificado (necessário em redes escolares/locais)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true
            )
        );

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

        // Anexa o arquivo
        if (isset($_FILES['anexo']) && $_FILES['anexo']['error'] === UPLOAD_ERR_OK) {
            $caminhoTemp = $_FILES['anexo']['tmp_name'];
            $nomeOriginal = $_FILES['anexo']['name'];
            $mail->addAttachment($caminhoTemp, $nomeOriginal);
        }

        $mail->send();
        echo "E-mail enviado com sucesso para " . $emailDestino . "!";
    } catch (Exception $e) {
        echo "Erro ao enviar e-mail: {$mail->ErrorInfo}";
    }
}
?>
