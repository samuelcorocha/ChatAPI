<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# ChatAPI

## O ChatAPI

O sistema do ChatApi, é um conjunto de APIs, que atuam como um mecanismo de gerenciamento de mensagens tanto de forma privada quanto compartilhada em comunidade, permitindo a criação de usuários e salas para a realização de conversas. O sistema foi criado como trabalho da disciplina de redes de computadores, no 6° período de Engenharia de Computação na Pontifícia Universidade Católica de Minas Gerais.

## O Laravel

Laravel é um framework de aplicação web com sintaxe expressiva e elegante. Acreditamos que o desenvolvimento deve ser uma experiência agradável e criativa para ser verdadeiramente gratificante. O Laravel facilita o desenvolvimento, facilitando tarefas comuns usadas em muitos projetos web, como (Laravel, 2024):

- [Mecanismo de roteamento simples e rápido](https://laravel.com/docs/routing).
- [Contêiner poderoso de injeção de dependência](https://laravel.com/docs/container).
- Vários back-ends para [sessões](https://laravel.com/docs/session) e [cache](https://laravel.com/docs/cache) storage.
- Expressivo, intuitivo [database ORM](https://laravel.com/docs/eloquent).
- Agnóstico de banco de dados [schema migrations](https://laravel.com/docs/migrations).
- [Processamento robusto de trabalhos em segundo plano](https://laravel.com/docs/queues).
- [Transmissão de eventos em tempo real](https://laravel.com/docs/broadcasting).

Laravel é acessível, poderoso e fornece as ferramentas necessárias para aplicações grandes e robustas.

### O Sistema

A estrutura da API se divide em algumas partes. Através das _routes_, podemos acessar as funções da API. Por exemplo:

```
/user/login 
```

Essas funções são acessadas nos _Controllers_, mas antes disso, a requisiçãp pode ser validada pela _Middleware_ de autenticação ou pela validação de requisição, encontrada em _Requests_.

Dentro do Controller, as regras de negócio poderão ser encontradas, além das consultas a _Model_, permitindo a conectividade com o Banco de Dados.

## A Instalação

Para a instalação do sistema, esteja ciente que será necessário a utilização do _software_ [Docker](https://www.docker.com/), para a execução do sistema, pois, para o funcionamento foi utilizado containers de uso padrão disponibilizado pelo próprio _Framework_ do Laravel, sendo ele o Laravel Sail.

Para o caso do Sistema Operacional de uso seja Windows, será necessário uma quantidade de passos a mais para seu funcionamento, mas para o caso do uso de Linux pule para o passo 3.

#### Passo 1: Instalando o [WSL para o _Windows_](https://learn.microsoft.com/pt-br/windows/wsl/install)

O WSL (Subsistema do Windows para Linux) permite que os desenvolvedores instalem uma distribuição do Linux e usem aplicativos, utilitários e ferramentas de linha de comando bash do Linux diretamente no Windows.

Para que a instalação seja possível, é possível utilizar dois caminhos, o primeiro é através da linha de comando:

```
wsl --install
```
ou através da instalação do pacote de atualização do kernel do Linux do WSL2 [aqui](https://wslstorestorage.blob.core.windows.net/wslblob/wsl_update_x64.msi).

#### Passo 2: Instalando o Linux

A instalação pelo _Windows_ pode ser feito utilizando o _Microsoft Store_, para este uso será utilizado o Ubuntu como Sistema Linux de uso para a execução do sistema.

#### Passo 3: Instalando o Git 

Para realizar o download do sistema será necessário do Git pois o sistema se encontra em um repositório do GitHub, para isso acesse [aqui](https://git-scm.com), e instale o git baseado no seu Sistema Operacional.

#### Passo 4: Instalando o Sistema no Linux

O sistema do ChatApi, pode ser acessado pelo [link](https://github.com/samuelcorocha/ChatAPI.git). Ao realizar o _download_, acesse o sistema em um ambiente de desenvolvimento e em um teminal de execução linux.

#### Passo 5: Configuração de Funcionamento

O Laravel possui um conjunto de dependências necessárias para seu funcionamento, através disso, para a instalação das suas dependências, será utilizado o container de execução do Docker disponibilizado pelos criadores do _Framework_ para que assim seja possível a realização das atualizações do sistema. Para isso execute o seguinte comando dentro da pasta principal do sistema em um terminal:

```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

Após isso seu projeto estará completamente atualizado, a partir de agora será necessário a configuração das variáveis de ambiente de execução do funcionamento do sistema, desde configurações de sessão quanto ao banco de dados que será utilizado. Para isso procure dentro da pasta principal um arquivo que possua o nome ``.env.example``. e copie seu conteudo para um arquivo com o nome ``.env``.

Após isso boa parte das variaveis de ambiente de configuração já estarão fazendo o seu papel, mas terá aquelas que não estarão possuindo valores por questão de segurança, entre elas a variável ``APP_KEY``, Junto das variavés de conexão com o banco de dados listadas a seguir:

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=root
DB_PASSWORD=root
```

As preferências de banco estarão ao dispor de nomeclatura, junto da utilização do user e da senha de conexão a ser estabelecida para a consulta do mesmo caso necessário a troca.

Após estas configurações, através de um terminal dentro da pasta principal, será necessário subir o container do Laravel Sail para que assim o sistema possa começar a executar, para isso execute o comando:

```
./vendor/bin/sail up -d
```
Com este comando dado, iniciará a execução do arquivo ``docker-compose.yml``, que tentará construir três imagens com containers, entre elas

- Laravel.test: Container que roda o sistema para a visualização através da url: ``http://localhost``;
- Mysql: Container que roda o mysql para armazenar o banco de dados utilizado;
- Phpmyadmin: Container que irá construir uma interface de interação para o caso de ser necessário visualizar o as informações (Obs.: Para acessar utilize o servidor: "mysql" e usuário e senha informados no arquivo ``.env``, caso não tenha alterado utilize "root" nos dois locais).

Essa ação poderá levar um tempo para executar.

Com o finalizar será necessário executar o seguinte comando pelo terminal para criar o código de validação do Laravel, o ``APP_KEY``, para isso execute:

```
./vendor/bin/sail artisan key:generate
```

#### Passo 6: Construindo o Banco de Dados

O Banco de Dados foi o material de uso essêncial para o funcionamento do sistema, baseado nisso, com a configuração já estabelecida no arquivo ``.env`` para o uso do mesmo, crie o banco de dados e insira o seu nome na linha abaixo caso não tenha sido realizado:
```
DB_DATABASE=<nome_do_banco>
```

Após isso a conexão com o banco estará realizada será possível construir as tabelas do banco de maneira limpa e de maneira anteriomente populada com informações _fakes_ (Não Reais) já anteriomente preenchidas para a necessidade de uma visualização mais rápida e dinâmica de seus resultados.

Para o caso da construção das tabelas completamente limpas, execute:

```
./vendor/bin/sail artisan migrate:fresh
```

Já para o caso de uma construção com informações pré alocadas, execute:

```
./vendor/bin/sail artisan migrate:fresh --seed
```

#### Passo 7: Acessando o Sistema

Após toda a configuração ter sido executada com sucesso, o sistema estará disponível através da url: ``http://localhost``, o qual em sua visualização web estará disponível a documentação de todos os endpoints elaborados, de maneira a permitir uma visualização mais prática e intuitiva de meios de uso.

A documentação utilizada foi construida através da ferramenta do [_Swagger_](https://swagger.io/), o qual foi elaborada para simplificar a visualização de APIs e suas funcionalidade, permitindo assim a execução ser realizada pela própria ferramenta ou por _softwares_ externos como [Insominia](https://insomnia.rest/) e [Postman](https://www.postman.com/).

Para o caso de consulta das APIs em _software_ externo, a url de domínio devera ser acessada por: ```http://localhost/api/<endpoint_de_consulta>```.

Além disso, alguns endpoints estarão bloqueados com a necessidade de um token de acesso, que será gerado no momento da criação do usuário ou no efetuar do login, sendo assim necessário enviar um cabeçalho com o nome de ``Authorization`` que venha a armazenar este token de acesso, sendo assim a única maneira de acessar estas rotas bloqueadas.

Para uma melhor visualização, acesse o sistema pelo navegador para a visualização da documentação do _Swagger_, o qual informará quais rotas estarão protegidas através de um ícone de cadeado presente no canto direito referente ao endpoint.

Com base nisso, tem-se ao fim:

```
http://localhost          => URL de documentação
http://localhost/api      => URL de consulta dos endpoints
```
