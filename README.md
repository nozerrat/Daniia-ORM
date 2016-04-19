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
$daniia->table('personas')->get();// consultamos todos los datos
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
$personas->get();// consultamos todos los datos
```
## Uso y Ejemplos
Para usar Daniia ORM es muy falcil, aquí aplicaremos algunos ejemplos del uso de la herramienta:
### Insert
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
### Update
```php
// Update simple
$daniia->table("personas")->primaryKey("id")->update(["id"=>1,"ci"=>"1111","nombre"=>"aa","apellido"=>"aa"]);
// o en caso de que la ID no este esablecida en los datos
$daniia->table("personas")->where("id",1)->update(["ci"=>"1111","nombre"=>"aa","apellido"=>"aa"]);

// Update multiples
$daniia->table("personas")->primaryKey("id")->update([
	["id"=>1,"ci"=>4,"nombre"=>"Petra","apellido"=>""],
	["id"=>2,"ci"=>5,"nombre"=>"José","apellido"=>"Jill"],
	["id"=>3,"ci"=>6,"nombre"=>"Jhon","apellido"=>"Peña"],
]);
```

### Delete
```php
$daniia->table("personas")->where("id",2)->delete();

$daniia->primaryKey('id')->table("personas")->delete(3);
$daniia->primaryKey('id')->table("personas")->delete([3]);

$daniia->primaryKey('id')->table("personas")->delete(6,7);
$daniia->primaryKey('id')->table("personas")->delete([6,7]);

$daniia->primaryKey('id')->table("personas")->find(8)->delete();
$daniia->primaryKey('id')->table("personas")->find([8])->delete();
```


### Select
```php
$daniia->table('personas')->select()->get();

$daniia->table('personas')->select('COUNT(*)')->first();

$daniia->table('personas')->select('ci','nombre')->first();
$daniia->table('personas')->select(['ci','nombre'])->first();
```

### Operadores validos
Los operadores que soporta la framework Daniia son: =, <, >, <=, >=, <>, !=, like, not like, in, is, is not, ilike, between, not between. Por ejemplo:
```php
/**
 * OPERADORES VALIDOS
 **/
$daniia->table("personas")->where("id","4")->first();// por defecto es '='

$daniia->table("personas")->where("id",'=',"4")->first();

$daniia->table("personas")->where("id",'like',"4")->first();

$daniia->table("personas")->where("id",["4"])->first();// si es un array por default es 'IN'

$daniia->table("personas")->where("id",'in',["4"])->first();

$daniia->table("personas")->where("id",'is',"true",false)->first();//el parametro false indica no escapar valor
// o puede usar en este caso 
$daniia->table("personas")->where("id is true")->first();
```

### Table
El método table se encarga de asignar el nombre de la tabla al framework
```php
$daniia->table('personas')->first();
$daniia->table(['personas'])->first();

// realizamos un JOIN
$daniia->table('personas','oficina')->first();
$daniia->table(['personas','oficina'])->first();
```

### From
El método from es similar al método table, se usa para asignar los nombres de las tablas a consultar, pero si en su argumento especificamos un closure indicará un sub-quey contenido en la clausula from.
```php
$daniia->table('personas')->from()->first();

$daniia->from('personas')->first();
$daniia->from(['personas'])->first();

$daniia->from('personas','oficina')->first();
$daniia->from(['personas','oficina'])->first();

// especificamos un sub-query
$daniia->from(function (Daniia $daniia) {
	$daniia->table("personas");
})->first();

// si especificamos el método table este será un alias para el sub-query contenido en el método from
$daniia->table("AliasForFROM")->from(function (Daniia $daniia) {
	$daniia->table("personas");
})->first();

// otro ejemplo de sub-query anidado contenido en el clausula from
$daniia->table("A")->from(function (Daniia $daniia) {
	$daniia->table("B")->from(function (Daniia $daniia) {
		$daniia->table("C")->from(function (Daniia $daniia) {
			$daniia->table("personas");
		});
	});
})->first();

// otro ejemplo 
 $daniia->select("personas.id","personas.nombre","personas.apellido")->from(function (Daniia $daniia) {
	$daniia->table("personas")->select("personas.id","personas.nombre","personas.apellido");
})->first();
```

### Join, LeftJoin, RightJoin
```php
// Join alternativa
$daniia->table('personas','oficina')->where('personas.id','oficina.id_personas',false)->first();

$daniia->table('personas')->join("oficina","personas.id","oficina.id_personas")->first();

$daniia->table('personas')->innerJoin("oficina","personas.id","=","oficina.id_personas")->first();

$daniia->table('personas')->leftJoin("oficina","personas.id","oficina.id_personas")->first();

$daniia->table('personas')->rightJoin("oficina","personas.id","oficina.id_personas")->first();

$daniia->table("personas")->join('oficina',"personas.id",function(Daniia $query) {
	$query->select("personas.id")->from("personas")->where("personas.id","4")->limit(1);
})->first();

$daniia->table('personas')->join("oficina",function(Daniia $daniia) {
	$daniia->on("personas.id",[1,2,3,4])->orOn("personas.id","<>","oficina.id_personas");
})->first();

 $daniia->table('personas')->join("oficina alias_a",function(Daniia $daniia) {
	$daniia->on(function(Daniia $daniia) {
		$daniia->on("personas.id","alias_a.id_personas");
	});
})->join("oficina alias_b",function(Daniia $daniia) {
	$daniia->on("personas.id",'=',function(Daniia $daniia) {
		$daniia->table('oficina')->select('id_personas')->where('id_personas',4);
	});
})->first();

$daniia->table('personas')->join("oficina",function(Daniia $daniia) {
	$daniia->on("personas.id",function(Daniia $daniia) {
		$daniia->select("personas.id")->from("personas")->where("personas.id","4")->limit(1);
	});
})->first();
```

### Get, GetArray, List
```php
$daniia->table('personas')->get();

$daniia->table('personas')->getArray();

$daniia->table('personas')->lists('nombre');

$daniia->table('personas')->lists('nombre','id');
```

###  Find
```php
$daniia->primaryKey('id')->table('personas')->find(1);
$daniia->primaryKey('id')->table('personas')->find([1]);

$daniia->primaryKey('id')->table('personas')->find(1,2);
$daniia->primaryKey('id')->table('personas')->find([1,2]);

$daniia->primaryKey('id')->table('personas')->find(2);
echo $daniia->id;
echo $daniia->ci;
echo $daniia->nombre;
echo $daniia->apellido;
```

### First, FirstArray
```php
$daniia->table('personas')->first();
$daniia->table('personas')->firstArray();

$daniia->table('personas')->where('id',1)->first();
$daniia->table('personas')->where('id',1)->firstArray();

$daniia->table('personas')->primaryKey('id')->find([1,2])->first();
$daniia->table('personas')->primaryKey('id')->find([1,2])->firstArray();
```

### Save
```php
$daniia->primaryKey('id')->table('personas')->find(2);
$daniia->nombre = "yyyyyyyy";
$daniia->save();//UPDATE

$daniia->primaryKey('id')->table('personas')->find(1,2)->first();
$daniia->nombre = "yyyyyyyy";
$daniia->save();//UPDATE

$daniia->primaryKey('id')->table('personas')->find('00')->first(); // registro no existe..
$daniia->ci       = "123456789";
$daniia->nombre   = "Carlos";
$daniia->apellido = "Garcia";
$daniia->save();//INSERT

$daniia->primaryKey('id')->table('personas');
$daniia->ci       = "123456789";
$daniia->nombre   = "Carlos";
$daniia->apellido = "Garcia";
$daniia->save();//INSERT
```

### Order By
```php
$daniia->table("personas")->orderBy("ci")->get();
$daniia->table("personas")->orderBy(["ci"])->get();

$daniia->table("personas")->orderBy("apellido","nombre")->get();
$daniia->table("personas")->orderBy(["apellido","nombre"])->get();

// al fina se indica el tipo de orden 
$daniia->table("personas")->orderBy("apellido","nombre",'asc')->get();
$daniia->table("personas")->orderBy(["apellido","nombre",'desc'])->get();
```

### Group By
```php
$daniia->table("personas")->groupBy("ci")->first();
$daniia->table("personas")->groupBy(["ci"])->get();

$daniia->table("personas")->groupBy("apellido","nombre")->get();
$daniia->table("personas")->groupBy(["apellido","nombre"])->get();
```

### Limit
```php
$daniia->table("personas")->limit("1")->get();
$daniia->table("personas")->limit(["1"])->get();

$daniia->table("personas")->limit("1","0")->get();
$daniia->table("personas")->limit(["1","0"])->get();
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
