# Database #

```
php app/console doctrine:database:create
```

### Development ###
```
php app/console doctrine:schema:drop --force --full-database
php app/console doctrine:schema:update --force
php app/console doctrine:fixtures:load
```
