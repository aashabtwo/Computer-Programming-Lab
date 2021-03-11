# WEBL Computer Programming Lab

## Workaround for certain issues

### Issue 1:
Sometimes assigning foreign keys for a table may cause migration issues, especially if the assignment is done in the wrong way. This might end up becoming a major problem because the table will nonetheless be created but could still be broken, preventing further migration commands (check first if the rollback command works or not). To get around this problem, you will have to manually delete the table from the database, create a new drop migration file using

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

### Issue 2: Capturing submission outputs
'exec' command returns only the last line.
Use 'shell_exec' instead

### Issue 3: Error handling, request body validation, file extension check
None of these have been implemented. However, they will be (hopefully) present in the next commit.
