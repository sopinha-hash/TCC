<?php
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'alerta_faltas');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');

    $conexao = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conexao->connect_error) {
        die('<p>Erro ao conectar ao banco de dados: ' 
            . $conexao->connect_error . '</p>');
}
?>
