<?php

/* 

   CREATE DATABASE daniia;

   CREATE SCHEMA daniia;

   CREATE TABLE daniia.personas (
         _id serial NOT NULL,
         nombre text NOT NULL,
         apellido text,
         ci text NOT NULL,
         CONSTRAINT id_pk PRIMARY KEY (_id)
   );

   CREATE TABLE daniia.oficina (
         _id serial NOT NULL,
         _id_personas integer,
         oficina text,
         CONSTRAINT id_pk_oficina PRIMARY KEY (_id)
   );

 */


// foreach ([
//    "USER" =>"root",
//    "PASS" =>"",
//    "SCHEMA" =>"daniia",
//    "DSN" =>"mysql:port=3306;host=localhost;dbname=daniia",
// ] as $key => $value) {
//    define(strtoupper($key),$value);
// }

foreach ([
   "USER" =>"postgres",
   "PASS" =>"123",
   "SCHEMA" =>"daniia",
   "DSN" =>"pgsql:port=5432;host=localhost;dbname=daniia",
] as $key => $value ) {
   define(strtoupper($key),$value);
}

require("Daniia/Daniia.php");
require("Daniia/BaseDaniia.php");

use Daniia\Daniia;
use Daniia\BaseDaniia;

class Personas extends Daniia {
   // El nombre de la tabla es el minmo nombre de la clase
   // en caso contrario se puede personalizar con la 
   // propiedad "protected $table";
   # protected $table = "nameTableCustom";
   
   // 
   # protected $primaryKey = "_id";
}

class Oficina extends Daniia {
   // El nombre de la tabla es el minmo nombre de la clase
   // en caso contrario se puede personalizar con la 
   // propiedad "protected $table";
   # protected $table = "nameTableCustom";
   
   // 
   # protected $primaryKey = "_id";
}

$transaction = new Daniia;
$daniia      = new Daniia;
$daniia1     = new Daniia;
$daniia2     = new Daniia;
$personas    = new Personas;
$oficina     = new Oficina;


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
//    ->insert(["_id_personas"=>$daniia->lastId(),"oficina"=>"Oficina ".$daniia->lastId()]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->insertGetId(["ci"=>date("Ymdms"),"nombre"=>"Carlos","apellido"=>"Garcia"]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("oficina")
//    ->insert(["_id_personas"=>$daniia->lastId(),"oficina"=>"Oficina ".$daniia->lastId()]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
################    BEGINTRANSACTION, BEGIN, COMMIT, ROLLBACK     #################
###################################################################################
###################################################################################

// $transaction->begin();
//    $daniia1->table("personas")->truncate();
//    $daniia2->table("oficina")->truncate();
// $transaction->rollback();
// $transaction->commit();


// $transaction->begin();
//    $daniia1
//       ->table("personas")
//       ->insertGetId(["ci"=>1,"nombre"=>"Carlos","apellido"=>"Garcia","otros"=>"otros"]);
//    var_dump( $daniia1->lastQuery(), $daniia1->getData(), $daniia1->error() );
//    echo "<hr>";

//    $daniia2
//       ->table("oficina")
//       ->insert(["_id_personas"=>$daniia1->lastId(),"oficina"=>"Oficina ".$daniia1->lastId()]);
//    var_dump( $daniia2->lastQuery(), $daniia2->getData(), $daniia2->error() );
//    echo "<hr>";
// $transaction->rollback();
// $transaction->commit();


// $transaction->begin();
//    $personas
//       ->table("personas")
//       ->insertGetId(["ci"=>1,"nombre"=>"Carlos","apellido"=>"Garcia","otros"=>"otros"]);
//    var_dump( $personas->lastQuery(), $personas->rowCount(), $personas->error() );
//    echo "<hr>";

//    $oficina
//       ->table("oficina")
//       ->insert(["_id_personas"=>$personas->lastId(),"oficina"=>"Oficina ".$personas->lastId()]);
//    var_dump( $oficina->lastQuery(), $oficina->rowCount(), $oficina->error() );
//    echo "<hr>";
// // $transaction->rollback();
// $transaction->commit();



###################################################################################
###################################################################################
#############################         UPDATE        ###############################
###################################################################################
###################################################################################
// $daniia
//    ->table("personas")
//    ->update(["ci"=>"1111111","nombre"=>"aaaa","apellido"=>"aaaa","otro"=>"otro"], true/*Ignorar primaryKey*/); // Se actualizaran todos los campos
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("ci",1)
//    ->update(["ci"=>"1111111","nombre"=>"aaaa","apellido"=>"aaaa","otro"=>"otro"]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->update(["_id"=>1,"ci"=>"1111111","nombre"=>"aaaa","apellido"=>"aaaa","otro"=>"otro"]); // toma la primaryKey "_id"
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("ci",1111111)
//    ->update(["_id"=>1,"ci"=>"1111111","nombre"=>"aaaa","apellido"=>"aaaa","otro"=>"otro"]); // toma la primaryKey "_id"
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",1)
//    ->update([
//       ["ci"=>4,"nombre"=>"Petra","apellido"=>"","otros"=>"otros"],
//       ["ci"=>5,"nombre"=>"José","apellido"=>"Jill"],
//       ["ci"=>6,"nombre"=>"Jhon","apellido"=>"Peña","otros"=>"otros"],
//    ]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->primaryKey("_id")
//    ->where(1,1)
//    ->update([
//       ["_id"=>2,"ci"=>4,"nombre"=>"Petra","apellido"=>"","otros"=>"otros"],
//       ["_id"=>3,"ci"=>5,"nombre"=>"José","apellido"=>"Jill"],
//       ["_id"=>4,"ci"=>6,"nombre"=>"Jhon","apellido"=>"Peña","otros"=>"otros"],
//    ]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function(Daniia $daniia) {
//       $daniia->where("_id",1);
//    })
//    ->update(["ci"=>"1111111","nombre"=>"aaaa","apellido"=>"aaaa","otro"=>"otro"]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function(Daniia $daniia) {
//       $daniia
//          ->where("_id",1)
//          ->orWhere("ci",1111111);
//    })
//    ->update(["_id"=>1,"ci"=>"1111111","nombre"=>"aaaa","apellido"=>"aaaa","otro"=>"otro"]); // toma la primaryKey "_id"
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
//    ->where("_id",2)
//    ->delete();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->delete( ["_id"=>2] );
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->delete( ["_id <>"=>2] );
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->delete([3]); // Toma por defecto la primaryKey "_id"
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id') // Cambiamos la predeterminada primaryKey "_id" por "_id"
//    ->table("personas")
//    ->where("_id",4)
//    ->delete(1);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table("personas")
//    ->delete(1,4);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id') // se ignora
//    ->table("personas")
//    ->where("_id",[5,6,7])
//    ->delete(); // Ignora el primaryKey
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table("personas")
//    ->find([5,6,7])
//    ->delete();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table("personas")
//    ->find([11,12,13])
//    ->delete();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",10)
//    ->orWhere("_id",14)
//    ->delete(); // Ignora el primaryKey
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
#############################         QUERY          ##############################
###################################################################################
###################################################################################

// $daniia->query('SELECT * FROM daniia.personas LIMIT 1');
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia->queryArray('SELECT * FROM daniia.personas LIMIT 1');
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia->query('SELECT * FROM daniia.personas LIMIT 1', function( $data, Daniia $daniia ) {
//    return @$data[0];
// });
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia->queryArray('SELECT * FROM daniia.personas LIMIT 1', function( $data, Daniia $daniia ) {
//    return @$data[0];
// });
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";




###################################################################################
###################################################################################
#############################         SELECT         ##############################
###################################################################################
###################################################################################
// $daniia
//    ->select('TRUE AS boolean ')
//    ->getArray();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->select('TRUE AS boolean ')
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

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

// $daniia
//    ->select(
//       function( $query ) {
//          $query
//             ->select('TRUE AS boolean1');
//       }
//       ,function( $query ) {
//          $query
//             ->table('personas')
//             ->select('TRUE AS boolean2')
//             ->where('TRUE')
//             ->limit(1);
//       }
//       ,function( $query ) {
//          $query
//             ->case('1')
//             ->when('1', TRUE)
//             ->when('2', FALSE);
//       }
//    )
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

###################################################################################
###################################################################################
###########################          ROWCOUNT         #############################
###################################################################################
###################################################################################

// $daniia
//    ->table('personas')
//    ->select('_id')
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->rowCount(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->rowCount(), $daniia->error() );
// echo "<hr>";

// $personas
//    ->first();
//    var_dump( $personas->lastQuery(), $personas->rowCount(), $personas->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->insert([
//       ["ci"=>2,"nombre"=>"Carlos","apellido"=>"Garcia","otros"=>"otros"],
//       ["ci"=>3,"nombre"=>"Carlos","apellido"=>"Garcia"],
//       ["ci"=>4,"nombre"=>"Carlos","apellido"=>"Garcia","otros"=>"otros"],
//       ["ci"=>4,"nombre"=>"Carlos","apellido"=>"Garcia","otros"=>"otros"],
//    ]);
// var_dump( $daniia->lastQuery(), $daniia->rowCount(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->update(["ci"=>"1111111","nombre"=>"aaaa","apellido"=>"aaaa","otro"=>"otro"], true/*Ignorar primaryKey*/); // Se actualizaran todos los campos
// var_dump( $daniia->lastQuery(), $daniia->rowCount(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->update(["_id"=>107,"ci"=>"1111111","nombre"=>"aaaa","apellido"=>"aaaa","otro"=>"otro"]); // Se actualizaran todos los campos
// var_dump( $daniia->lastQuery(), $daniia->rowCount(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->update([
//       ["_id"=>107,"ci"=>4,"nombre"=>"Petra","apellido"=>"","otros"=>"otros"],
//       ["_id"=>108,"ci"=>5,"nombre"=>"José","apellido"=>"Jill"],
//       ["_id"=>109,"ci"=>6,"nombre"=>"Jhon","apellido"=>"Peña","otros"=>"otros"],
//    ]);
// var_dump( $daniia->lastQuery(), $daniia->rowCount(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->delete( ["_id"=>107] );
// var_dump( $daniia->lastQuery(), $daniia->rowCount(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->delete([108,109]); // Toma por defecto la primaryKey "_id"
// var_dump( $daniia->lastQuery(), $daniia->rowCount(), $daniia->error() );
// echo "<hr>";

###################################################################################
###################################################################################
###########################    OPERADORES VALIDOS     #############################
###################################################################################
###################################################################################

// $daniia
//    ->table("personas")
//    ->where("_id","4") // POR DEFECTO ES '='
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("oficina")
//    ->where("_id",'=',"2")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'<',"4")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'<=',"4")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'>',"4")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'>=',"4")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'<>',"4")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'!=',"4")
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
//    ->where("_id",["4"]) // si es un array por default es 'IN'
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'IN',["4"])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'IS',"NULL",false) // el parametro 'false' indica no escapar valor
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'IS',NULL) // el parametro 'false' indica no escapar valor
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'IS NOT',"NULL",false) // el parametro 'false' indica no escapar valor
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id ",NULL)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'BETWEEN',[3,5]) // el parametro 'false' indica no escapar valor
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'NOT BETWEEN',[3,5]) // el parametro 'false' indica no escapar valor
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
//    var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->from(['personas','oficina'])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->from(['personas','oficina'])
//    ->where('personas._id','oficina._id_personas',false)
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
//    ->select("_id","nombre","apellido")
//    ->from(function (Daniia $daniia) {
//       $daniia
//          ->table("personas")
//          ->select("personas._id","personas.nombre","personas.apellido");
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->select("_id_personas","id_oficinas")
//    ->from(function (Daniia $daniia) {
//       $daniia
//          ->table("oficina")
//          ->select("_id_personas","_id AS id_oficinas")
//          ->where("_id_personas",'<=',4);
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
//    ->select("_id_personas","id_oficinas")
//    ->from(function (Daniia $daniia) {
//       $daniia
//          ->table("oficina")
//          ->select("_id_personas","_id AS id_oficinas")
//          ->where("_id_personas",'<=',4);
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
//    ->where('personas._id','oficina._id_personas',false)
//    ->first();
// var_dump( $personas->lastQuery(), $personas->getData(), $personas->error() );
// echo "<hr>";

// $daniia
//    ->table('personas','oficina') // JOIN explicito
//    ->where('personas._id','oficina._id_personas',false)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->join("oficina","personas._id","oficina._id_personas")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->join("oficina","personas._id","=","oficina._id_personas")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->leftJoin("oficina","personas._id","=","oficina._id_personas")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->rightJoin("oficina","personas._id","=","oficina._id_personas")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->rightJoin("oficina","personas._id",[1,2,3,4])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->rightJoin("oficina",['personas._id'=>[4,5,6,7]])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->rightJoin("oficina",['personas._id <>'=> 4])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->join("oficina a","personas._id","a._id_personas")
//    ->leftJoin("oficina b","personas._id","b._id_personas")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->join('oficina',"personas._id",function(Daniia $query) {
//       $query
//          ->select("personas._id")
//          ->from("personas")
//          ->where("personas._id","4")
//          ->limit(1);
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->join("oficina",function(Daniia $daniia) {
//       $daniia
//          ->on("personas._id",1)
//          ->on("personas._id",2)
//          ->on("personas._id",3)
//          ->on("personas._id",[1,2,3,4])
//          ->on("personas._id","oficina._id_personas")

//          ->on( ["personas._id"=>"oficina._id_personas"] )
//          ->on( ["personas._id <>"=>"oficina._id_personas"] )

//          ->orOn("personas._id",'<>',"oficina._id_personas")
//          ->andOn("personas._id",1,true) //Escapamos el valor
//          ->orOn("personas._id","<>","oficina._id_personas");
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->join("oficina alias_a",function(Daniia $daniia) {
//       $daniia
//          ->on(function(Daniia $daniia) {
//             $daniia
//                ->on("personas._id","alias_a._id_personas");
//          });
//    })
//    ->join("oficina alias_b",function(Daniia $daniia) {
//       $daniia
//          ->on("personas._id",'=',function(Daniia $daniia) {
//             $daniia
//                ->table('oficina')
//                ->select('_id_personas')
//                ->where('_id_personas', 2);
//          });
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->join("oficina",function(Daniia $daniia) {
//       $daniia
//          ->on("personas._id",function(Daniia $daniia) {
//             $daniia
//                ->select("personas._id")
//                ->from("personas")
//                ->where("personas._id","4")
//                ->limit(1);
//          });
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
##############################     GET, GETARRAY     ##############################
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
//    ->get(function($data, Daniia $daniia) {
//       return $data;
//    });
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->getArray(function($data, Daniia $daniia) {
//       return $data;
//    });
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";




###################################################################################
###################################################################################
#################################      LISTS     ##################################
###################################################################################
###################################################################################
// $daniia
//    ->table('PERSONAS')
//    ->lists('NOMBRE');
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->lists('nombre','_ID');
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->lists('nombre',function($data, Daniia $daniia) {
//       return $data;
//    });
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->lists('nombre','_id',function($data, Daniia $daniia) {
//       return $data;
//    });
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";




###################################################################################
###################################################################################
##########################             FIND            ############################
###################################################################################
###################################################################################
// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->find(); // Consulta Todos
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->where('_id',3)
//    ->find();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->find(3);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->find(1,2);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->find(['_id'=>3,'apellido'=>'Garcia']); // Ignora la primaryKey
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->find(['_id !='=>1,'nombre LIKE'=> '%CARLOS%']);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->find([3]);
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// var_dump($daniia->_id);
// var_dump($daniia->ci);
// var_dump($daniia->nombre);
// var_dump($daniia->apellido);
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->find(function($data, Daniia $daniia) {
//       return $data;
//    });
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->where('_id',3)
//    ->find(function($data, Daniia $daniia) {
//       return $data;
//    });
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->find(3,function($data, Daniia $daniia) {
//       return $data;
//    });
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->find(1,3,function($data, Daniia $daniia) {
//       return $data;
//    });
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->find(['_id'=>3,'apellido'=>'Garcia'],function($data, Daniia $daniia) {
//       return $data;
//    }); // Ignora la primaryKey
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
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
//    ->where('_id',1)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->where('_id',1)
//    ->firstArray();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->primaryKey('_id')
//    ->find([1,3])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->primaryKey('_id')
//    ->find([1,2])
//    ->firstArray();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->first(function($data, Daniia $daniia) {
//       return $data;
//    });
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->firstArray(function($data, Daniia $daniia) {
//       return $data;
//    });
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas')
//    ->primaryKey('_id')
//    ->find([1,2],function($data, Daniia $daniia) {
//       return $data;
//    })
//    ->first(function($data, Daniia $daniia) {
//       return $data;
//    });
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
##########################            SAVE             ############################
###################################################################################
###################################################################################
// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->find()
//    ->first()
// ;
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// $daniia->nombre = "yyyyyyyy";
// $daniia
//    ->save();//UPDATE
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->where('_id',1)
//    ->find()
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// $daniia->nombre = "yyyyyyyy";
// $daniia
//    ->save();//UPDATE
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->find(1)
// ;
// $daniia->nombre = "yyyyyyyy";
// $daniia
//    ->save();//UPDATE
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->find(1);
// $daniia->nombre = "XXXXXXX";
// $daniia
//    ->save();//UPDATE
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->find(1);
// $daniia->nombre = "XXXXXXX";
// $daniia
//    ->save(function($data, Daniia $daniia) {
//       // Si la actualización es exitosa devuelve los datos actualizado
//       // sino, devuelve los datos insertado
//       return $data;
//    });//UPDATE
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->find('00');// registro no existe..
// $daniia->ci       = "6456789321";
// $daniia->nombre   = "Carlos";
// $daniia->apellido = "Garcia";
// $daniia
//    ->save();//INSERT
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->primaryKey('_id')
//    ->table('personas')
//    ->find('00');// registro no existe..
// $daniia->ci       = "6456789321";
// $daniia->nombre   = "Carlos";
// $daniia->apellido = "Garcia";
// $daniia
//    ->save(function($data, Daniia $daniia) {
//       return $data;
//    });//INSERT
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas');
// $daniia->ci       = "8795646";
// $daniia->nombre   = "Carlos";
// $daniia->apellido = "Garcia";
// $daniia->otros    = "otros";
// $daniia
//    ->save();//INSERT
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table('personas');
// $daniia->ci       = "8795646";
// $daniia->nombre   = "Carlos";
// $daniia->apellido = "Garcia";
// $daniia
//    ->save(function($data, Daniia $daniia) {
//       return $data;
//    });//UPDATE
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";



###################################################################################
###################################################################################
##########################            ORDER            ############################
###################################################################################
###################################################################################
// $daniia
//    ->table("personas")
//    ->orderBy("_id")
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->orderBy(["_id",'desc'])
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
##########################        LIMIT, OFFSET        ############################
###################################################################################
###################################################################################
// $daniia
//    ->table("personas")
//    ->limit("1")
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->limit("1","0")
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

// $daniia
//    ->table("personas")
//    ->limit("1")
//    ->offset("2")
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
//    ->where(TRUE)
//    ->orWhere(FALSE)
//    ->where(1,1)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("nombre",'ilike',"%carlos%")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id","_id")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'=',"2")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'=',"4")
//    ->andWhere("_id",'=',"4",false)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",4)
//    ->andWhere("_id",4,false)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'=',4)
//    ->orWhere("_id",'=',4)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",[4,5,6,7])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'in',[4,5,6,7])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",1)
//    ->orWhere("_id",2)
//    ->orWhere("_id",NULL)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'=',1)
//    ->orWhere("_id",'=',2)
//    ->orWhere("_id",'=',NULL)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",[1,2,NULL])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function (Daniia $daniia) {
//       $daniia
//          ->where("_id",2)
//          ->andWhere("apellido","iLIKE","%garcia%");
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function (Daniia $daniia) {
//       $daniia
//          ->where("_id",4)
//          ->orWhere("apellido","LIKE","%Garcia%");
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function (Daniia $daniia) {
//       $daniia
//          ->where("_id",4);
//    })
//    ->orWhere(function (Daniia $daniia){
//       $daniia
//          ->where("_id",4);
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function (Daniia $daniia) {
//       $daniia
//          ->where("_id","2")
//          ->orWhere(function (Daniia $daniia) {
//             $daniia
//                ->where("_id","3")
//                ->andWhere(function (Daniia $daniia) {
//                   $daniia
//                      ->where("_id","4");
//                });
//          });
//    })
//    ->orWhere(function (Daniia $daniia){
//       $daniia
//          ->where("_id","5");
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("personas._id",function($query) {
//       $query
//          ->table("personas")
//          ->select("_id")
//          ->where("_id",4)
//          ->limit(1)
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("personas._id","=",function($query) {
//       $query
//          ->table("personas")
//          ->select("_id")
//          ->where("_id",4)
//          ->limit(1);
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where("_id",'in',function(Daniia $daniia){
//       $daniia
//          ->table('personas')
//          ->select('_id');
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
//    ->where(['_id'=>[4,5,6,7]])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(['_id in'=>[4,5,6,7]])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(["personas._id"=>function($query) {
//       $query
//          ->table("personas")
//          ->select( '_id' )
//          ->where("_id",4)
//          ->limit(1);
//    }])->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(["personas._id !="=>function($query) {
//       $query
//          ->table("personas")
//          ->select( '_id' )
//          ->where("_id",'_id')
//          ->limit(1);
//    }])->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";


###################################################################################
###################################################################################
##########################       CASE, WHEN, ELSE      ############################
###################################################################################
###################################################################################

// $daniia
//    ->table("personas")
//    ->where(function($query) {
//       $query
//          ->case('1')
//          ->when('1', TRUE)
//          ->when('2', FALSE)
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function($query) {
//       $query
//          ->table("personas")
//          ->case('_id') #<- si indica la tabla se escapa automaticamente
//          ->when('1', TRUE)
//          ->when('2', FALSE)
//          ->when('_id', NULL) #<- si indica la tabla se escapa automaticamente
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function($query) {
//       $query
//          // ->case(TRUE,TRUE)
//          ->case(1,'=',1)
//          ->when(TRUE, TRUE)
//          ->when(FALSE, FALSE)
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function($query) {
//       $query
//          ->case(function($query) {
//             $query
//                ->select( 'TRUE' );
//          })
//          ->when(TRUE, TRUE)
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function($query) {
//       $query
//          ->case(function($query) {
//             $query
//                ->select( '2' );
//          },function($query) {
//             $query
//                ->select( '2' );
//          })
//          ->when(TRUE, TRUE)
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function($query) {
//       $query
//          ->when(TRUE, TRUE)
//          ->when('1', FALSE)
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function($query) {
//       $query
//          ->table("personas")
//          ->when('_id', 1, TRUE) #<- si indica la tabla se escapa automaticamente
//          ->when('FALSE', FALSE, 'true', TRUE)
//          ->when(FALSE, '<>', NULL, TRUE)
//          ->when('FALSE', '=', NULL, TRUE)
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(TRUE, function($query) {
//       $query
//          ->when(TRUE, TRUE, TRUE)
//          ->when(FALSE, FALSE, 'true', TRUE)
//          ->when('FALSE', '<>', NULL, TRUE)
//          ->when('FALSE', '=', NULL, 'TRUE',false)
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";


// $daniia
//    ->table("personas")
//    ->where(function($query) {
//       $query
//          ->when(function($query) {
//             $query
//                ->select( 'TRUE' );
//          }, TRUE)
//          ->when(function($query) {
//             $query
//                ->select( 'FALSE' );
//          }, TRUE)
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function($query) {
//       $query
//          ->when(function($query) {
//             $query
//                ->select( 'TRUE' );
//          },'_id' , TRUE, FALSE, FALSE)
//          ->when(function($query) {
//             $query
//                ->select( 'FALSE' );
//          },'=','_id' , TRUE, FALSE, FALSE)
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function($query) {
//       $query
//          ->table("personas")
//          ->when('_id',function($query) {
//             $query
//                ->select( '1' );
//          }, TRUE)
//          ->when('_id','=',function($query) {
//             $query
//                ->select( '2' );
//          }, TRUE)
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function($query) {
//       $query
//          ->when(function($query) {
//             $query
//                ->select('TRUE');
//          }, function($query) {
//             $query
//                ->select('TRUE');
//          }, function($query) {
//             $query
//                ->select('TRUE');
//          })
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function($query) {
//       $query
//          ->case('1')
//          ->else(TRUE)
//          ->when('2', FALSE)
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where(function($query) {
//       $query
//          ->case('1')
//          ->when('2', FALSE)
//          ->else(function($query) {
//             $query
//                ->select("TRUE")
//             ;
//          })
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

###################################################################################
###################################################################################
##########################            HAVING           ############################
###################################################################################
###################################################################################
//['=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like', 'in']

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
//    ->select( "_id" )
//    ->groupBy( "_id" )
//    ->having("_id","2")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "_id" )
//    ->groupBy( "_id" )
//    ->having("_id",'=',"3")
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "_id" )
//    ->groupBy( "_id" )
//    ->having("_id",'=',"4")
//    ->andHaving("_id",'=',"4",false)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "_id" )
//    ->groupBy( "_id" )
//    ->having("_id",4)
//    ->orHaving("_id",4,false)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "_id" )
//    ->where("_id",'=',4)
//    ->groupBy( "_id" )
//    ->having("_id",'=',4)
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "_id" )
//    ->groupBy( "_id" )
//    ->having("_id",[4,5,6,7])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "_id" )
//    ->groupBy( "_id" )
//    ->having("_id",'in',[4,5,6,7])
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
// ->table("personas")
// ->groupBy( "_id" )
// ->having(FALSE,FALSE)
// ->having(TRUE,TRUE)
// ->having("_id",NULL)
// ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
// ->table("personas")
// ->groupBy( "_id" )
// ->having(FALSE,'=',FALSE)
// ->having(TRUE,'=',TRUE)
// ->having('_id','IS NOT',NULL)
// ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
// ->table("personas")
// ->groupBy( "_id" )
// ->having(TRUE,[TRUE,FALSE,NULL])
// ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "_id",'apellido' )
//    ->groupBy( "_id",'apellido' )
//    ->having(function (Daniia $daniia) {
//       $daniia
//          ->having("_id",4)
//          ->andHaving("apellido","ILIKE","%garcia%");
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $param = ["_id",'apellido'];
// $daniia
//    ->table("personas")
//    ->select( $param )
//    ->groupBy( $param )
//    ->having(function (Daniia $daniia) {
//       $daniia
//          ->having("_id",4)
//          ->orHaving("apellido","ILIKE","%garcia%");
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "_id",'apellido' )
//    ->groupBy( "_id",'apellido' )
//    ->having(function (Daniia $daniia) {
//       $daniia
//          ->having("_id",4);
//    })
//    ->orHaving(function (Daniia $daniia){
//       $daniia
//          ->having("_id",4);
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "_id" )
//    ->groupBy( "_id" )
//    ->having(function (Daniia $daniia) {
//       $daniia
//          ->having("_id","2")
//          ->orHaving(function (Daniia $daniia) {
//             $daniia
//                ->having("_id","3")
//                ->andHaving(function (Daniia $daniia) {
//                   $daniia
//                      ->having("_id","4");
//                });
//          });
//    })
//    ->orHaving(function (Daniia $daniia){
//       $daniia
//          ->having("_id","4");
//    })
//    ->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "_id" )
//    ->groupBy( "_id" )
//    ->having("personas._id",function($query) {
//       $query
//          ->table("personas")
//          ->select("_id")
//          ->groupBy( "_id" )
//          ->having("_id",4)
//          ->limit(1)
//       ;
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "_id" )
//    ->groupBy( "_id" )
//    ->having("personas._id","=",function($query) {
//       $query
//          ->table("personas")
//          ->select("_id")
//          ->groupBy("_id")
//          ->having("_id",4)
//          ->limit(1);
//    })->first();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select( "_id" )
//    ->groupBy( "_id" )
//    ->having("_id",'in',function(Daniia $daniia){
//       $daniia
//          ->table('personas')
//          ->select('_id');
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
//    ->where('_id',2) 
//    ->union(function (Daniia $daniia) {
//       $daniia
//          ->table("personas")
//          ->where('_id',2);
//    })
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select('_id')
//    ->union(function (Daniia $daniia) {
//       $daniia
//          ->table("oficina")
//          ->select('_id_personas AS _id');
//    })
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->select('_id')
//    ->where('_id',2)
//    ->union(function (Daniia $daniia) {
//       $daniia
//          ->table("oficina")
//          ->select('_id_personas AS _id')
//          ->where('_id_personas',4);
//    })
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";

// $daniia
//    ->table("personas")
//    ->where('_id',2)
//    ->union(function (Daniia $daniia) {
//       $daniia
//          ->table("personas")
//          ->where('_id',2);
//    })
//    ->union(function (Daniia $daniia) {
//       $daniia
//          ->table("personas")
//          ->where('_id',2);
//    })
//    ->limit(2)
//    ->get();
// var_dump( $daniia->lastQuery(), $daniia->getData(), $daniia->error() );
// echo "<hr>";
