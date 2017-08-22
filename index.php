<?php

/* 

   CREATE DATABASE daniia;

   CREATE SCHEMA daniia;

   CREATE TABLE personas (
   		id serial NOT NULL,
   		nombre text NOT NULL,
   		apellido text,
   		ci text NOT NULL,
   		CONSTRAINT id_pk PRIMARY KEY (id)
   );

   CREATE TABLE oficina (
   		id serial NOT NULL,
   		id_personas integer,
   		oficina text,
   		CONSTRAINT id_pk_oficina PRIMARY KEY (id)
   );

 */


// foreach ([
// 			 "USER" =>"root",
// 			 "PASS" =>"",
// 			 "SCHEMA" =>"test",
// 			 "DSN" =>"mysql:port=3306;host=localhost;dbname=test",
// 		 ] as $key => $value
// ) { define(strtoupper($key),$value); }

foreach ([
	"USER" =>"postgres",
	"PASS" =>"123",
	"SCHEMA" =>"codeigniter",
	"DSN" =>"pgsql:port=5432;host=localhost;dbname=codeigniter",
	]
as $key => $value
) { define(strtoupper($key),$value); }

require("Daniia/Daniia.php");
require("Daniia/BaseDaniia.php");

use Daniia\Daniia;
use Daniia\BaseDaniia;

class Personas extends BaseDaniia {
	protected $table = "personas";
	protected $primaryKey = "id";
}

$daniia   = new Daniia;
$personas = new Personas;

/**
 * COLUMNS
 **/
// $columns = $daniia->columns('personas');
// var_dump($columns);
// echo "<hr>";

// $columns = $daniia->table('personas')->columns();
// var_dump($columns);
// echo "<hr>";



/**
 * TRUNCATE
 **/
// $r = $daniia->table("personas")->truncate();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("oficina")->truncate();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";



/**
 * INSERT
 **/
// $r = $daniia
// ->table("personas")
// ->insert(["ci"=>1,"nombre"=>"Carlos","apellido"=>"Garcia","otros"=>"otros"]);
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// ->table("personas")
// ->insert([
// 	["ci"=>2,"nombre"=>"Carlos","apellido"=>"Garcia","otros"=>"otros"],
// 	["ci"=>3,"nombre"=>"Carlos","apellido"=>"Garcia"],
// 	["ci"=>4,"nombre"=>"Carlos","apellido"=>"Garcia","otros"=>"otros"],
// ]);
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// ->table("personas")
// ->insertGetId(["ci"=>date("Ymdms"),"nombre"=>"Carlos","apellido"=>"Garcia"]);
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// ->table("oficina")
// ->insert(["id_personas"=>$daniia->last_id,"oficina"=>"Oficina ".$daniia->last_id]);
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";


/**
 * UPDATE
 **/
// $r = $daniia->table("personas")->where("id",1)->update(["ci"=>"1111111","nombre"=>"aaaa","apellido"=>"aaaa","otro"=>"otro"]);
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->primaryKey("id")->where(1,1)->update([
// 		["id"=>2,"ci"=>4,"nombre"=>"Petra","apellido"=>"","otros"=>"otros"],
// 		["id"=>3,"ci"=>5,"nombre"=>"José","apellido"=>"Jill"],
// 		["id"=>4,"ci"=>6,"nombre"=>"Jhon","apellido"=>"Peña","otros"=>"otros"],
// 	]);
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";



/**
 * DELETE
 **/
// $r = $daniia->table("personas")->where("id",2)->delete();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->primaryKey('id')->table("personas")->delete([3]);
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->primaryKey('id')->table("personas")->where("id",4)->delete(1);
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->primaryKey('id')->table("personas")->delete(1,4);
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->primaryKey('id')->table("personas")->where("id",[5,6,7])->delete();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->primaryKey('id')->table("personas")->find([8])->delete();
// var_dump($daniia->sql);
// var_dump($daniia->data);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->primaryKey('id')->table("personas")->find([11,12,13])->delete();
// var_dump($daniia->sql);
// var_dump($daniia->data);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->primaryKey('id')->table("personas")->where("id",10)->orWhere("id",14)->delete();
// var_dump($daniia->sql);
// var_dump($daniia->data);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->primaryKey('id')->table("personas")->delete();
// var_dump($daniia->sql);
// var_dump($daniia->data);
// var_dump($r);
// echo "<hr>";



/**
 * SELECT
 **/
// $r = $daniia->table('personas')->select()->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table('personas')->select('COUNT(*) AS registros')->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table('personas')->select('ci','nombre')->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table('personas')->select(['ci','nombre'])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table('personas')->select('ci AS cedula',"CONCAT(nombre, ' ', apellido) AS nomapel")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table('personas')->select(['ci AS cedula',"CONCAT(nombre, ' ', apellido) AS nomapel"])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";



/**
 * OPERADORES VALIDOS
 **/
// $r = $daniia->table("personas")->where("id","4")->first();// POR DEFECTO ES '='
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'=',"4")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'<',"4")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'<=',"4")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'>',"4")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'>=',"4")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'<>',"4")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'!=',"4")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'LIKE',"%4%")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'NOT LIKE',"%4%")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("nombre",'ILIKE',"%carlos%")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("nombre",'NOT ILIKE',"%carlos%")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",["4"])->first();// si es un array por default es 'IN'
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'IN',["4"])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'IS',"TRUE",false)->first();// el parametro 'false' indica no escapar valor
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id IS TRUE")->first();// el parametro 'false' indica no escapar valor
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'IS NOT',"NULL",false)->first();// el parametro 'false' indica no escapar valor
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id IS NOT NULL")->first();// el parametro 'false' indica no escapar valor
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'BETWEEN',[3,5])->first();// el parametro 'false' indica no escapar valor
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'NOT BETWEEN',[3,5])->first();// el parametro 'false' indica no escapar valor
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";



/**
 * TABLE
 **/
// $r = $daniia->table('personas')->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table('personas','oficina')->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table(['personas'])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table(['personas','oficina'])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";



/**
 * FROM
 **/
// $r = $daniia->table('personas')->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->from('personas')->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->from(['personas'])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->from('personas','oficina')->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $personas->from('oficina')->first();
// var_dump($personas->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->from(['personas','oficina'])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->from(['personas','oficina'])->where('personas.id=oficina.id_personas')->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->from(function (Daniia $daniia) {
// 		$daniia->table("personas");
// 	})->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->from(function (Daniia $daniia) {
// 		$daniia->table("personas");
// 	},"AliasForFROM")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->select("id","nombre","apellido")->from(function (Daniia $daniia) {
// 		$daniia->table("personas")->select("personas.id","personas.nombre","personas.apellido");
// 	})->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->select("id_personas","id_oficinas")->from(function (Daniia $daniia) {
// 		$daniia->table("oficina")->select("id_personas","id AS id_oficinas")->where("id_personas",'<=',4);
// 	})->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->from(function (Daniia $daniia) {
// 	$daniia->from(function (Daniia $daniia) {
// 		$daniia->from(function (Daniia $daniia) {
// 			$daniia->table("personas");
// 		},"C");
// 	},"B");
// },"A")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->select("id_personas","id_oficinas")->from(function (Daniia $daniia) {
// 	$daniia->table("oficina")->select("id_personas","id AS id_oficinas")->where("id_personas",'<=',4);
// })
// ->where('TRUE')
// ->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";



/**
 * JOIN, LEFTJOIN, RIGHTJOIN
 **/
// $r = $daniia->table('personas','oficina')->where('personas.id','oficina.id_personas',false)->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table('personas')->join("oficina","personas.id","oficina.id_personas")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table('personas')->join("oficina","personas.id","=","oficina.id_personas")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table('personas')->leftJoin("oficina","personas.id","=","oficina.id_personas")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table('personas')->rightJoin("oficina","personas.id","=","oficina.id_personas")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table('personas')->rightJoin("oficina","personas.id",[1,2,3,4])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table('personas')->join("oficina a","personas.id","a.id_personas")->leftJoin("oficina b","personas.id","b.id_personas")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// 	->table("personas")
// 	->join('oficina',"personas.id",function(Daniia $query) {
// 		$query
// 			->select("personas.id")
// 			->from("personas")
// 			->where("personas.id","4")
// 			->limit(1);
// 	})->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table('personas')->join("oficina",function(Daniia $daniia) {
// 	$daniia
// 		->on("personas.id",[1,2,3,4])
// 		->on("personas.id","oficina.id_personas")
// 		->orOn("personas.id",'<>',"oficina.id_personas")
// 		->andOn("personas.id","1",true)
// 		->orOn("personas.id","<>","oficina.id_personas");
// })->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table('personas')
// 	->join("oficina alias_a",function(Daniia $daniia) {
// 		$daniia->on(function(Daniia $daniia) {
// 			$daniia->on("personas.id","alias_a.id_personas");
// 		});
// 	})
// 	->join("oficina alias_b",function(Daniia $daniia) {
// 		$daniia->on("personas.id",'=',function(Daniia $daniia) {
// 			$daniia->table('oficina')->select('id_personas')->where('id_personas',4);
// 		});
// 	})
// 	->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table('personas')
// 	->join("oficina",function(Daniia $daniia) {
// 		$daniia
// 		->on("personas.id",function(Daniia $daniia) {
// 			$daniia
// 				->select("personas.id")
// 				->from("personas")
// 				->where("personas.id","4")
// 				->limit(1);
// 		});
// })->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";



/**
 * GET, GETARRAY, LIST
 **/
// $r = $daniia->table('personas')->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table('personas')->getArray();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";
// //
// $daniia->table('personas')->lists('nombre');
// var_dump($daniia->sql);
// var_dump($daniia->data);
// echo "<hr>";
// //
// $daniia->table('personas')->lists('nombre','id');
// var_dump($daniia->sql);
// var_dump($daniia->data);
// echo "<hr>";



/**
 * FIND
 **/
// $r = $daniia->primaryKey('id')->table('personas')->find(1);
// var_dump($daniia->sql);
// var_dump($r->data);
// echo "<hr>";

// $r = $daniia->primaryKey('id')->table('personas')->find(1,2);
// var_dump($daniia->sql);
// var_dump($r->data);
// echo "<hr>";

// $r = $daniia->primaryKey('id')->table('personas')->find([2]);
// var_dump($daniia->sql);
// var_dump($daniia->id);
// var_dump($daniia->ci);
// var_dump($daniia->nombre);
// var_dump($daniia->apellido);
// var_dump($daniia->data);
// echo "<hr>";



/**
 * FIRST, FIRSTARRAY
 **/
// $daniia->table('personas')->first();
// var_dump($daniia->sql);
// var_dump($daniia->data);
// echo "<hr>";

// $daniia->table('personas')->firstArray();
// var_dump($daniia->sql);
// var_dump($daniia->data);
// echo "<hr>";

// $daniia->table('personas')->where('id',1)->first();
// var_dump($daniia->sql);
// var_dump($daniia->data);
// echo "<hr>";

// $daniia->table('personas')->where('id',1)->firstArray();
// var_dump($daniia->sql);
// var_dump($daniia->data);
// echo "<hr>";

// $r = $daniia->table('personas')->primaryKey('id')->find([1,2])->first();
// var_dump($daniia->sql);
// var_dump($daniia->data);
// echo "<hr>";

// $r = $daniia->table('personas')->primaryKey('id')->find([1,2])->firstArray();
// var_dump($daniia->sql);
// var_dump($daniia->data);
// echo "<hr>";


/**
 * SAVE
 **/
// $daniia->primaryKey('id')->table('personas')->find(2);
// $daniia->nombre = "yyyyyyyy";
// $r = $daniia->save();//UPDATE
// var_dump($daniia->sql);
// var_dump($daniia->data);
// var_dump($r);
// echo "<hr>";

// $daniia->primaryKey('id')->table('personas')->find(1,2)->first();
// $daniia->nombre = "yyyyyyyy";
// $r = $daniia->save();//UPDATE
// var_dump($daniia->sql);
// var_dump($daniia->data);
// var_dump($r);
// echo "<hr>";

// $daniia->primaryKey('id')->table('personas')->find('00')->first();// registro no existe..
// $daniia->ci       = "123456789";
// $daniia->nombre   = "Carlos";
// $daniia->apellido = "Garcia";
// $r = $daniia->save();//INSERT
// var_dump($daniia->sql);
// var_dump($daniia->data);
// var_dump($r);
// echo "<hr>";

// $daniia->primaryKey('id')->table('personas');
// $daniia->ci       = "123456789";
// $daniia->nombre   = "Carlos";
// $daniia->apellido = "Garcia";
// $r = $daniia->save();//INSERT
// var_dump($daniia->sql);
// var_dump($daniia->data);
// var_dump($r);
// echo "<hr>";



/**
 * ORDER
 **/
// $r = $daniia->table("personas")->orderBy("ci")->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->orderBy(["ci",'desc'])->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->orderBy("apellido","nombre")->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->orderBy("apellido","nombre",'desc')->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->orderBy(["apellido","nombre"])->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->orderBy(["apellido","nombre",'desc'])->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";


/**
 * LIMIT
 **/
// $r = $daniia->table("personas")->limit("1","0")->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->limit("1")->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->limit(["1","0"])->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->limit(["1"])->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";


/**
 * GROUP
 **/
// $r = $daniia->select('ci')->table("personas")->groupBy("ci")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->select("apellido","nombre")->table("personas")->groupBy("apellido","nombre")->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table(["personas"])->select(['ci'])->groupBy(["ci"])->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table(["personas"])->select(["apellido","nombre"])->groupBy(["apellido","nombre"])->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";


/**
 * WHERE
 **/
//['=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'in']

// $r = $daniia->table("personas")->where("id",'like',"%1%")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id","1")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'=',"1")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'=',"4")->where("id",'=',"4",false)->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",4)->andWhere("id",4,false)->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'=',4)->orWhere("id",'=',4)->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",[4,5,6,7])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'in',[4,5,6,7])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// 	->table("personas")
// 	->where(function (Daniia $daniia) {
// 		$daniia
// 			->where("id",4)
// 			->andWhere("apellido","LIKE","%garcia%");
// 	})
// 	->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// 	->table("personas")
// 	->where(function (Daniia $daniia) {
// 		$daniia
// 			->where("id",4)
// 			->orWhere("apellido","LIKE","%garcia%");
// 	})
// 	->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// 	->table("personas")
// 	->where(function (Daniia $daniia) {
// 		$daniia
// 			->where("id",4);
// 	})
// 	->orWhere(function (Daniia $daniia){
// 		$daniia
// 			->where("id",4);
// 	})
// 	->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// 	->table("personas")
// 	->where(function (Daniia $daniia) {
// 		$daniia
// 			->where("id","1")
// 			->orWhere(function (Daniia $daniia) {
// 				$daniia
// 					->where("id","2")
// 					->andWhere(function (Daniia $daniia) {
// 						$daniia
// 							->where("id","3");
// 					});
// 			});
// 	})
// 	->orWhere(function (Daniia $daniia){
// 		$daniia
// 			->where("id","4");
// 	})
// 	->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// 	->table("personas")
// 	->where("personas.id",function($query) {
// 		$query
// 			->table("personas")
// 			->select("id")
// 			->where("id",4)
// 			->limit(1)
// 		;
// 	})->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// 	->table("personas")
// 	->where("personas.id","=",function($query) {
// 		$query
// 			->table("personas")
// 			->select("id")
// 			->where("id",4)
// 			->limit(1);
// 	})->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where("id",'in',function(Daniia $daniia){
// 	$daniia->table('personas')->select('id');
// })->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where(['nombre'=>'carlos'])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where(['nombre !='=>'carlos'])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where(['nombre'=>[4,5,6,7]])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->where(['nombre in'=>[4,5,6,7]])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
//    ->table("personas")
//    ->where(["personas.id"=>function($query) {
//       $query
//          ->table("personas")
//          ->select()
//          ->where("id",4)
//          ->limit(1);
//    }])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
//    ->table("personas")
//    ->where(["personas.id !="=>function($query) {
//       $query
//          ->table("personas")
//          ->select()
//          ->where("id",4)
//          ->limit(1);
//    }])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";


/**
 * HAVING
 **/
////['=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'in']
// $r = $daniia->table("personas")->having("id",'like',"%1%")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->having("id","1")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->having("id",'=',"1")->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->having("id",'=',"4")->andHaving("id",'=',"4",false)->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->having("id",4)->orHaving("id",4,false)->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->having("id",'=',4)->where("id",'=',4)->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->having("id",[4,5,6,7])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->having("id",'in',[4,5,6,7])->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// 	->table("personas")
// 	->having(function (Daniia $daniia) {
// 		$daniia
// 			->having("id",4)
// 			->andHaving("apellido","LIKE","%garcia%");
// 	})
// 	->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// 	->table("personas")
// 	->having(function (Daniia $daniia) {
// 		$daniia
// 			->having("id",4)
// 			->orHaving("apellido","LIKE","%garcia%");
// 	})
// 	->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// 	->table("personas")
// 	->having(function (Daniia $daniia) {
// 		$daniia
// 			->having("id",4);
// 	})
// 	->orHaving(function (Daniia $daniia){
// 		$daniia
// 			->having("id",4);
// 	})
// 	->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// 	->table("personas")
// 	->having(function (Daniia $daniia) {
// 		$daniia
// 			->having("id","1")
// 			->orHaving(function (Daniia $daniia) {
// 				$daniia
// 					->having("id","2")
// 					->andHaving(function (Daniia $daniia) {
// 						$daniia
// 							->having("id","3");
// 					});
// 			});
// 	})
// 	->orHaving(function (Daniia $daniia){
// 		$daniia
// 			->having("id","4");
// 	})
// 	->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// 	->table("personas")
// 	->having("personas.id",function($query) {
// 		$query
// 			->table("personas")
// 			->select("id")
// 			->having("id",4)
// 			->limit(1)
// 		;
// 	})->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// 	->table("personas")
// 	->having("personas.id","=",function($query) {
// 		$query
// 			->table("personas")
// 			->select("id")
// 			->having("id",4)
// 			->limit(1);
// 	})->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia->table("personas")->having("id",'in',function(Daniia $daniia){
// 	$daniia->table('personas')->select('id');
// })->first();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";



/**
 * UNION
 **/
// $r = $daniia
// 	->table("personas")->where('id',1)
// 	->union(function (Daniia $daniia) {
// 		$daniia->table("personas")->where('id',1);
// 	})
// 	->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// 	->table("personas")->select('id')
// 	->union(function (Daniia $daniia) {
// 		$daniia->table("oficina")->select('id_personas AS id');
// 	})
// 	->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// 	->table("personas")->select('id')->where('id',1)
// 	->union(function (Daniia $daniia) {
// 		$daniia->table("oficina")->select('id_personas AS id')->where('id_personas',4);
// 	})
// 	->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";

// $r = $daniia
// 	->table("personas")->where('id',1)
// 	->union(function (Daniia $daniia) {
// 		$daniia->table("personas")->where('id',1);
// 	})
// 	->union(function (Daniia $daniia) {
// 		$daniia->table("personas")->where('id',1);
// 	})
// 	->limit(1)
// 	->get();
// var_dump($daniia->sql);
// var_dump($r);
// echo "<hr>";
