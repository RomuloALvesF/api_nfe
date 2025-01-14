<?php

require_once '../vendor/autoload.php';// Inclua o autoload do Composer

use NFePHP\DA\NFe\Danfe;

try {
    // Carregar o XML autorizado
    $xml = file_get_contents('nfe_protocolada.xml');

    // Criar a instância do DANFE
    $danfe = new Danfe($xml);

    // Gerar o PDF
    $pdf = $danfe->render();

    // Salvar o DANFE como arquivo PDF
    file_put_contents('danfe.pdf', $pdf);

    echo "DANFE gerado com sucesso! Arquivo salvo como 'danfe.pdf'.";
} catch (\Exception $e) {
    echo "Erro ao gerar o DANFE: " . $e->getMessage();
}
