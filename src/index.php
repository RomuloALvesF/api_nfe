<?php

require_once '../vendor/autoload.php';

use NFePHP\NFe\Tools;
use NFePHP\NFe\Make;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Complements;
use NFePHP\NFe\Common\Standardize;

try {
    // Configuração do certificado digital
    $certificadoPath = 'C:/xampp/htdocs/sped-nfe/certificado/cert.pfx';
    $senhaCertificado = '182838';
    $certificado = Certificate::readPfx(file_get_contents($certificadoPath), $senhaCertificado);

    // Configuração do ambiente e dados da SEFAZ
    $configJson = json_encode([
        "atualizacao" => date('Y-m-d H:i:s'),
        "tpAmb"       => 2, // Ambiente: 1 = Produção, 2 = Homologação
        "razaosocial" => "MV SOFT SISTEMAS E TECNOLOGIA LTDA",
        "cnpj"        => "31646541000115",
        "siglaUF"     => "MG",
        "schemes"     => "PL_009_V4",
        "versao"      => '4.00'
    ]);

    $tools = new Tools($configJson, $certificado);
    $tools->model('55'); // Modelo da NFe: 55 = NFe, 65 = NFCe

    // Instância da NFe
    $make = new Make();

    // Bloco infNFe (obrigatório)
    $std = new \stdClass();
    $std->Id = ''; // Deixe vazio, será gerado automaticamente
    $std->versao = '4.00'; // Versão do layout
    $make->taginfNFe($std);

    // Bloco ide (obrigatório)
    $std = new \stdClass();
    $std->cUF = 31; // Código da UF (MG = 31)
    $std->cNF = str_pad(random_int(1, 99999999), 8, '0', STR_PAD_LEFT); // Código numérico gerado aleatoriamente
    $std->natOp = 'Venda de mercadoria'; // Natureza da operação
    $std->mod = 55; // Modelo da NFe
    $std->serie = 1; // Série da NFe
    $std->nNF = 100; // Número da NFe
    $std->dhEmi = (new \DateTime())->format('Y-m-d\TH:i:sP'); // Data e hora de emissão
    $std->tpNF = 1; // Tipo de operação: 1 = Saída
    $std->idDest = 1; // Destino: 1 = Interno
    $std->cMunFG = 3106200; // Código do município do emitente
    $std->tpImp = 1; // Formato do DANFE: 1 = Retrato
    $std->tpEmis = 1; // Tipo de emissão: 1 = Normal
    $std->cDV = 2; // Dígito verificador da chave de acesso
    $std->tpAmb = 2; // Ambiente: 1 = Produção, 2 = Homologação
    $std->finNFe = 1; // Finalidade: 1 = Normal
    $std->indFinal = 1; // Consumidor final: 1 = Sim
    $std->indPres = 1; // Indicador de presença: 1 = Operação presencial
    $std->procEmi = 0; // Processo de emissão: 0 = Contribuinte
    $std->verProc = '4.13'; // Versão do processo emissor
    $make->tagide($std);

    // Bloco emit (obrigatório)
    $std = new \stdClass();
    $std->xNome = "MV SOFT SISTEMAS E TECNOLOGIA LTDA";
    $std->CNPJ = "31646541000115";
    $std->IE = "0032869840071";
    $std->CRT = 3; // Código de regime tributário: 3 = Simples Nacional
    $make->tagemit($std);



    // Bloco enderEmit (obrigatório)
    $std = new \stdClass();
    $std->xLgr = "Rua Tefe";
    $std->nro = "491";
    $std->xBairro = "Renascença";
    $std->cMun = 3106200; // Código do município (Belo Horizonte)
    $std->xMun = "Belo Horizonte";
    $std->UF = "MG";
    $std->CEP = "31130420";
    $make->tagenderemit($std);

    // Bloco dest (destinatário - obrigatório)
    $std = new \stdClass();
    $std->xNome = 'EMPRESA TESTE DESTINATARIO LTDA';
    $std->CNPJ = '31646541000115'; // CNPJ do destinatário
    $std->indIEDest = '1'; // 1=Contribuinte ICMS
    $std->IE = '0032869840071'; // Inscrição Estadual do destinatário
    $make->tagdest($std);

    // Bloco enderDest (endereço do destinatário - obrigatório)
    $std = new \stdClass();
    $std->xLgr = 'Rua Teste';
    $std->nro = '123';
    $std->xBairro = 'Centro';
    $std->cMun = '3106200'; // Código do município (Belo Horizonte)
    $std->xMun = 'Belo Horizonte';
    $std->UF = 'MG';
    $std->CEP = '30140000';
    $std->cPais = '1058'; // Brasil
    $std->xPais = 'Brasil';
    $make->tagenderDest($std);

    // Bloco prod (obrigatório)
    $std = new \stdClass();
    $std->item = 1; // Número do item
    $std->cProd = "001"; // Código do produto
    $std->xProd = "Produto Teste"; // Nome do produto
    $std->NCM = "61091000"; // Código NCM
    $std->CFOP = "5102"; // CFOP
    $std->uCom = "UN"; // Unidade comercial
    $std->qCom = 1.0000; // Quantidade comercial
    $std->vUnCom = 100.00; // Valor unitário
    $std->vProd = 100.00; // Valor total
    $std->uTrib = "UN"; // Unidade tributável
    $std->qTrib = 1.0000; // Quantidade tributável
    $std->vUnTrib = 100.00; // Valor unitário tributável
    $std->cEAN = "SEM GTIN"; // Caso o produto não tenha GTIN
    $std->cEANTrib = "SEM GTIN"; // Caso o produto não tenha GTIN
    $std->indTot = 1; // Indicador de totalização no valor da NFe
    $make->tagprod($std);

    // Adicionar informações do imposto (obrigatório)
    $std = new \stdClass();
    $std->item = 1;
    $std->vTotTrib = 0.00;
    $make->tagimposto($std);

    // Adicionar informações do ICMS (obrigatório)
    $std = new \stdClass();
    $std->item = 1;
    $std->orig = 0;
    $std->CST = '00';
    $std->modBC = 0;
    $std->vBC = 100.00;
    $std->pICMS = 18.00;
    $std->vICMS = 18.00;
    $make->tagICMS($std);

    // Adicionar informações do PIS (não tributado)
    $std = new \stdClass();
    $std->item = 1; // Número do item
    $std->CST = '06'; // Código da Situação Tributária para operação não tributada
    $std->vBC = 0.00; // Base de cálculo (zero)
    $std->pPIS = 0.00; // Alíquota do PIS (zero)
    $std->vPIS = 0.00; // Valor do PIS (zero)
    $make->tagPIS($std);

    // Adicionar informações do COFINS (não tributado)
    $std = new \stdClass();
    $std->item = 1; // Número do item
    $std->CST = '06'; // Código da Situação Tributária para operação não tributada
    $std->vBC = 0.00; // Base de cálculo (zero)
    $std->pCOFINS = 0.00; // Alíquota da COFINS (zero)
    $std->vCOFINS = 0.00; // Valor da COFINS (zero)
    $make->tagCOFINS($std);


    // Adicionar informações de transporte (obrigatório)
    $std = new \stdClass();
    $std->modFrete = 9; // 9 = Sem frete
    $make->tagtransp($std);

    // Adicionar informações de pagamento (obrigatório)
    $std = new \stdClass();
    $std->vTroco = 0.00; // Valor do troco
    $make->tagpag($std);

    // Adicionar detalhes do pagamento
    $std = new \stdClass();
    $std->tPag = '01'; // 01=Dinheiro
    $std->vPag = 100.00; // Valor do pagamento
    $make->tagdetPag($std);

    // Verificar erros antes de montar o XML
    $erros = $make->getErrors();
    if (!empty($erros)) {
        echo "Erros de validação encontrados:\n";
        print_r($erros);
        exit();
    }

    // Gerar o XML ok
    if ($make->monta()) {
        $xml = $make->getXML();

        // Assinar o XML ok
        $xml = $tools->signNFe($xml);

        // Enviar para a SEFAZ
        $idLote = str_pad(1, 15, '0', STR_PAD_LEFT); // Identificador do lote
        $response = $tools->sefazEnviaLote([$xml], $idLote, 1); // Envio síncrono

        // Processar a resposta ok
        $stdCl = new Standardize($response);
        $respObj = $stdCl->toStd();

        // Verificar o status do lote
        if ($respObj->cStat != 104) {
            throw new \Exception(sprintf('Erro no envio do lote: %s - %s', $respObj->cStat, $respObj->xMotivo));
        }

        // Verificar o status do processamento da NFe
        if (!isset($respObj->protNFe)) {
            throw new \Exception('Protocolo da NFe não encontrado');
        }

        if ($respObj->protNFe->infProt->cStat != 100) {
            throw new \Exception(sprintf('NFe não autorizada: %s - %s', 
                $respObj->protNFe->infProt->cStat,
                $respObj->protNFe->infProt->xMotivo
            ));
        }

        // Salvar o XML autorizado
        $authorizedXml = Complements::toAuthorize($xml, $response);
        file_put_contents('nfe_protocolada.xml', $authorizedXml);

        echo "NFe gerada e autorizada com sucesso!";
    } else {
        // Depurar os erros encontrados no XML
        $errors = $make->getErrors();
        echo "Erros encontrados na validação do XML:\n";
        print_r($errors);
    }
} catch (\Exception $e) {
    echo "Erro: " . $e->getMessage();
}
