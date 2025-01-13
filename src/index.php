<?php
require __DIR__ . '/../vendor/autoload.php';

use NFePHP\Common\Certificate;

$certificadoPath = 'C:/xampp/htdocs/sped-nfe/certificado/cert.pfx';
$senhaCertificado = '182838';

try {
    // Configurar contexto SSL para leitura
    $streamContext = stream_context_create([
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ]);

    // Ler o certificado com contexto SSL
    $certificado = Certificate::readPfx(file_get_contents($certificadoPath, false, $streamContext), $senhaCertificado);
    echo "Certificado digital carregado com sucesso!";
} catch (Exception $e) {
    echo "Erro ao carregar o certificado digital: " . $e->getMessage();
}




