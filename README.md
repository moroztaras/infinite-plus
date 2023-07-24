# Technical task for Infinite Plus

Project on Symfony 6 using docker

## Install project

### Things you need
* composer
* npm
* docker
* php-cs-fixer (brew install php-cs-fixer)

### Clone repository to your local machine
```bash
% git clone git@github.com:moroztaras/infinite-plus.git
```

### Create project config
```bash
% cd infinite-plus
% cp .env .env.local
% cp ./docker/.env.dist ./docker/.env
```
### Quick start of the project

Adjust .env.local line 6.

It's credentials to database.

# Run MySQL in a docker
```bash
% docker-compose -f ./docker/docker-compose.yaml  up -d
```

### Execute a migration to the latest available version
```bash
./bin/console doctrine:migrations:migrate
```

### Run server
```bash
% symfony serve:start
```

### Go to the link at
```bash
http://127.0.0.1:8000
```

Collection postman in file: ./Infinite-plus.postman_collection.json 


© 2023