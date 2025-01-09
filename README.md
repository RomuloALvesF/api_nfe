DOCUMENTAÇÃO API NFE:

Servidor Xampp!
Estrutura:

proj_nfe/
├── certificado/
│   └── certificado.pfx  (Certificado digital da sua empresa)
├── Dockerfile //não usado
├── docker-compose.yml//não usado
├── vendor/
├── composer.json
├── testa_nfe.php


Biblioteca baixada via composer no CMD: 
cd C:\xampp\htdocs\proj_nfe
composer require nfephp-org/sped-nfe
