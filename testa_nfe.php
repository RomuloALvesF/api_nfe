<?php
require 'vendor/autoload.php';

use NFePHP\NFe\Make;
use NFePHP\NFe\Tools;
use NFePHP\Common\Certificate;
use NFePHP\Common\Standardize;

// Configurações básicas
$configJson = json_encode([
    "atualizacao" => "2025-01-01 00:00:00",
    "tpAmb" => 2, // 2 = Homologação
    "razaosocial" => "Empresa Teste LTDA",
    "cnpj" => "12345678000100",
    "IE" => "123456789",
    "UF" => "SP",
    "certPfx" => __DIR__ . '/certificado/certificado.pfx',
    "certPassword" => "senha123", //senha do certificado
    "proxy" => [
        "host" => "",
        "port" => "",
        "user" => "",
        "pass" => ""
    ]
]);

try {
    // Carregar o certificado digital
    $certificate = Certificate::readPfx(__DIR__ . '/certificado/certificado.pfx', 'senha123');//senha do certificado

    // Configurar as ferramentas da NF-e
    $tools = new Tools($configJson, $certificate);

    // Instanciar o gerador de XML
    $nfe = new Make();

    // Testar geração simples
    echo "Biblioteca SPED-NFe configurada e funcionando!";
} catch (Exception $e) {
    // Exibir qualquer erro ocorrido
    echo "Erro: " . $e->getMessage();
}
?>
