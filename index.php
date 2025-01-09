<?php
require 'vendor/autoload.php';

use NFePHP\NFe\Make;

try {
    $nfe = new Make();
    echo "Biblioteca SPED-NFe instalada e carregada com sucesso!";
} catch (Exception $e) {
    echo "Erro ao carregar a biblioteca: " . $e->getMessage();
}


  



