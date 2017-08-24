<?php

/* 

   CREATE DATABASE daniia;

   CREATE SCHEMA daniia;

   CREATE TABLE daniia.personas (
         id serial NOT NULL,
         nombre text NOT NULL,
         apellido text,
         ci text NOT NULL,
         CONSTRAINT id_pk PRIMARY KEY (id)
   );

   CREATE TABLE daniia.oficina (
         id serial NOT NULL,
         id_personas integer,
         oficina text,
         CONSTRAINT id_pk_oficina PRIMARY KEY (id)
   );

 */


// foreach ([
//           "USER" =>"root",
//           "PASS" =>"",
//           "SCHEMA" =>"daniia",
//           "DSN" =>"mysql:port=3306;host=localhost;dbname=daniia",
//        ] as $key => $value
// ) { define(strtoupper($key),$value); }

foreach ([
   "USER" =>"postgres",
   "PASS" =>"123",
   "SCHEMA" =>"daniia",
   "DSN" =>"pgsql:port=5432;host=localhost;dbname=daniia",
   ]
as $key => $value
) { define(strtoupper($key),$value); }

require("Daniia/Daniia.php");
require("Daniia/BaseDaniia.php");

use Daniia\Daniia;
use Daniia\BaseDaniia;

class Personas extends BaseDaniia {
   // El nombre de la tabla es el minmo nombre de la clase
   // en caso contrario se puede personalizar con la 
   // propiedad "protected $table";
   # protected $table = "nameTableCustom";
   
   // 
   # protected $primaryKey = "id";
}

$daniia   = new Daniia;
$personas = new Personas;


###################################################################################
###################################################################################
#############################         COLUMNS        ##############################
###################################################################################
###################################################################################
// $columns = $daniia->columns('personas');
// var_dump($columns);
// echo "<hr>";

// $columns = $daniia->table('personas')->columns();
// var_dump($columns);
// echo "<hr>";



###################################################################################
###################################################################################
#############################        TRUNCATE        ##############################
###################################################################################
###################################################################################
// $daniia->table("personas")->truncate();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia->table("oficina")->truncate();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
#############################         INSERT         ##############################
###################################################################################
###################################################################################
// $daniia
//    ->table("personas")
//    ->insert(["ci"=>1,"nombre"=>"Carlos","apellido"=>"Garcia","otros"=>"otros"]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->insert([
//       ["ci"=>2,"nombre"=>"Carlos","apellido"=>"Garcia","otros"=>"otros"],
//       ["ci"=>3,"nombre"=>"Carlos","apellido"=>"Garcia"],
//       ["ci"=>4,"nombre"=>"Carlos","apellido"=>"Garcia","otros"=>"otros"],
//    ]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->insertGetId(["ci"=>date("Ymdms"),"nombre"=>"Carlos","apellido"=>"Garcia"]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("oficina")
//    ->insert(["id_personas"=>$daniia->lastId(),"oficina"=>"Oficina ".$daniia->lastId()]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->insertGetId(["ci"=>date("Ymdms"),"nombre"=>"Carlos","apellido"=>"Garcia"]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("oficina")
//    ->insert(["id_personas"=>$daniia->lastId(),"oficina"=>"Oficina ".$daniia->lastId()]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
#############################         UPDATE        ###############################
###################################################################################
###################################################################################
// $daniia
//    ->table("personas")
//    ->update(["ci"=>"1111111","nombre"=>"aaaa","apellido"=>"aaaa","otro"=>"otro"]); // Se actualizaran todos los campos
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",1)
//    ->update(["ci"=>"1111111","nombre"=>"aaaa","apellido"=>"aaaa","otro"=>"otro"]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->update(["id"=>1,"ci"=>"1111111","nombre"=>"aaaa","apellido"=>"aaaa","otro"=>"otro"]); // toma la primaryKey "id"
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("ci",1111111)
//    ->update(["id"=>1,"ci"=>"1111111","nombre"=>"aaaa","apellido"=>"aaaa","otro"=>"otro"]); // toma la primaryKey "id"
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",1)
//    ->update([
//       ["ci"=>4,"nombre"=>"Petra","apellido"=>"","otros"=>"otros"],
//       ["ci"=>5,"nombre"=>"José","apellido"=>"Jill"],
//       ["ci"=>6,"nombre"=>"Jhon","apellido"=>"Peña","otros"=>"otros"],
//    ]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->primaryKey("id")
//    ->where(1,1)
//    ->update([
//       ["id"=>2,"ci"=>4,"nombre"=>"Petra","apellido"=>"","otros"=>"otros"],
//       ["id"=>3,"ci"=>5,"nombre"=>"José","apellido"=>"Jill"],
//       ["id"=>4,"ci"=>6,"nombre"=>"Jhon","apellido"=>"Peña","otros"=>"otros"],
//    ]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function(Daniia $daniia) {
//       $daniia->where("id",1);
//    })
//    ->update(["ci"=>"1111111","nombre"=>"aaaa","apellido"=>"aaaa","otro"=>"otro"]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function(Daniia $daniia) {
//       $daniia
//          ->where("id",1)
//          ->orWhere("ci",1111111);
//    })
//    ->update(["id"=>1,"ci"=>"1111111","nombre"=>"aaaa","apellido"=>"aaaa","otro"=>"otro"]); // toma la primaryKey "id"
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
#############################         DELETE        ###############################
###################################################################################
###################################################################################
// $daniia
//    ->table("personas")
//    ->delete(); // Eliminar todo los registros de la tabla
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",2)
//    ->delete();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->delete( ["id"=>2] );
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->delete( ["id <>"=>2] );
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->delete([3]); // Toma por defecto la primaryKey "id"
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('id') // Cambiamos la predeterminada primaryKey "id" por "id"
//    ->table("personas")
//    ->where("id",4)
//    ->delete(1);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('id')
//    ->table("personas")
//    ->delete(1,4);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('id') // se ignora
//    ->table("personas")
//    ->where("id",[5,6,7])
//    ->delete(); // Ignora el primaryKey
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('id')
//    ->table("personas")
//    ->find([5,6,7])
//    ->delete();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('id')
//    ->table("personas")
//    ->find([11,12,13])
//    ->delete();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",10)
//    ->orWhere("id",14)
//    ->delete(); // Ignora el primaryKey
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
#############################         SELECT         ##############################
###################################################################################
###################################################################################
// $daniia
//    ->table('personas')
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->select('COUNT(*) AS registros')
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->select('ci','nombre')
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->select(['ci','nombre'])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->select('ci AS cedula',"CONCAT(nombre, ' ', apellido) AS nomapel")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->select(['ci AS cedula',"CONCAT(nombre, ' ', apellido) AS nomapel"])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
###########################    OPERADORES VALIDOS     #############################
###################################################################################
###################################################################################
// $daniia
//    ->table("personas")
//    ->where("id","4") // POR DEFECTO ES '='
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("oficina")
//    ->where("id",'=',"2")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",'<',"4")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",'<=',"4")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",'>',"4")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",'>=',"4")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",'<>',"4")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",'!=',"4")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("nombre",'LIKE',"%Car%")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("nombre",'NOT LIKE',"%los%")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("nombre",'ILIKE',"%carlos%")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("nombre",'NOT ILIKE',"%carlos%")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",["4"]) // si es un array por default es 'IN'
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",'IN',["4"])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",'IS',"TRUE",false) // el parametro 'false' indica no escapar valor
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id IS TRUE") // el parametro 'false' indica no escapar valor
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",'IS NOT',"NULL",false) // el parametro 'false' indica no escapar valor
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id IS NOT NULL") // el parametro 'false' indica no escapar valor
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",'BETWEEN',[3,5]) // el parametro 'false' indica no escapar valor
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",'NOT BETWEEN',[3,5]) // el parametro 'false' indica no escapar valor
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
###########################          TABLE            #############################
###################################################################################
###################################################################################
// $daniia
//    ->table('personas')
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas','oficina')
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table(['personas'])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table(['personas','oficina'])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
###########################           FROM            #############################
###################################################################################
###################################################################################
// $daniia
//    ->table('personas')
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->from('personas')
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->from(['personas'])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->from('personas','oficina')
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $personas
//    ->from('oficina')
//    ->first();
// var_dump( $personas->lastQuery(), $personas->getData() );
// echo "<hr>";

// $daniia
//    ->from(['personas','oficina'])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->from(['personas','oficina'])
//    ->where('personas.id=oficina.id_personas')
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->from(function (Daniia $daniia) {
//       $daniia
//          ->table("personas");
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->from(function (Daniia $daniia) {
//       $daniia
//          ->table("personas");
//    },"AliasForFROM")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->select("id","nombre","apellido")
//    ->from(function (Daniia $daniia) {
//       $daniia
//          ->table("personas")
//          ->select("personas.id","personas.nombre","personas.apellido");
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->select("id_personas","id_oficinas")
//    ->from(function (Daniia $daniia) {
//       $daniia
//          ->table("oficina")
//          ->select("id_personas","id AS id_oficinas")
//          ->where("id_personas",'<=',4);
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->from(function (Daniia $daniia) {
//       $daniia
//          ->from(function (Daniia $daniia) {
//             $daniia
//                ->from(function (Daniia $daniia) {
//                   $daniia
//                      ->table("personas");
//             },"C");
//          },"B");
//    },"A")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->select("id_personas","id_oficinas")
//    ->from(function (Daniia $daniia) {
//       $daniia
//          ->table("oficina")
//          ->select("id_personas","id AS id_oficinas")
//          ->where("id_personas",'<=',4);
//    })
//    ->where('TRUE')
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
##########################  JOIN, LEFTJOIN, RIGHTJOIN  ############################
###################################################################################
###################################################################################
// $personas
//    ->table('oficina') // JOIN implicito
//    ->where('personas.id','oficina.id_personas',false)
//    ->first();
// var_dump( $personas->lastQuery(), $personas->getData() );
// echo "<hr>";

// $daniia
//    ->table('personas','oficina') // JOIN explicito
//    ->where('personas.id','oficina.id_personas',false)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->join("oficina","personas.id","oficina.id_personas")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->join("oficina","personas.id","=","oficina.id_personas")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->leftJoin("oficina","personas.id","=","oficina.id_personas")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->rightJoin("oficina","personas.id","=","oficina.id_personas")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->rightJoin("oficina","personas.id",[1,2,3,4])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->join("oficina a","personas.id","a.id_personas")
//    ->leftJoin("oficina b","personas.id","b.id_personas")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->join('oficina',"personas.id",function(Daniia $query) {
//       $query
//          ->select("personas.id")
//          ->from("personas")
//          ->where("personas.id","4")
//          ->limit(1);
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->join("oficina",function(Daniia $daniia) {
//       $daniia
//          ->on("personas.id",[1,2,3,4])
//          ->on("personas.id","oficina.id_personas")
//          ->orOn("personas.id",'<>',"oficina.id_personas")
//          ->andOn("personas.id","1",true)
//          ->orOn("personas.id","<>","oficina.id_personas");
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->join("oficina alias_a",function(Daniia $daniia) {
//       $daniia
//          ->on(function(Daniia $daniia) {
//             $daniia
//                ->on("personas.id","alias_a.id_personas");
//          });
//    })
//    ->join("oficina alias_b",function(Daniia $daniia) {
//       $daniia
//          ->on("personas.id",'=',function(Daniia $daniia) {
//             $daniia
//                ->table('oficina')
//                ->select('id_personas')
//                ->where('id_personas', 6);
//          });
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->join("oficina",function(Daniia $daniia) {
//       $daniia
//          ->on("personas.id",function(Daniia $daniia) {
//             $daniia
//                ->select("personas.id")
//                ->from("personas")
//                ->where("personas.id","4")
//                ->limit(1);
//          });
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
##########################     GET, GETARRAY, LIST     ############################
###################################################################################
###################################################################################
// $daniia
//    ->table('personas')
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->getArray();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->lists('nombre');
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->lists('nombre','id');
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
##########################             FIND            ############################
###################################################################################
###################################################################################
// $daniia
//    ->primaryKey('id')
//    ->table('personas')
//    ->find(2);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('id')
//    ->table('personas')
//    ->find(1,2);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('id')
//    ->table('personas')
//    ->find(['id'=>2,'apellido'=>'Garcia']); // Ignora la primaryKey
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->find(['id !='=>1,'nombre ILIKE'=> '%CARLOS%']);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('id')
//    ->table('personas')
//    ->find([2]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// var_dump($daniia->id);
// var_dump($daniia->ci);
// var_dump($daniia->nombre);
// var_dump($daniia->apellido);
// echo "<hr>";



###################################################################################
###################################################################################
##########################      FIRST, FIRSTARRAY      ############################
###################################################################################
###################################################################################
// $daniia
//    ->table('personas')
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->firstArray();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->where('id',1)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->where('id',1)
//    ->firstArray();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->primaryKey('id')
//    ->find([1,2])->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->primaryKey('id')
//    ->find([1,2])
//    ->firstArray();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
##########################            SAVE             ############################
###################################################################################
###################################################################################
// $daniia
//    ->primaryKey('id')
//    ->table('personas')
//    ->find(2)
//    ->nombre = "yyyyyyyy";
// $daniia
//    ->save();//UPDATE
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('id')
//    ->table('personas')
//    ->find(2)
//    ->first();
// $daniia
//    ->nombre = "XXXXXXX";
// $daniia
//    ->save();//UPDATE
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('id')
//    ->table('personas')
//    ->find('00')
//    ->first();// registro no existe..
// $daniia
//    ->ci       = "6456789321";
// $daniia
//    ->nombre   = "Carlos";
// $daniia
//    ->apellido = "Garcia";
// $daniia
//    ->save();//INSERT
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas');
// $daniia
//    ->ci       = "8795646";
// $daniia
//    ->nombre   = "Carlos";
// $daniia
//    ->apellido = "Garcia";
// $daniia
//    ->save();//INSERT
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
##########################            ORDER            ############################
###################################################################################
###################################################################################
// $daniia
//    ->table("personas")
//    ->orderBy("id")
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->orderBy(["id",'desc'])
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->orderBy("apellido","nombre")
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->orderBy("apellido","nombre",'desc')
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->orderBy( ["apellido","nombre"] )
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->orderBy( ["apellido","nombre",'desc'] )
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
##########################            LIMIT            ############################
###################################################################################
###################################################################################
// $daniia
//    ->table("personas")
//    ->limit("1","0")
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->limit("1")
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->limit(["1","3"])
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->limit(["1"])
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
##########################            GROUP            ############################
###################################################################################
###################################################################################
// $daniia
//    ->select('ci')
//    ->table("personas")
//    ->groupBy("ci")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->select("apellido","nombre")
//    ->table("personas")
//    ->groupBy("apellido","nombre")
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table( ["personas"] )
//    ->select( ['ci'] )
//    ->groupBy( ["ci"] )
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table( ["personas"] )
//    ->select (["apellido","nombre"] )
//    ->groupBy( ["apellido","nombre"] )
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
##########################            WHERE            ############################
###################################################################################
###################################################################################
//['=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'in']

// $daniia
//    ->table("personas")
//    ->where("nombre",'ilike',"%carlos%")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id","2")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",'=',"2")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",'=',"4")
//    ->andWhere("id",'=',"4",false)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",4)
//    ->andWhere("id",4,false)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",'=',4)
//    ->orWhere("id",'=',4)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",[4,5,6,7])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",'in',[4,5,6,7])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function (Daniia $daniia) {
//       $daniia
//          ->where("id",4)
//          ->andWhere("apellido","ILIKE","%garcia%");
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function (Daniia $daniia) {
//       $daniia
//          ->where("id",4)
//          ->orWhere("apellido","LIKE","%Garcia%");
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function (Daniia $daniia) {
//       $daniia
//          ->where("id",4);
//    })
//    ->orWhere(function (Daniia $daniia){
//       $daniia
//          ->where("id",4);
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function (Daniia $daniia) {
//       $daniia
//          ->where("id","2")
//          ->orWhere(function (Daniia $daniia) {
//             $daniia
//                ->where("id","3")
//                ->andWhere(function (Daniia $daniia) {
//                   $daniia
//                      ->where("id","4");
//                });
//          });
//    })
//    ->orWhere(function (Daniia $daniia){
//       $daniia
//          ->where("id","5");
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("personas.id",function($query) {
//       $query
//          ->table("personas")
//          ->select("id")
//          ->where("id",4)
//          ->limit(1)
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("personas.id","=",function($query) {
//       $query
//          ->table("personas")
//          ->select("id")
//          ->where("id",4)
//          ->limit(1);
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("id",'in',function(Daniia $daniia){
//       $daniia
//          ->table('personas')
//          ->select('id');
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(['nombre'=>'Carlos'])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(['nombre !='=>'carlos'])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(['id'=>[4,5,6,7]])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(['id in'=>[4,5,6,7]])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(["personas.id"=>function($query) {
//       $query
//          ->table("personas")
//          ->select( 'id' )
//          ->where("id",4)
//          ->limit(1);
//    }])->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(["personas.id !="=>function($query) {
//       $query
//          ->table("personas")
//          ->select( 'id' )
//          ->where("id",4)
//          ->limit(1);
//    }])->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
##########################            HAVING           ############################
###################################################################################
###################################################################################
////['=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'in']

// $daniia
//    ->table("personas")
//    ->select( "nombre" )
//    ->groupBy( "nombre" )
//    ->having("nombre",'like',"%Carlos%")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "id" )
//    ->groupBy( "id" )
//    ->having("id","2")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "id" )
//    ->groupBy( "id" )
//    ->having("id",'=',"3")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "id" )
//    ->groupBy( "id" )
//    ->having("id",'=',"4")
//    ->andHaving("id",'=',"4",false)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "id" )
//    ->groupBy( "id" )
//    ->having("id",4)
//    ->orHaving("id",4,false)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "id" )
//    ->where("id",'=',4)
//    ->groupBy( "id" )
//    ->having("id",'=',4)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "id" )
//    ->groupBy( "id" )
//    ->having("id",[4,5,6,7])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "id" )
//    ->groupBy( "id" )
//    ->having("id",'in',[4,5,6,7])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "id",'apellido' )
//    ->groupBy( "id",'apellido' )
//    ->having(function (Daniia $daniia) {
//       $daniia
//          ->having("id",4)
//          ->andHaving("apellido","ILIKE","%garcia%");
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $param = ["id",'apellido'];
// $daniia
//    ->table("personas")
//    ->select( $param )
//    ->groupBy( $param )
//    ->having(function (Daniia $daniia) {
//       $daniia
//          ->having("id",4)
//          ->orHaving("apellido","ILIKE","%garcia%");
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "id",'apellido' )
//    ->groupBy( "id",'apellido' )
//    ->having(function (Daniia $daniia) {
//       $daniia
//          ->having("id",4);
//    })
//    ->orHaving(function (Daniia $daniia){
//       $daniia
//          ->having("id",4);
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "id" )
//    ->groupBy( "id" )
//    ->having(function (Daniia $daniia) {
//       $daniia
//          ->having("id","2")
//          ->orHaving(function (Daniia $daniia) {
//             $daniia
//                ->having("id","3")
//                ->andHaving(function (Daniia $daniia) {
//                   $daniia
//                      ->having("id","4");
//                });
//          });
//    })
//    ->orHaving(function (Daniia $daniia){
//       $daniia
//          ->having("id","4");
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "id" )
//    ->groupBy( "id" )
//    ->having("personas.id",function($query) {
//       $query
//          ->table("personas")
//          ->select("id")
//          ->groupBy( "id" )
//          ->having("id",4)
//          ->limit(1)
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "id" )
//    ->groupBy( "id" )
//    ->having("personas.id","=",function($query) {
//       $query
//          ->table("personas")
//          ->select("id")
//          ->groupBy("id")
//          ->having("id",4)
//          ->limit(1);
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "id" )
//    ->groupBy( "id" )
//    ->having("id",'in',function(Daniia $daniia){
//       $daniia
//          ->table('personas')
//          ->select('id');
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
##########################            UNION            ############################
###################################################################################
###################################################################################
// $daniia
//    ->table("personas")
//    ->where('id',2) 
//    ->union(function (Daniia $daniia) {
//       $daniia
//          ->table("personas")
//          ->where('id',2);
//    })
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select('id')
//    ->union(function (Daniia $daniia) {
//       $daniia
//          ->table("oficina")
//          ->select('id_personas AS id');
//    })
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select('id')
//    ->where('id',2)
//    ->union(function (Daniia $daniia) {
//       $daniia
//          ->table("oficina")
//          ->select('id_personas AS id')
//          ->where('id_personas',4);
//    })
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where('id',2)
//    ->union(function (Daniia $daniia) {
//       $daniia
//          ->table("personas")
//          ->where('id',2);
//    })
//    ->union(function (Daniia $daniia) {
//       $daniia
//          ->table("personas")
//          ->where('id',2);
//    })
//    ->limit(2)
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";
