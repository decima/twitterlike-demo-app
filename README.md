# Twitter like project

For Symfony students

## Requirements
- php with PDO SQLite extension
## Getting started

clone the project and install all dependencies with composer
```
composer install
```

To initialize the database : 
```
bin/console doctrine:database:create
bin/console doctrine:schema:update --complete --force
bin/console doctrine:fixtures:load -n
```