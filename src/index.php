<?php

require_once '../vendor/autoload.php';

use NFePHP\NFe\Tools;
use NFePHP\NFe\Make;
use NFePHP\Common\Certificate;
use NFePHP\NFe\Complements;
use NFePHP\NFe\Common\Standardize;

try {
    // Configura��o do certificado digital
    $certificadoPath = 'C:/xampp/htdocs/sped-nfe/certificado/cert.pfx';
    $senhaCertificado = '182838';
    $certificado = Certificate::readPfx(file_get_contents($certificadoPath), $senhaCertificado);

    // Configura��o do ambiente e dados da SEFAZ
    $configJson = json_encode([
        "atualizacao" => date('Y-m-d H:i:s'),
        "tpAmb"       => 2, // Ambiente: 1 = Produ��o, 2 = Homologa��o
        "razaosocial" => "MV SOFT SISTEMAS E TECNOLOGIA LTDA",
        "cnpj"        => "31646541000115",
        "siglaUF"     => "MG",
        "schemes"     => "PL_009_V4",
        "versao"      => '4.00'
    ]);

    $tools = new Tools($configJson, $certificado);
    $tools->model('55'); // Modelo da NFe: 55 = NFe, 65 = NFCe

    // Inst�ncia da NFe
    $make = new Make();

    // Bloco infNFe (obrigat�rio)
    $std = new \stdClass();
    $std->Id = ''; // Deixe vazio, ser� gerado automaticamente
    $std->versao = '4.00'; // Vers�o do layout
    $make->taginfNFe($std);

    // Bloco ide (obrigat�rio)
    $std = new \stdClass();
    $std->cUF = 31; // C�digo da UF (MG = 31)
    $std->cNF = str_pad(random_int(1, 99999999), 8, '0', STR_PAD_LEFT);
    $std->natOp = 'Licenciamento de software';
    $std->mod = 55;
    $std->serie = 2;
    $std->nNF = 104;//numero da nota lembrar de alterar sempre que gerar uma nova nota
    $std->dhEmi = (new \DateTime())->format('Y-m-d\TH:i:sP');
    $std->tpNF = 1; // Tipo de opera��o: 1 = Sa�da
    $std->idDest = 1; // Destino: 1 = Interno
    $std->cMunFG = 3106200; // C�digo do munic�pio do emitente
    $std->tpImp = 1; // Formato do DANFE: 1 = Retrato
    $std->tpEmis = 1; // Tipo de emiss�o: 1 = Normal
    $std->cDV = 2; // D�gito verificador da chave de acesso
    $std->tpAmb = 2; // Ambiente: 1 = Produ��o, 2 = Homologa��o
    $std->finNFe = 1; // Finalidade: 1 = Normal
    $std->indFinal = 1; // Consumidor final: 1 = Sim
    $std->indPres = 1; // Indicador de presen�a: 1 = Opera��o presencial
    $std->procEmi = 0; // Processo de emiss�o: 0 = Contribuinte
    $std->verProc = '4.13'; // Vers�o do processo emissor
    $make->tagide($std);

    // Bloco emit (obrigat�rio)
    $std = new \stdClass();
    $std->xNome = "MV SOFT SISTEMAS E TECNOLOGIA LTDA";
    $std->CNPJ = "31646541000115";
    $std->IE = "0032869840071";
    $std->IM = "11078800019"; // Inscri��o Municipal
    $std->CRT = 1; // 1 = Simples Nacional
    $make->tagemit($std);



    // Bloco enderEmit (obrigat�rio)
    $std = new \stdClass();
    $std->xLgr = "Rua Tefe";
    $std->nro = "491";
    $std->xBairro = "Renascen�a";
    $std->cMun = 3106200; // C�digo do munic�pio (Belo Horizonte)
    $std->xMun = "Belo Horizonte";
    $std->UF = "MG";
    $std->CEP = "31130420";
    $make->tagenderemit($std);

    // Bloco dest (destinat�rio - obrigat�rio)
    $std = new \stdClass();
    $std->xNome = 'ASSOCIACAO DO PESSOAL DA CAIXA ECONOMICA FEDERAL DE MINAS GERAIS - APCEF/MG';
    $std->CNPJ = '17299090000166'; // CNPJ do destinat�rio
    $std->indIEDest = '9'; // 1=Contribuinte ICMS
    $std->IE = ''; // Inscri��o Estadual do destinat�rio
    $make->tagdest($std);

    // Bloco enderDest (endere�o do destinat�rio - obrigat�rio)
    $std = new \stdClass();
    $std->xLgr = 'RUA EXPEDICIONARIO CELSO RACIOPP';
    $std->nro = '145';
    $std->xBairro = 'S�o Lu�z';
    $std->cMun = '3106200'; // C�digo do munic�pio (Belo Horizonte)
    $std->xMun = 'Belo Horizonte';
    $std->UF = 'MG';
    $std->CEP = '31310070';
    $std->cPais = '1058'; // Brasil
    $std->xPais = 'Brasil';
    $make->tagenderDest($std);

    // Bloco prod (obrigat�rio)
    $std = new \stdClass();
    $std->item = 1;
    $std->cProd = "LIC001";
    $std->xProd = "Licenciamento ou cessao de direito de uso de programas de computa��o";
    $std->NCM = "85049090";
    $std->CFOP = "5101";
    $std->uCom = "UN";
    $std->qCom = 1; // Quantidade comercial
    $std->vUnCom = 1500.00; // Valor unit�rio comercial
    $std->vProd = 1500.00; // Valor total do produto
    $std->uTrib = "UN";
    $std->qTrib = 1; // Quantidade tribut�vel deve ser igual � quantidade comercial
    $std->vUnTrib = 1500.00; // Valor unit�rio tribut�vel
    $std->cEAN = "SEM GTIN";
    $std->cEANTrib = "SEM GTIN";
    $std->indTot = 1;
    $make->tagprod($std);

    // Adicionar informa��es do imposto (obrigat�rio)
    $std = new \stdClass();
    $std->item = 1;
    $std->vTotTrib = 38.29;
    $make->tagimposto($std);

    // Adicionar informa��es do ICMS (obrigat�rio)
    $std = new \stdClass();
    $std->item = 1;
    $std->orig = 0;
    $std->CSOSN = '102'; // For Simples Nacional
    $std->pCredSN = 0.00;
    $std->vCredICMSSN = 0.00;
    $make->tagICMSSN($std);

    // Adicionar informa��es do PIS (n�o tributado)
    $std = new \stdClass();
    $std->item = 1; // N�mero do item
    $std->CST = '06'; // C�digo da Situa��o Tribut�ria para opera��o n�o tributada
    $std->vBC = 0.00; // Base de c�lculo (zero)
    $std->pPIS = 0.00; // Al�quota do PIS (zero)
    $std->vPIS = 0.00; // Valor do PIS (zero)
    $make->tagPIS($std);

    // Adicionar informa��es do COFINS (n�o tributado)
    $std = new \stdClass();
    $std->item = 1; // N�mero do item
    $std->CST = '06'; // C�digo da Situa��o Tribut�ria para opera��o n�o tributada
    $std->vBC = 0.00; // Base de c�lculo (zero)
    $std->pCOFINS = 0.00; // Al�quota da COFINS (zero)
    $std->vCOFINS = 0.00; // Valor da COFINS (zero)
    $make->tagCOFINS($std);


    // Adicionar informa��es de transporte (obrigat�rio)
    $std = new \stdClass();
    $std->modFrete = 9; // 9 = Sem frete
    $make->tagtransp($std);

    // Adicionar informa��es de pagamento (obrigat�rio)
    $std = new \stdClass();
    $std->vTroco = 0.00; // Valor do troco
    $make->tagpag($std);

    // Adicionar detalhes do pagamento
    $std = new \stdClass();
    $std->tPag = '99'; // Outros meios de pagamento
    $std->vPag = 1500.00; // Valor do pagamento deve ser igual ao valor total da nota
    $std->xPag = 'Transferencia Bancaria'; // Descri��o do pagamento - obrigat�rio para tPag = 99
    $make->tagdetPag($std);

    // Verificar erros antes de montar o XML
    $erros = $make->getErrors();
    if (!empty($erros)) {
        echo "Erros de valida��o encontrados:\n";
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
        $response = $tools->sefazEnviaLote([$xml], $idLote, 1); // Envio s�ncrono

        // Processar a resposta ok
        $stdCl = new Standardize($response);
        $respObj = $stdCl->toStd();

        // Verificar o status do lote
        if ($respObj->cStat != 104) {
            throw new \Exception(sprintf('Erro no envio do lote: %s - %s', $respObj->cStat, $respObj->xMotivo));
        }

        // Verificar o status do processamento da NFe
        if (!isset($respObj->protNFe)) {
            throw new \Exception('Protocolo da NFe n�o encontrado');
        }

        if ($respObj->protNFe->infProt->cStat != 100) {
            throw new \Exception(sprintf('NFe n�o autorizada: %s - %s', 
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
        echo "Erros encontrados na valida��o do XML:\n";
        print_r($errors);
    }
} catch (\Exception $e) {
    echo "Erro: " . $e->getMessage();
}
