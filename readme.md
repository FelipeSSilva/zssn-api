# Iniciando
Após clonar o projeto não esquecer de rodar:

> composer install

Assim como editar o arquivo de conexão .env, no projeto possui um arquivo de exemplo (.env.example), criar o banco de dados (MySQL) e rodar:

> php artisan migrate

Depois de executar o comando acima, agora é hora de inserir dados no banco para permitir testes e o funcionamento correto da API, para isso rode nessa sequência:

> composer dump-autoload
>
> php artisan db:seed


# API endpoints
| Tipo         | Endpoint | Resposta |
|--------------|----------|----------| 
| GET      | /survivors | Retorna todos os sobreviventes em ZSSN |
| POST       | /survivors | Cria um novo sobrevivente em ZSSN |
| PUT | /survivors/:id | Atualiza a localização de um sobrevivente em ZSSN |
| POST | /survivors/:survivorId1/reportInfection/:survivorId2  | O primeiro sobrevivente reporta que o segundo está infectado |
| PUT | /survivors/:survivorId1/tradeItems/:survivorId2  | O primeiro sobrevivente oferece um pedido de troca para segundo |
| GET | /survivors/reports/percentageInfected |Retorna a porcetagem de infectados em ZSSN |
| GET | /survivors/reports/percentageNonInfected  | Retorna a porcetagem de não infectados em ZSSN |
| GET | /survivors/reports/averageAmount  | Retorna a média de itens por usuários em ZSSN |
| GET | /survivors/reports/pointsLost/:id  | Retorna a quantidade de pontos peridos em itens do usuário infectado |
