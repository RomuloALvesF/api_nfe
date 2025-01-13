DOCUMENTAÇÃO API NFE:<br>

Servidor Xampp!<br>
Estrutura:<br>

proj_nfe/<br>
├── certificado/<br>
│   └── cert.pfx  (Certificado digital)<br>
├── Dockerfile //não usado<br>
├── docker-compose.yml//não usado<br>
├── vendor/<br>
│       └── arquivos de configuração em geral<br>
├── composer.json<br>
├── src<br>
│     └── index.php  (Carregamento com biblioteca nfe)<br>


Biblioteca baixada via composer no CMD:<br>
Repositorio original: https://github.com/nfephp-org/sped-nfe<br>
cd C:\xampp\htdocs\proj_nfe<br>
composer require nfephp-org/sped-nfe<br>
