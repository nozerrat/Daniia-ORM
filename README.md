# Daniia
Se trata de un framework para realizar Mapeo de Objeto Racional (Object Relational Mapping ORM) para PHP basado en el patrón de diseño ORM ActiveRecord

## Requerimientos
- PHP >= 5.3
- POD

## Motor de Base de Datos soportado
Daniia ORM es compatible con:
* MySQL 5.1+
* Postgres 8+
* SQLite3
* SQLServer 2008+
* Oracle 

## Conección a la Base de Datos
Lo primero que necesitaremos es registrar los datos de conexión para luego usar la herramienta Daniia:

```php
// Conexión con la Base de Datos MySql
define("USER","root");
define("PASS","1234");
define("DSN","mysql:port=3306;host=localhost;dbname=test");
```
o otro ejemplo:
```php
// Conexión con la Base de Datos PostgreSql
define("USER","root");
define("PASS","1234");
define("SCHEMA","public");
define("DSN","mysql:port=3306;host=localhost;dbname=test");
```
