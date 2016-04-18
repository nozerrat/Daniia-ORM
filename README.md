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
define("USER","postgres");
define("PASS","1234");
define("SCHEMA","public");
define("DSN","pgsql:port=5432;host=localhost;dbname=postgres");
```
## Instanciar Daniia ORM

```php
use Daniia\Daniia;
$daniia = new Daniia();
```
en el caso de hederar la clase Daniia hay que hederar la clase BaseDB
```php
use Daniia\Daniia;
use Daniia\BaseDB;
class Personas extends BaseDB {
	protected $table = "personas"; //Nombre de la tabla
	protected $primaryKey = "id"; //Clave primaria de la tabla
}
$personas = new Personas();
```
## Uso y Ejemplos
Para usar Daniia ORM es muy falcil, aquí aplicaremos algunos ejemplos del uso de la herramienta:
### Insertar datos
```php
// Insert simple
$daniia->table("personas")->insert(["ci"=>1,"nombre"=>"Carlos","apellido"=>"Garcia"]);

// Insert multiples
$daniia->table("personas")->insert([
	["ci"=>1,"nombre"=>"Carlos","apellido"=>"Garcia"],
	["ci"=>2,"nombre"=>"Carlos","apellido"=>"Garcia"],
	["ci"=>3,"nombre"=>"Carlos","apellido"=>"Garcia"],
]);
```
### Actualizar datos
```php
// Update simple
$daniia->table("personas")->primaryKey("id")->update(["id"=>1,"ci"=>"1111","nombre"=>"aa","apellido"=>"aa"]);
// o en caso de que la ID no este esablecida en los datos
$daniia->table("personas")->where("id",1)->update(["ci"=>"1111","nombre"=>"aa","apellido"=>"aa"]);

// Update multiples
$r = $daniia->table("personas")->primaryKey("id")->update([
	["id"=>1,"ci"=>4,"nombre"=>"Petra","apellido"=>""],
	["id"=>2,"ci"=>5,"nombre"=>"José","apellido"=>"Jill"],
	["id"=>3,"ci"=>6,"nombre"=>"Jhon","apellido"=>"Peña"],
]);
```

### Eliminar datos
```php
$daniia->table("personas")->where("id",2)->delete();

$daniia->primaryKey('id')->table("personas")->delete(3);
$daniia->primaryKey('id')->table("personas")->delete([3]);

$daniia->primaryKey('id')->table("personas")->delete(6,7);
$daniia->primaryKey('id')->table("personas")->delete([6,7]);
```


### Seleccionar datos
```php
$daniia->table('personas')->select()->get();

$daniia->table('personas')->select('COUNT(*)')->first();

$daniia->table('personas')->select('ci','nombre')->first();
$daniia->table('personas')->select(['ci','nombre'])->first();
```



## API
```php

/**
 * Entrecomilla una cadena de caracteres para usarla en una consulta
 */
public function quote()
/**
 * Inicia una transacción
 */
public function begin()
/**
 * Consigna una transacción
 */
public function commit()
/**
 * Revierte una transacción
 */
public function rollback()
/**
 * Obtiene información ampliada del error asociado con la última operación del gestor de sentencia
 */
public function error()
/**
 * Ejecuta una sentencia SQL, devolviendo un conjunto de resultados como un objeto
 */
public function query()
/**
 * Retorna los datos consultados
 */
public function get()
/**
 * Retorna los datos consultados en formato Array
 */
public function getArray()
/**
 * Alia del get
 */
public function all()
/**
 * Alia del getArray
 */
public function allArray()
/**
 * Devuelve la primera fila de una consulta
 */
public function first()
/**
 * Devuelve la primera fila de una consulta
 */
public function firstArray()
/**
 * genera una lista dependiendo de los datos consultados
 */
public function lists($column,$index=null)
/**
 * Busca uno o varios registros en la Base de Datos
 */
public function find($ids=[])
/**
 * actualiza o crea un registro en la base de datos
 */
public function save()
/**
 * Establese la clave principal de la tabla
 */
public function primaryKey($primaryKey)
/**
 * Obtiene los nombres de las columnas de la tabla seleccionada
 */
public function columns($table=null)
/**
 * elimina todos los datos de una tabla
 */
public function truncate()
/**
 * Establese el nombre de la tabla
 */
public function table($table)
/**
 * Establese los nombres dee las columnas a consultar
 */
public function select($select)
/**
 * Establece el nombre de la tabla a consultar
 */
public function from($table="")
/**
 * inserta datos en la base de datos
 */
public function insert(array $datas)
/**
 * inserta y luego retorna la clave primaria del registro
 */
public function insertGetId($datas)
/**
 * actualiza los datos en la base de datos
 */
public function update(array $datas)
/**
 * elimina un registro en la base de datos
 */
public function delete($ids=[])
/**
 * establece las tebles que van hacer unidas por la izquierda
 */
public function join($table,$column,$operator=null,$value=null, $scape_quote=false)
/**
 * establece las tebles que van hacer unidas por la izquierda
 */
public function leftJoin($table,$column,$operator=null,$value=null, $scape_quote=false)
/**
 * establece las tebles que van hacer unidas por la derecha
 */
public function rightJoin($table,$column,$operator=null,$value=null, $scape_quote=false)
/**
 */
public function on($column, $operator = null, $value = false, $scape_quote=false)
/**
 */
public function orOn($column, $operator = null, $value = false, $scape_quote=false)
/**
 */
public function andOn($column, $operator = null, $value = false, $scape_quote=false)
/**
 */
public function where($column, $operator = null, $value = true, $scape_quote=true)
/**
 */
public function orWhere($column, $operator = null, $value = true, $scape_quote=true)
/**
 */
public function andWhere($column, $operator = null, $value = true, $scape_quote=true)
/**
 */
public function having($column, $operator = null, $value = true, $scape_quote=true)
/**
 */
public function orHaving($column, $operator = null, $value = true, $scape_quote=true)
/**
 */
public function andHaving($column, $operator = null, $value = true, $scape_quote=true)
/**
 * agrega el query ORDER BY basico
 */
public function orderBy($fields=[])
/**
 * agrega el query GROUP BY basico
 */
public function groupBy($fields=[])
/**
 * agrega el quiery basico LIMIT
 */
public function limit($limit,$offset=null)
/**
 * genera una lista dependiendo de los datos consultados
 */
public function union($closure)
```
