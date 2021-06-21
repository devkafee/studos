## Instalação
    git clone https://github.com/devkafee/studos.git
    cd studos/
    composer install
    php artisan migrate
    sudo chmod -R 755 storage/*
    docker-compose up -d --build

## Tests de Featured
    php artisan test

#### Resultado esperado:
    PASS  Tests\Feature\ShortnerTest
    ✓ add url success
    ✓ add url error 404
    ✓ get url success
    ✓ get url error

## Tecnologias
    - PHP 7.4
    - Laravel 8
    - MariaDB 10.5

## Coleção POSTMAN
    https://documenter.getpostman.com/view/363344/TzeZFn6q

## Dificuldades
Encontrei alguns obstáculos em decorrência do meu ambiente de desenvolvimento estar formatado com os recursos e permissões específicas para o meu trabalho atual.Utilizo uma máquina com Linux Mint, com permissões e grupos de usuários bastante específicos.Para conseguir organizar as permissões de acesso do docker aos volumes (tanto da aplicação / quanto ao banco) e posteriormente o acesso da aplicação ao banco, eu acabei usando quase 80% do meu tempo disponível.

## Configurações
No repositório do projeto, eu estou enviando o arquivo .env para alteração de acordo com o seu ambiente.
Segue abaixo os pontos onde recomendo modificar de acordo com as portas e URLs disponíveis.

    - docker-compose.yml
        - Linha 9: Portas do banco de dados, atual: 9906
        - Linha 36: Portas da aplicação, atual: 8200

    - .env
        - Linha 5: Url da aplicação, atual: localhost:8200
        - Linhas 11/12: Configurações do banco de dados de acordo com as informações do Docker.

## Sobre
    - Hashids
        => Essa biblioteca fornece um padrão de criptografia de duas vias, onde, com base em um inteiro ele gera um hash.
        Eu utilizo o ID da linha do banco, para poder criar o hash para a URL.
        Desta forma, eu não preciso me preocupar em validar no banco de dados se determinada hash já existe, e não preciso fazer processos manuais para 'encode' e 'decode'.
        É uma biblioteca que eu já utilizo há mtos anos para não expor IDs do banco para o usuário.

    - Validação da URL
        => O Laravel dispõe de um método de validação de url que utiliza a função nativa do PHP dns_get_record que verifica se a URL informada é válida e se o DNS está ativo.
        Isso 'economiza' em tempo de desenvolvimento, e aplica uma camada a mais de segurança.
        
        => A minha primeira opção seria deixar a coluna da URL como unique. Estou permitindo que a mesma URL possa ser cadastrada mais de uma vez, talvez por usuários diferentes, com métricas e prazos de expiração diferentes.

    - Tempo de expiração da URL
        => No arquivo .env existe um parametro chamado 'expire_limit' que é definido em dias. O padrão são 7 dias, setados no arquivo config/app.php

    - Aviso de Segurança
        => De acordo com a documentação, não foi solicitado que fosse usado a rota de API, e o grupo de rotas web EXIGE que seja enviado CSRF token, porém, pelo postman isso não é possível. Então eu comentei o middleware de CSRF, mas não recomendo que isso seja feito em produção. app/Http/Kernel.php:38

    - Resposta
        => Para o cadastramento da URL, como a requisição está como POST, não faz sentido retornar uma view uma vez que a requisição terá que ser Rest.