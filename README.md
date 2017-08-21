[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)
# Daniia
Se trata de un framework para realizar Mapeo de Objeto Racional (Object Relational Mapping ORM) para PHP basado en el patrón de diseño ORM ActiveRecord

## Instalación

### Composer

Para instalar con [Composer](https://getcomposer.org/), simplemente requiere la
última versión de este paquete.

```bash
composer require nozerrat/daniia
```

## Requerimientos
- PHP >= 5.3
- PDO

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
en el caso de hederar la clase Daniia hay que hederar la clase BaseDaniia
```php
use Daniia\Daniia;
use Daniia\BaseDaniia;
class Personas extends BaseDaniia {
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
$daniia->from('personas')->first();
$daniia->from(['personas'])->first();

$daniia->from('personas','oficina')->first();
$daniia->from(['personas','oficina'])->first();

// especificamos un sub-query
$daniia->from(function (Daniia $daniia) {
	$daniia->table("personas");
})->first();

// con alias para el sub-query contenido en el método from
$daniia->from(function (Daniia $daniia) {
	$daniia->table("personas");
}, "Alias")->first();

// otro ejemplo de sub-query anidado contenido en el clausula from
$daniia->from(function (Daniia $daniia) {
	$daniia->from(function (Daniia $daniia) {
		$daniia->from(function (Daniia $daniia) {
			$daniia->table("personas");
		}, "C");
	}, "B");
}, "A")->first();

// otro ejemplo 
$daniia
->select("P.id","P.nombre","P.apellido")
->from(function (Daniia $daniia) {
	$daniia
	->table("personas")
	->select("personas.id","personas.nombre","personas.apellido");
}, 'P')->first();
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

### Where
```php
$daniia->table("personas")->where("id","1")->first();

$daniia->table("personas")->where("id",'=',"1")->first();

$daniia->table("personas")->where("id","4")->andWhere("id","4")->first();

$daniia->table("personas")->where("id",4)->orWhere("id",4)->first();

$daniia->table("personas")->where("id",[4,5,6,7])->first();

$daniia->table("personas")->where("id",'in',[4,5,6,7])->first();

$daniia->table("personas")->where(function (Daniia $daniia) {
	$daniia->where("id",4)->andWhere("apellido","LIKE","%garcia%");
})->first();

$daniia->table("personas")->where(function (Daniia $daniia) {
	$daniia->where("id",4);
})->orWhere(function (Daniia $daniia){
	$daniia->where("id",4);
})->first();

$daniia->table("personas")->where(function (Daniia $daniia) {
	$daniia->where("id","1")->orWhere(function (Daniia $daniia) {
		$daniia->where("id","2")->andWhere(function (Daniia $daniia) {
			$daniia->where("id","3");
		});
	});
})->first();

$daniia->table("personas")->where("personas.id",function($query) {
	$query->table("personas")->select("id")->where("id",4)->limit(1);
})->first();

$daniia->table("personas")->where("personas.id","=",function($query) {
	$query->table("personas")->select("id")->where("id",4)->limit(1);
})->first();

$daniia->table("personas")->where("id",'in',function(Daniia $daniia){
	$daniia->table('personas')->select('id');
})->first();
```

### Having 
```php
$daniia->table("personas")->having("id","1")->first();

$daniia->table("personas")->having("id",'=',"1")->first();

$daniia->table("personas")->having("id","4")->andHaving("id","4")->first();

$daniia->table("personas")->having("id",4)->orHaving("id",4)->first();

$daniia->table("personas")->having("id",[4,5,6,7])->first();

$daniia->table("personas")->having("id",'in',[4,5,6,7])->first();

$daniia->table("personas")->having(function (Daniia $daniia) {
	$daniia->having("id",4)->andHaving("apellido","LIKE","%garcia%");
})->first();

$daniia->table("personas")->having(function (Daniia $daniia) {
	$daniia->having("id",4);
})->orHaving(function (Daniia $daniia){
	$daniia->having("id",4);
})->first();

$daniia->table("personas")->having(function (Daniia $daniia) {
	$daniia->having("id","1")->orHaving(function (Daniia $daniia) {
		$daniia->having("id","2")->andHaving(function (Daniia $daniia) {
			$daniia->having("id","3");
		});
	});
})->first();

$daniia->table("personas")->having("personas.id",function($query) {
	$query->table("personas")->select("id")->having("id",4)->limit(1);
})->first();

$daniia->table("personas")->having("personas.id","=",function($query) {
	$query->table("personas")->select("id")->having("id",4)->limit(1);
})->first();

$daniia->table("personas")->having("id",'in',function(Daniia $daniia){
	$daniia->table('personas')->select('id');
})->first();
```

### Union
```php
$daniia->table("personas")->where('id',1)->union(function (Daniia $daniia) {
	$daniia->table("personas")->where('id',1);
})->get();

$daniia->table("personas")->select('id')->union(function (Daniia $daniia) {
	$daniia->table("oficina")->select('id_personas AS id');
})->get();

$daniia->table("personas")->select('id')->where('id',1)->union(function (Daniia $daniia) {
	$daniia->table("oficina")->select('id_personas AS id')->where('id_personas',4);
})->get();

$daniia->table("personas")->where('id',1)->union(function (Daniia $daniia) {
	$daniia->table("personas")->where('id',1);
})->union(function (Daniia $daniia) {
	$daniia->table("personas")->where('id',1);
})->limit(1)->get();
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
Daniia {
	public string quote( void );
	public Daniia begin( void );
	public Daniia commit( void );
	public Daniia rollback( void );
	public array error( void );
	public PDOStatement query( void );
	public array get( void );
	public array getArray( void );
	public array all( void );
	public array allArray( void );
	public object first( void );
	public array firstArray( void );
	public array lists( string $column [, string $index = null ] );
	public Daniia find( string $ids [, ...] | array $ids );
	public bool save( void );
	public Daniia primaryKey( string $primaryKey );
	public array columns( string $table = null );
	public bool truncate( void );
	public Daniia table( string $table [, ...] | array $table );
	public Daniia select( string $select [, ...] | array $select );
	public Daniia from( string $table [, ...] | array $table | Closure $table [, string $aliasFrom] );
	public bool insert( array $datas );
	public int insertGetId( array $datas );
	public bool update( array $datas );
	public bool delete( string $ids [, ...] | array $ids = [] );
	public Daniia join( string $table, string $column | Closure $column [, string $operator = null | Closure $operator [, string $value = null | Closure $value | bool $value [, bool $scape_quote = false ]]] );
	public Daniia innerJoin( string $table, string $column | Closure $column [, string $operator = null | Closure $operator [, string $value = null | Closure $value | bool $value [, bool $scape_quote = false ]]] );
	public Daniia leftJoin( string $table, string $column | Closure $column [, string $operator = null | Closure $operator [, string $value = null | Closure $value | bool $value [, bool $scape_quote = false ]]] );
	public Daniia rightJoin( string $table, string $column | Closure $column [, string $operator = null | Closure $operator [, string $value = null | Closure $value | bool $value [, bool $scape_quote = false ]]] );
	public Daniia on( string $column | Closure $column [, string $operator = null | Closure $operator [, string $value = null | Closure $value | bool $value [, bool $scape_quote = false ]]] );
	public Daniia orOn( string $column | Closure $column [, string $operator = null | Closure $operator [, string $value = null | Closure $value | bool $value [, bool $scape_quote = false ]]] );
	public Daniia andOn( string $column | Closure $column [, string $operator = null | Closure $operator [, string $value = null | Closure $value | bool $value [, bool $scape_quote = false ]]] );
	public Daniia where( string $column | Closure $column [, string $operator = null | Closure $operator [, string $value = null | Closure $value | bool $value [, bool $scape_quote = false ]]] );
	public Daniia orWhere( string $column | Closure $column [, string $operator = null | Closure $operator [, string $value = null | Closure $value | bool $value [, bool $scape_quote = false ]]] );
	public Daniia andWhere( string $column | Closure $column [, string $operator = null | Closure $operator [, string $value = null | Closure $value | bool $value [, bool $scape_quote = false ]]] );
	public Daniia having( string $column | Closure $column [, string $operator = null | Closure $operator [, string $value = null | Closure $value | bool $value [, bool $scape_quote = false ]]] );
	public Daniia orHaving( string $column | Closure $column [, string $operator = null | Closure $operator [, string $value = null | Closure $value | bool $value [, bool $scape_quote = false ]]] );
	public Daniia andHaving( string $column | Closure $column [, string $operator = null | Closure $operator [, string $value = null | Closure $value | bool $value [, bool $scape_quote = false ]]] );
	public Daniia orderBy( string $fields | array $fields );
	public Daniia groupBy( string $fields | array $fields );
	public Daniia limit( int $limit [, int $offset = null ] | array $limit );
	public Daniia union( Closure $closure );
}
```
