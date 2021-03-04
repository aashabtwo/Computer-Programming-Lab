## WEBL Computer Programming Lab

## Workaround for certain issues

# Issue 1:
Sometimes using assigning foreign keys for a table may cause migration issues, especially if the assignment is done in the wrong way. This might end up becoming a major problem because the table will nonetheless be created but will still be created, preventing any further migration commands (check first if the rollback commands works or not). To get around this problem, manually delete the table from the database, create a new drop migration file using
```
php artisan make:migration drop_<table_name>_table
```
and paste the following command in the up() function

```
Schema::dropIfExists('<table_name>');
```
then, migrate

```
php artisan migrate
```
