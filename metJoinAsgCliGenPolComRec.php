<?php
/**
 * Created by PhpStorm.
 * User: Sinerconsult
 * Date: 08-04-2016
 * Time: 11:42 AM
 */

foreach ([
			 "USER" =>"postgres",
			 "PASS" =>"123",
			 "SCHEMA" =>"gestiondelcorredor",
			 "DSN" =>"pgsql:port=5432;host=localhost;user=postgres;dbname=sitiotes_gdc",
		 ] as $key => $value
) { define(strtoupper($key),$value); }

require("Daniia.php");

use Daniia\Daniia;

function met_registrosJoin_Asg_Cli_Gen_Pol_Com_Rec_Gen($cedrif, $cirif, $fecha, $nombrecliente, $rif, $discriminante, $tipovencimiento, $idpoliza=null, $mylim=null, $myoffset=null) {

	$daniia = new Daniia();
	$daniia->from(function(Daniia $daniia){
		$daniia->table('ASEGURA');
		$daniia->select(
			'ASEGURA.cedrif'
			,'NULL AS rif'
			,'CLIENTENATURAL.cirif AS cirif'
			,'CLIENTENATURAL.nombre AS nombre'
			,'CLIENTENATURAL.apellido AS apellido'
			,'ASEGURA.idpoliza AS idpoliza'
			,'POLIZA.discriminante AS discriminante'
			,'COMPANIA.razon AS razon'
			,'GENERA.idrecibo AS idrecibo'
			,"to_char(RECIBO.fvigini, 'DD/MM/YYYY') AS fvigini"
			,"to_char(RECIBO.fvigfin, 'DD/MM/YYYY') AS fvigfin"
			,"to_char(RECIBO.fvigcobro, 'DD/MM/YYYY') AS fvigcobro"
			,'RECIBO.prima AS prima');
		$daniia->join('CLIENTENATURAL','ASEGURA.cirif','CLIENTENATURAL.cirif');
		$daniia->join('GENERA','ASEGURA.idpoliza','GENERA.idpoliza');
		$daniia->join('COMPANIA','GENERA.rif','COMPANIA.rif');
		$daniia->join('POLIZA','GENERA.idpoliza','POLIZA.idpoliza');
		$daniia->join('RECIBO','GENERA.idrecibo','RECIBO.idrecibo');
		$daniia->andWhere('RECIBO.nuevo');

		$daniia->union(function(Daniia $daniia) {
			$daniia->table('ASEGURA');
			$daniia->select(
				'ASEGURA.cedrif'
				,'COMPANIA.rif'
				,'CLIENTENONATURAL.cirif AS cirif'
				,'CLIENTENONATURAL.razonsocial AS nombre'
				,'NULL AS apellido'
				,'ASEGURA.idpoliza AS idpoliza'
				,'POLIZA.discriminante AS discriminante'
				,'COMPANIA.razon AS razon'
				,'GENERA.idrecibo AS idrecibo'
				,"to_char(RECIBO.fvigini, 'DD/MM/YYYY') AS fvigini"
				,"to_char(RECIBO.fvigfin, 'DD/MM/YYYY') AS fvigfin"
				,"to_char(RECIBO.fvigcobro, 'DD/MM/YYYY') AS fvigcobro"
				,'RECIBO.prima AS prima');
			$daniia->join('CLIENTENONATURAL','ASEGURA.cirif','CLIENTENONATURAL.cirif');
			$daniia->join('GENERA','ASEGURA.idpoliza','GENERA.idpoliza');
			$daniia->join('COMPANIA','GENERA.rif','COMPANIA.rif');
			$daniia->join('POLIZA','GENERA.idpoliza','POLIZA.idpoliza');
			$daniia->join('RECIBO','GENERA.idrecibo','RECIBO.idrecibo');
			$daniia->andWhere('RECIBO.nuevo');
		});
	});

	$daniia->where('cedrif',$cedrif);
	if ($cirif) $daniia->andWhere('cirif','ILIKE','%'.$cirif.'%');
	if ($nombrecliente) $daniia->andWhere(function(Daniia $daniia)use($nombrecliente) {
		$daniia->where('nombre','ILIKE',"%{$nombrecliente}%");
		$daniia->orWhere('apellido','ILIKE',"%{$nombrecliente}%");
	});
	if ($rif) $daniia->andWhere('rif',$rif);
	if ($discriminante) $daniia->andWhere('discriminante',$discriminante);
	if ($tipovencimiento == 1 && !$fecha) $daniia->andWhere('TRUE');
	if ($tipovencimiento == 2 && !$fecha) $daniia->andWhere('fvigfin < CURRENT_DATE');
	if ($tipovencimiento == 3 && !$fecha) $daniia->andWhere('fvigcobro IS NULL');
	if ($tipovencimiento == 1 &&  $fecha) $daniia->andWhere(function(Daniia $daniia)use($fecha){
		$daniia->where('Extract(month from fvigfin)',"Extract(month from to_date(".$daniia->quote($fecha).",'YYYY-MM-DD'))",false);
		$daniia->andWhere('Extract(year from fvigfin)',"Extract(year from to_date(".$daniia->quote($fecha).",'YYYY-MM-DD'))",false);
	});
	if ($tipovencimiento == 2 &&  $fecha) $daniia->andWhere(function(Daniia $daniia)use($fecha){
		$daniia->where('fvigfin < CURRENT_DATE');
		$daniia->andWhere('Extract(month from fvigfin)',"Extract(month from to_date(".$daniia->quote($fecha).",'YYYY-MM-DD'))",false);
		$daniia->andWhere('Extract(year from fvigfin)',"Extract(year from to_date(".$daniia->quote($fecha).",'YYYY-MM-DD'))",false);
	});
	if ($tipovencimiento == 3 &&  $fecha) $daniia->andWhere('fvigcobro IS NULL');
	if ($idpoliza) $daniia->andWhere('idpoliza',$idpoliza);
	if ($mylim!==null) $daniia->limit($mylim,$myoffset);

	$data  = $daniia->get();
	echo $daniia->sql.'<br><br>';
	//$error = $daniia->error();
	//echo utf8_decode($error[2]).'<br><br>';

	return $data;
}


$r = met_registrosJoin_Asg_Cli_Gen_Pol_Com_Rec_Gen(
	'v-11223344'
	,$cirif=null
	,$fecha=null
	,$nombrecliente=null
	,$rif=null
	,$discriminante=null
	,$tipovencimiento=null
	,$idpoliza=null
	,$mylim=null
	,$myoffset=null
);

var_dump($r);