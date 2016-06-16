<?php
/**
 * Daniia - ORM PHP 5.3.0 framework
 *
 * @author      Carlos Garcia <garlos.figueroa@gmail.com>
 * @copyright   2016 Carlos Garcia
 * @link
 * @license
 * @version     1.0
 * @package     Daniia
 *
 */

namespace Daniia;

class Daniia
{
	/**
	 * Host donde está localizado la base de datos
	 * @var String
	 */
	private $driver;

	/**
	 * Usuario de la Base de Datos
	 * @var String
	 */
	private $user;

	/**
	 * Clave del Usuario de la Base de Datos
	 * @var String
	 */
	private $pass;

	/**
	 * Nombre de la Base de Datos
	 * @var String
	 */
	private $dsn;

	/**
	 * Nombre del escquema a conectarse
	 * @var String
	 */
	private $schema;


	/**
	 * Nombre de la Tabla
	 * @var String
	 */
	protected $table;

	/**
	 * Contiene en nombre de la clave primaria de la tabla
	 * @var String
	 */
	protected $primaryKey = "id";

	/**
	 * Contiene los SELECT de las sentencia SQL
	 * @var String
	 */
	private $select = "SELECT *";

	/**
	 * Contiene los FROM de las sentencia SQL
	 * @var String
	 */
	private $from = "FROM _table_";

	/**
	 * Contiene los JOIN de las sentencia SQL
	 * @var String
	 */
	private $join = "";

	/**
	 * Contiene los ON de las sentencia SQL
	 * @var String
	 */
	private $on = "";

	/**
	 * Contiene los WHERE de las sentencia SQL
	 * @var String
	 */
	private $where = "";

	/**
	 * Contiene los ORDER BY de las sentencia SQL
	 * @var String
	 */
	private $groupBy = "";

	/**
	 * Contiene los ORDER BY de las sentencia SQL
	 * @var String
	 */
	private $having = "";

	/**
	 * Contiene los ORDER BY de las sentencia SQL
	 * @var String
	 */
	private $orderBy = "";

	/**
	 * Contiene los ORDER BY de las sentencia SQL
	 * @var String
	 */
	private $limit = "";

	/**
	 * Contiene los UNION de las sentencia SQL
	 * @var String
	 */
	private $union = "";

	/**
	 * Conexión a la Base de Datos
	 * @var Connection
	 */
	private $id_conn = null;

	/**
	 * última Sentencia SQL ejecutada en la base de datos de un Resultado Paginado
	 * @var String
	 */
	public $sql;

	/**
	 * última Sentencia SQL ejecutada en la base de datos
	 * @var String
	 */
	public $last_sql;

	/**
	 * Obtiene el resultado de un query
	 * @var Boolean
	 */
	private $resultset;

	/**
	 * Obtiene los datos consultado desde la tabla
	 * @var Array
	 */
	private $rows = [];

	/**
	 * Obtiene los errores de la sentencia ejecutada
	 * @var Array
	 */
	private $error = [];

	/**
	 * Obtiene la ID de un registro para salvar los datos consultados
	 * @var Array
	 */
	private $save = [];

	private $first = [];

	/**
	 * Obtiene los nombres de los campos de una tabla dada
	 * @var Array
	 */
	private $columns = [];

	/**
	 * Identifica el tipo de fetch
	 * Tipos: row, Array, object, all, assoc
	 * @var string
	 */
	public $type_fetch = "assoc";

	/**
	 * Operadores SQL
	 * @var Array
	 */
	private $operators = ['=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like' , 'in', 'is', 'is not', 'ilike', 'not ilike', 'between', 'not between'];

	/**
	 * para saber si es una sentencia agrupada
	 * @var Boolean
	 */
	private $bool_group = false;

	/**
	 * si ha sido extendido a una sub-clase
	 * @var Boolean
	 */
	protected $extended = false;

	/**
	 * contiene los datos consultada
	 * @var Object
	 */
	public $data = [];

	/**
	 * contiene los datos del marcador de posición
	 * @var Object
	 */
	private $placeholder_data = [];



	public function __construct($bool_group = false) {
		$this->bool_group = $bool_group;
	}

	/**
	 * Establese la coneccion a la Base de Datdos
	 * @author Carlos Garcia
	 * @return Object
	 */
	private function connection() {
		if(defined('SCHEMA'))
			$this->schema = SCHEMA;

		if(defined('USER'))
			$this->user = USER;

		if(defined('PASS'))
			$this->pass = PASS;

		if(defined('DSN')) {
			$this->dsn = DSN;
			$dsn = explode(':',$this->dsn);
			$this->driver = strtolower($dsn[0]);
		}
		try {
			if(!$this->id_conn)
				$this->id_conn = new \PDO($this->dsn, $this->user, $this->pass);
		} catch (\PDOException $e) {
			die("Error: " . $e->getMessage() . "<br/>");
		}
		$this->db_instanced();
		return $this;
	}

	/**
	 * resetea todas las variables de la clase por defecto
	 * @author Carlos Garcia
	 * @return Object
	 */
	private function reset() {
		$this->select  = " SELECT * ";
		$this->from    = " FROM _table_ ";
		$this->join    = " ";
		$this->on      = " ";
		$this->where   = " ";
		$this->groupBy = " ";
		$this->having  = " ";
		$this->orderBy = " ";
		$this->limit   = " ";
		$this->union   = " ";
		$this->rows    = [] ;
		$this->placeholder_data = [] ;
		$_ENV["from"]  = null;
		$_ENV["where"] = null;
		$_ENV["having"]= null;
		$_ENV["join"]  = null;
		$_ENV["on"]    = null;
		$_ENV["union"] = null;

		$this->db_instanced();
		return $this;
	}

	private function get_placeholder($amount) {
		return implode(',', array_fill(0, count($amount), '?'));
	}

	/**
	 * Ejecuta los resultodado del query
	 * Tipos: row, Array, object, all, assoc
	 * @author Carlos Garcia
	 * @param  $getData boolean
	 * @return Object
	 */
	private function fetch($getData=true) {
		try{
			$this->connection();

			$this->resultset = $this->id_conn->prepare($this->sql);

			$this->resultset->execute($this->placeholder_data);
			$this->resultset->setFetchMode(constant('\PDO::FETCH_'.strtoupper($this->type_fetch)));

			if ($getData) {
				while ($row = $this->resultset->fetch()) {
					$this->rows[] = (object) $row;
				}
			}

			$this->db_instanced();
		} catch (\PDOException $e) {
			die("Error: " . $e->getMessage() . "<br/>");
		}

		return $this;
	}

	/**
	 * Devuelve el script sql
	 * @author Carlos Garcia
	 * @return String
	 */
	private function get_sql() {
		return "(".$this->get(false).")";
	}

	/**
	 * almacena el Objeto si es instanciado desde un closure
	 * @author Carlos Garcia
	 * @return void
	 **/
	private function db_instanced() {
		$_ENV["from"]   =
		$_ENV["join"]   =
		$_ENV["on"]     =
		$_ENV["where"]  =
		$_ENV["having"] =
		$_ENV["union"]  = $this;
	}



	/**
	 * Entrecomilla una cadena de caracteres para usarla en una consulta
	 * @author Carlos Garcia
	 * @param String $val
	 * @return String
	 */
	public function quote($val) {
		$this->connection();
		return $this->id_conn->quote($val);
	}

	/**
	 * Inicia una transacción
	 * @author Carlos Garcia
	 * @return Objecto
	 */
	public function begin() {
		$this->connection();
		$this->id_conn->beginTransaction();
		return $this;
	}

	/**
	 * Consigna una transacción
	 * @author Carlos Garcia
	 * @return Objecto
	 */
	public function commit() {
		$this->connection();
		$this->id_conn->commit();
		return $this;
	}

	/**
	 * Revierte una transacción
	 * @author Carlos Garcia
	 * @return Objecto
	 */
	public function rollback() {
		$this->connection();
		$this->id_conn->rollBack();
		return $this;
	}

	/**
	 * Obtiene información ampliada del error asociado con la última operación del gestor de sentencia
	 * @author Carlos Garcia
	 * @return Array
	 */
	public function error() {
		return $this->resultset->errorInfo();
	}

	/**
	 * Ejecuta una sentencia SQL, devolviendo un conjunto de resultados como un objeto
	 * @author Carlos Garcia
	 * @param String $sql
	 * @return Array
	 */
	public function query($sql) {
		return $this->id_conn->query($sql,constant('\PDO::FETCH_'.strtoupper($this->type_fetch)));
	}



	/**
	 * Retorna los datos consultados
	 * @author Carlos Garcia
	 * @param boolean $excute
	 * @return Array|object
	 */
	public function get($excute=true) {
		$this->connection();
		$this->from = str_replace("_table_", $this->table, $this->from);

		$this->sql = $this->select.' '.$this->from.' '.$this->join.' '.$this->where.' '.$this->union.' '.$this->groupBy.' '.$this->having.' '.$this->orderBy.' '.$this->limit;

		if (!$excute) return $this->sql;

		$this->fetch();

		$this->data = $this->rows;

		$this->reset();
		return $this->data;
	}

	/**
	 * Retorna los datos consultados en formato Array
	 * @author Carlos Garcia
	 * @return boolean $excute
	 * @return Array
	 */
	public function getArray($excute=true) {
		$this->get($excute);
		$this->data = array_map(function($v){return(array)$v;},$this->data);
		return $this->data;
	}

	/**
	 * Alia del GET
	 * @see function get
	 * @author Carlos Garcia
	 * @return Array|object
	 */
	public function all() {
		return $this->get();
	}

	/**
	 * Alia del GETARRAY
	 * @see function getArray
	 * @author Carlos Garcia
	 * @return Array
	 */
	public function allArray() {
		return $this->getArray();
	}



	/**
	 * Devuelve la primera fila de una consulta
	 * @author Carlos Garcia
	 * @return Object
	 */
	public function first() {
		if(!count($this->first))
			$this->get();
		if(is_array($this->data))
			$this->data = isset($this->data[0])?$this->data[0]:[];

		$this->first = [];
		return $this->data;
	}

	/**
	 * Devuelve la primera fila de una consulta
	 * @see function first
	 * @author Carlos Garcia
	 * @return Array
	 */
	public function firstArray() {
		return $this->data = (array)$this->first();
	}

	/**
	 * genera una lista dependiendo de los datos consultados
	 * @author Carlos Garcia
	 * @param $column String
	 * @param $index String
	 * @return mixed
	 */
	public function lists($column,$index=null) {
		$this->get();
		if (count($this->data)) {
			$temp_datas = $this->data;
			$this->data = [];
			if ($column && $index===null) {
				foreach ($temp_datas as $object) {
					$this->data[] = $object->{$column};
				}
			} elseif ($column && $index!==null) {
				foreach ($temp_datas as $key => $object) {
					$this->data[$object->{$index}] = $object->{$column};
				}
			}

			return $this->data;
		}
		return null;
	}

	/**
	 * Busca uno o varios registros en la Base de Datos
	 * @author Carlos Garcia
	 * @param Array $ids
	 * @return Array|Object
	 */
	public function find($ids) {
		if (func_num_args()>0) {
			if (func_num_args()==1) {
				if (!is_array($ids)) {
					$ids = (array) $ids ;
				}
			}else{
				$ids = func_get_args();
			}

			$this->placeholder_data = $this->save = $this->first = $ids;

			$placeholder = $this->get_placeholder($ids);
			$this->where = " WHERE {$this->primaryKey} IN({$placeholder}) ";

			$this->get();

			if (count($ids)==1)
				$this->data = @$this->data[0];

			return $this;
		}

		return null;
	}

	/**
	 * actualiza o crea un registro en la base de datos
	 * @author Carlos Garcia
	 * @return boolean
	 */
	public function save() {
		if (count($this->save)) {
			$this->update((array)$this->data);
		}elseif (!count($this->save)) {
			$this->insert((array)$this->data);
		}

		$this->save = [];
		return $this->resultset?true:false;
	}



	/**
	 * Establese la clave principal de la tabla
	 * @author Carlos Garcia
	 * @param String $primaryKey
	 * @return Object
	 */
	public function primaryKey($primaryKey) {
		$this->primaryKey = $primaryKey;
		return $this;
	}

	/**
	 * Obtiene los nombres de las columnas de la tabla seleccionada
	 * @author Carlos Garcia
	 * @param String $table
	 * @return Object
	 */
	public function columns($table=null) {
		$this->connection();
		$schema = $this->schema?"{$this->schema}.":'';
		$table = $table?$table:$this->table;
		$table = explode('.',$table);
		$table = $table[count($table)-1];



		if($this->driver=='dblib')//FreeTDS
			$sql = "";
		if($this->driver=='4d')
			$sql = "";
		if($this->driver=='pgsql') {
			$schema = $this->schema?  "AND table_schema = '{$this->schema}'":'';
			$sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '{$table}' {$schema};";
		}
		if($this->driver=='sybase')
			$sql = "SELECT sc.name AS column_name FROM syscolumns sc INNER JOIN sysobjects so ON sc.id = so.id WHERE so.name = '{$table}'";
		if($this->driver=='firebird')
			$sql = "SELECT RDB$FIELD_NAME AS column_name FROM RDB$RELATION_FIELDS WHERE RDB$RELATION_NAME='{$table}';";
		if($this->driver=='sqlite')
			$sql = "PRAGMA {$schema}table_info({$table});";
		if($this->driver=='mssql'||$this->driver=='sqlsrv')
			$sql = "SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$table}'";
		if($this->driver=='informix')
			$sql = "SELECT colname AS column_name FROM systables AS a JOIN syscolumns AS b ON a.tabid=b.tabid WHERE tabname='{$table}';";
		if($this->driver=='ibm')
			$sql = "DESCRIBE TABLE {$table}";
		if($this->driver=='mysql' || $this->driver=='oci' || $this->driver=='odbc' || $this->driver=='cubrid')
			$sql = "DESCRIBE {$schema}{$table}";

		if($this->driver=='dblib')//FreeTDS
			$field = '';
		if($this->driver=='4d')
			$field = '';
		if($this->driver=='mysql')
			$field = 'Field';
		if($this->driver=='cubrid')
			$field = 'tables_in_demodb';
		if($this->driver=='sqlite')
			$field = 'name';
		if($this->driver=='mssql'||$this->driver=='sqlsrv'||$this->driver=='informix'||$this->driver=='ibm'||$this->driver=='firebird'||$this->driver=='sybase'||$this->driver=='pgsql'||$this->driver=='oci'||$this->driver=='odbc'||$this->driver=='mssql')
			$field = 'column_name';

		try{
			$stmt = $this->id_conn->prepare($sql);
			$stmt->execute();
			$stmt->setFetchMode(constant('\PDO::FETCH_ASSOC'));

			$this->columns = [];
			while ($row = $stmt->fetch()) {
				$this->columns[] =  $row[$field];
			}
		} catch (\PDOException $e) {
			die("Error: " . $e->getMessage() . "<br/>");
		}

		return $this->columns;
	}

	/**
	 * elimina todos los datos de una tabla
	 * @author Carlos Garcia
	 * @return boolean
	 */
	public function truncate() {
		$this->connection();
		$this->sql = "TRUNCATE TABLE {$this->table};";

		if($this->driver=='firebird'||$this->driver=='sqlite')
			$this->sql = "DELETE FROM {$this->table};";

		$this->fetch(false);

		return $this->resultset?true:false;
	}

	/**
	 * Establese el nombre de la tabla
	 * @author Carlos Garcia
	 * @param $table String
	 * @return Object
	 */
	public function table($table) {
		$this->connection();
		if (func_num_args()>1)
			$table = func_get_args();
		else if(is_string($table))
			$table = [$table];

		$schema = $this->schema?"{$this->schema}.":'';

		$tmp_table = [];
		foreach($table as $val)
			$tmp_table[] = $schema.$val;

		$table = implode(",", $tmp_table);

		if ($this->extended) {
			$this->table .= ",".$table;
		}else{
			$this->table = $table;
		}

		$this->db_instanced();
		return $this;
	}

	/**
	 * Establese los nombres dee las columnas a consultar
	 * @author Carlos Garcia
	 * @param string $select
	 * @return Object
	 */
	public function select($select) {
		if (func_num_args()>1)
			$select = func_get_args();
		else if(is_string($select))
			$select = [$select];

		$select = implode(",", $select);

		$this->select = str_replace("*", $select, $this->select);

		$this->db_instanced();
		return $this;
	}

	/**
	 * Establece el nombre de la tabla a consultar
	 * @author Carlos Garcia
	 * @param $table String|Closure
	 * @param $alias String
	 * @return Object
	 */
	public function from($table="",$alias="") {
		$this->connection();

		$sql = "";
		$closure = false;

		// si es un closure lo ejecutamos
		if ($table instanceof \Closure) {
			// el resultado resuelto es almacenado en la variable $_ENV["from"]
			// para luego terminar de agruparlo
			$table(new \Daniia\Daniia());
			$sql = $_ENV["from"]->get_sql();
			$_ENV["from"] = null;
			$sql.= " AS ".($alias?$alias:" _alias_ ");
			$closure = true;
		} else {
			if (func_num_args()>1)
				$table = func_get_args();
			else if(is_string($table))
				$table = [$table];

			$table = implode(",", $table);
		}

		if ($this->extended && is_string($table) && !$closure) {
			$table = $this->table.",".$table;
		}

		if (!$this->bool_group) {
			$this->from = str_replace("_table_", ($closure?$sql:($table?$table:$this->table)), $this->from);
		}

		$this->db_instanced();
		return $this;
	}

	/**
	 * inserta datos en la base de datos
	 * @author Carlos Garcia
	 * @param $datas Array
	 * @param $getId Boolean
	 * @return Boolean
	 */
	public function insert(array $datas) {
		if (is_array($datas) && count($datas)) {
			if (!is_array(@$datas[0]))
				$datas = [$datas];

			$this->connection();
			$this->columns();

			$placeholders = [];
			$this->placeholder_data = [];
			foreach ($datas as $data) {
				$placeholder = [];
				foreach($this->columns as $column){
					if(isset($data[$column])) {
						if($data[$column]) {
							$this->placeholder_data[] = $data[$column];
							$placeholder[$column] = "?";
						}else
							$placeholder[$column] = "NULL";
					}
				}
				$placeholders[] = "(".implode(",", $placeholder).")";
			}

			$columns = "(".implode(",", array_keys($placeholder)).")";

			$placeholders = implode(",", $placeholders);

			$this->sql = "INSERT INTO {$this->table} {$columns} VALUES {$placeholders};";
			$this->fetch(false);

			$this->reset();

			return $this->resultset?true:false;
		}
		return false;
	}

	/**
	 * inserta y luego retorna la clave primaria del registro
	 * @author Carlos Garcia
	 * @param $datas Array
	 * @return integer
	 */
	public function insertGetId($datas) {

		$this->insert($datas,true);

		$lastInsertId = -1;
		if($this->driver=='mysql')
			$lastInsertId = $this->id_conn->lastInsertId();
		if($this->driver=='pgsql') {
			$this->sql = "SELECT lastval() AS {$this->primaryKey};";
			$this->fetch();
			$lastInsertId = $this->rows[0]->{$this->primaryKey};
			$this->reset();
		}

		return (int) $lastInsertId;
	}

	/**
	 * actualiza los datos en la base de datos
	 * @author Carlos Garcia
	 * @param $datas Array
	 * @return boolean
	 */
	public function update(array $datas) {
		if (is_array($datas) && count($datas)) {
			if (!is_array(@$datas[0]))
				$datas = [$datas];

			$this->connection();
			$this->columns();

			$this->placeholder_data = [];
			foreach ($datas as $data) {
				$placeholder = [];
				$isID = false;

				foreach($this->columns as $column) {
					if(isset($data[$column])) {
						if($this->primaryKey==$column) {
							$isID = $data[$column];
						} else {
							if((is_string($data[$column]) && $data[$column]) || is_numeric($data[$column])) {
								$this->placeholder_data[] = $data[$column];
								$placeholder[] = "{$column}=?";
							} else {
								if($data[$column]===true ) $placeholder[] = "{$column}=TRUE";
								elseif($data[$column]===false) $placeholder[] = "{$column}=FALSE";
								else $placeholder[] = "{$column}=NULL";
							}
						}
					}
				}

				$where = '';
				if($isID) {
					$this->placeholder_data[] = $isID;
					if (preg_match("/WHERE/", $this->where))
						$where = $this->where." AND {$this->primaryKey}=? ";
					else
						$where = " WHERE {$this->primaryKey}=? ";
				}else{
					if (preg_match("/WHERE/", $this->where))
						$where = $this->where;
				}

				$placeholders = implode(",", $placeholder);
				$this->sql = "UPDATE {$this->table} SET {$placeholders}".$where;

				$this->fetch(false);

				$this->reset();
				if(!$this->resultset) return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * elimina un registro en la base de datos
	 * @author Carlos Garcia
	 * @param $ids mixed
	 * @return boolean
	 */
	public function delete($ids=[]) {
		if (func_num_args()>0) {
			if (func_num_args()==1) {
				if (!is_array($ids)) {
					$ids = (array) $ids;
				}
			}else{
				$ids = func_get_args();
			}
		}

		$this->connection();

		if(is_object($this->data)&&$this->data)
			$ids[] = $this->data->{$this->primaryKey};
		if(is_array($this->data)&&$this->data)
			foreach($this->data as $data)
				$ids[] = $data->{$this->primaryKey};


		$placeholder = [];
		foreach($ids as $id){
			$this->placeholder_data[] = $id;
			$placeholder[] = "?";
		}
		$placeholder  = implode(",", $placeholder);

		$where = '';
		if($ids) {
			if (preg_match("/WHERE/", $this->where))
				$where = $this->where." AND {$this->primaryKey} IN({$placeholder}) ";
			else
				$where = " WHERE {$this->primaryKey} IN({$placeholder}) ";
		}else{
			if (preg_match("/WHERE/", $this->where))
				$where = $this->where;
		}

		$this->sql = "DELETE FROM {$this->table}".$where;

		$this->fetch(false);

		$this->reset();

		return $this->resultset?true:false;
	}



	/**
	 * opera sobre las clausulas indicadas
	 * @author Carlos Garcia
	 * @param  $column Array|String|Closure
	 * @param  $operator string
	 * @param  $value mixed
	 * @param  $scape_quote Bool
	 * @param  $clause string
	 * @param  $logicaOperator string
	 * @return Objeto.
	 */
	private function clause($column, $operator, $value, $scape_quote, $clause, $logicaOperator) {
		$str     = "";
		$closure = false;
		$logicaOperator = strtoupper($logicaOperator);
		$clauseUpper = strtoupper($clause);
		$clauseLower = strtolower($clause);
		$this->connection();

		// si es un closure lo ejecutamos
		if ($column instanceof \Closure) {
			// el resultado resuelto es almacenado en la variable $_ENV para luego terminar de agruparlo
			$column(new \Daniia\Daniia(true));
			$str = $_ENV[$clauseLower]->{$clauseLower}.")";
			$_ENV[$clauseLower] = null;
			$closure = true;
		}

		// si no es un operador valido
		if ($operator instanceof \Closure) {
			list($operator, $value, $scape_quote) = ['=', $operator, $value];
		}elseif (is_array($operator)) {
			list($operator, $value, $scape_quote) = ['in', $operator, $value];
		}elseif ($operator!==null&&!in_array(strtolower(trim($operator)), $this->operators, true)) {
			list($operator, $value, $scape_quote) = ['=', $operator, $value];
		}

		if ($value instanceof \Closure) {
			// el resultado resuelto es almacenado en la variable $_ENV para luego terminar de agruparlo
			$value(new \Daniia\Daniia());
			$get_sql = $_ENV[$clauseLower]->get_sql();
			$_ENV[$clauseLower] = null;
			$scape_quote = false;
			$value = $get_sql;
		}

		if(is_array($value)&&(strtolower($operator)=='between'||strtolower($operator)=='not between')){
			$scape_quote = false;
			$value = ' '.$this->id_conn->quote($value[0]).' AND '.$this->id_conn->quote($value[1]).' ';
		}

		if(is_array($value)) {
			$in = [];
			foreach($value as $val){
				$in[] = ($scape_quote===true?$this->id_conn->quote($val):$val);
			}
			$scape_quote = false;
			$value = ' ('.implode(',',$in).') ';
		}

		// if (is_null($value)) {
		// 	return $this->whereNull($column, $boolean, $operator != '=');
		// }

		if (!$closure && preg_match("/,/", $this->table)) {
			$str   = $column.' '.strtoupper($operator).' '.($scape_quote===true?$this->id_conn->quote($value):$value)." ";
		}elseif(!$closure){
			if($operator===null&&$value===true){
				$str = $column;
			}else{
				$str = $column.' '.strtoupper($operator).' '.($scape_quote===true?$this->id_conn->quote($value):$value)." ";
			}
		}

		if (!$this->bool_group) {
			if (preg_match("/".$clauseUpper."/", $this->{$clauseLower})) {
				$this->{$clauseLower} .= " {$logicaOperator} {$str} ";
			}else {
				$this->{$clauseLower} = " {$clauseUpper} {$str} ";
			}
		}else{
			if (preg_match('/\(/', $this->{$clauseLower})) {
				$this->{$clauseLower} .= " {$logicaOperator} {$str} ";
			}else {
				$this->{$clauseLower} .= " ({$str} ";
			}
		}

		$this->db_instanced();
		return $this;
	}

	/**
	 * establece las tebles que van hacer unidas
	 * @author Carlos Garcia
	 * @param $table String
	 * @param $column String|Closure
	 * @param $operator String
	 * @param $value String
	 * @param $scape_quote Bool
	 * @param $type String
	 * @return Object
	 */
	private function clauseJoin($table,$column,$operator,$value, $scape_quote,$type) {
		$closure = false;
		$type = strtoupper($type);
		$this->connection();
		$schema = $this->schema?"{$this->schema}.":'';

		// si es un closure lo ejecutamos
		if ($column instanceof \Closure) {
			// el resultado resuelto es almacenado en la variable $_ENV para luego terminar de agruparlo
			$column(new \Daniia\Daniia());
			$str = $_ENV["on"]->on;
			$_ENV["on"] = null;
			$closure = true;
		}

		// si no es un operador valido
		if ($operator instanceof \Closure) {
			list($operator, $value, $scape_quote) = ['=', $operator, $value];
		}elseif (is_array($operator)) {
			list($operator, $value, $scape_quote) = ['in', $operator, $value];
		}elseif ($operator!==null&&!in_array(strtolower(trim($operator)), $this->operators, true)) {
			list($operator, $value, $scape_quote) = ['=', $operator, $value];
		}

		if ($value instanceof \Closure) {
			// el resultado resuelto es almacenado en la variable $_ENV para luego terminar de agruparlo
			$value(new \Daniia\Daniia());
			$get_sql = $_ENV["on"]->get_sql();
			$_ENV["on"] = null;
			$value = $get_sql;
		}

		if(is_array($value)) {
			$in = [];
			foreach($value as $val){
				$in[] = ($scape_quote===true?$this->id_conn->quote($val):$val);
			}
			$scape_quote = false;
			$value = ' ('.implode(',',$in).') ';
		}

		if (!$closure) {
			$str = $column.' '.strtoupper($operator).' '.($scape_quote?$this->id_conn->quote($value):$value);
			$this->join .= " {$type} JOIN {$schema}{$table} ON {$str} ";
		} else
			$this->join .= " {$type} JOIN {$schema}{$table} {$str} ";

		$this->db_instanced();
		return $this;
	}



	/**
	 * establece las tebles que van hacer unidas
	 * @author Carlos Garcia
	 * @see function join
	 * @param $table String
	 * @param $column String|Closure
	 * @param $operator String
	 * @param $value String
	 * @param $scape_quote Bool
	 * @return Object
	 */
	public function join($table,$column,$operator=null,$value=null, $scape_quote=false) {
		$this->clauseJoin($table,$column,$operator,$value,$scape_quote,"");
		return $this;
	}

	/**
	 * establece las tebles que van hacer unidas
	 * @author Carlos Garcia
	 * @see function join
	 * @param $table String
	 * @param $column String|Closure
	 * @param $operator String
	 * @param $value String
	 * @param $scape_quote Bool
	 * @return Object
	 */
	public function innerJoin($table,$column,$operator=null,$value=null, $scape_quote=false) {
		$this->clauseJoin($table,$column,$operator,$value,$scape_quote,"INNER");
		return $this;
	}

	/**
	 * establece las tebles que van hacer unidas por la izquierda
	 * @author Carlos Garcia
	 * @see function join
	 * @param $table String
	 * @param $column String|Closure
	 * @param $operator String
	 * @param $value String
	 * @param $scape_quote Bool
	 * @return Object
	 */
	public function leftJoin($table,$column,$operator=null,$value=null, $scape_quote=false) {
		$this->clauseJoin($table,$column,$operator,$value,$scape_quote,"LEFT");
		return $this;
	}

	/**
	 * establece las tebles que van hacer unidas por la derecha
	 * @author Carlos Garcia
	 * @see function join
	 * @param $table String
	 * @param $column String|Closure
	 * @param $operator String
	 * @param $value String
	 * @param $scape_quote Bool
	 * @return Object
	 */
	public function rightJoin($table,$column,$operator=null,$value=null, $scape_quote=false) {
		$this->clauseJoin($table,$column,$operator,$value,$scape_quote,"RIGHT");
		return $this;
	}

	/**
	 * @author Carlos Garcia
	 * @see function clause
	 * @param  $column Array|String|Closure
	 * @param  $operator string
	 * @param  $value mixed
	 * @param  $scape_quote Bool
	 * @return Objeto.
	 */
	public function on($column, $operator = null, $value = false, $scape_quote=false){
		$this->clause($column, $operator, $value, $scape_quote,'on', 'AND');
		return $this;
	}

	/**
	 * @author Carlos Garcia
	 * @see function clause
	 * @param  $column Array|String|Closure
	 * @param  $operator string
	 * @param  $value mixed
	 * @param  $scape_quote Bool
	 * @return Objeto.
	 */
	public function orOn($column, $operator = null, $value = false, $scape_quote=false){
		$this->clause($column, $operator, $value, $scape_quote,'on', 'OR');
		return $this;
	}

	/**
	 * @author Carlos Garcia
	 * @see function clause
	 * @param  $column Array|String|Closure
	 * @param  $operator string
	 * @param  $value mixed
	 * @param  $scape_quote Bool
	 * @return Objeto.
	 */
	public function andOn($column, $operator = null, $value = false, $scape_quote=false){
		$this->clause($column, $operator, $value, $scape_quote,'on', 'AND');
		return $this;
	}



	/**
	 * @author Carlos Garcia
	 * @see function clause
	 * @param  $column Array|String|Closure
	 * @param  $operator string
	 * @param  $value mixed
	 * @param  $scape_quote Bool
	 * @return Objeto.
	 */
	public function where($column, $operator = null, $value = true, $scape_quote=true) {
		$this->clause($column, $operator, $value, $scape_quote,'where', 'AND');
		return $this;
	}

	/**
	 * @author Carlos Garcia
	 * @see function clause
	 * @param  $column Array|String|Closure
	 * @param  $operator string
	 * @param  $value mixed
	 * @param  $scape_quote Bool
	 * @return Objeto.
	 */
	public function orWhere($column, $operator = null, $value = true, $scape_quote=true) {
		$this->clause($column, $operator, $value, $scape_quote,'where', 'OR');
		return $this;
	}

	/**
	 * @author Carlos Garcia
	 * @see function clause
	 * @param  $column Array|String|Closure
	 * @param  $operator string
	 * @param  $value mixed
	 * @param  $scape_quote Bool
	 * @return Objeto.
	 */
	public function andWhere($column, $operator = null, $value = true, $scape_quote=true) {
		$this->clause($column, $operator, $value, $scape_quote,'where', 'AND');
		return $this;
	}



	/**
	 * @author Carlos Garcia
	 * @see function clause
	 * @param  $column Array|String|Closure
	 * @param  $operator string
	 * @param  $value mixed
	 * @param  $scape_quote Bool
	 * @return Objeto.
	 */
	public function having($column, $operator = null, $value = true, $scape_quote=true) {
		$this->clause($column, $operator, $value, $scape_quote,'having', 'AND');
		return $this;
	}

	/**
	 * @author Carlos Garcia
	 * @see function having
	 * @param  $column mixed
	 * @param  $operator string
	 * @param  $value mixed
	 * @param  $scape_quote Bool
	 * @return Object
	 */
	public function orHaving($column, $operator = null, $value = true, $scape_quote=true) {
		$this->clause($column, $operator, $value, $scape_quote,'having', 'OR');
		return $this;
	}

	/**
	 * @author Carlos Garcia
	 * @see function having
	 * @param  $column mixed
	 * @param  $operator string
	 * @param  $value mixed
	 * @param  $scape_quote Bool
	 * @return Object
	 */
	public function andHaving($column, $operator = null, $value = true, $scape_quote=true) {
		$this->clause($column, $operator, $value, $scape_quote,'having', 'AND');
		return $this;
	}



	/**
	 * agrega el query ORDER BY basico
	 * @author Carlos Garcia
	 * @param String|Array $fields
	 * @return Object
	 */
	public function orderBy($fields) {
		if (func_num_args()>0) {
			if (func_num_args()==1) {
				if (!is_array($fields)) {
					$fields = (array) $fields;
				}
			}else{
				$fields = func_get_args();
			}

			$order = '';
			if(strtoupper($fields[count($fields)-1])=='ASC'||strtoupper($fields[count($fields)-1])=='DESC'){
				$order = $fields[count($fields)-1];
				unset($fields[count($fields)-1]);
			}
			$this->orderBy = " ORDER BY ".implode(',',$fields).' '.strtoupper($order);

			$this->db_instanced();
			return $this;
		}
		return null;
	}

	/**
	 * agrega el query GROUP BY basico
	 * @author Carlos Garcia
	 * @param String|Array $fields
	 * @return mixed.
	 */
	public function groupBy($fields) {
		if (func_num_args()>0) {
			if (func_num_args()==1) {
				if (!is_array($fields)) {
					$fields = (array) $fields;
				}
			}else{
				$fields = func_get_args();
			}

			$this->groupBy = " GROUP BY ".implode(',',$fields);

			$this->db_instanced();
			return $this;
		}
		return null;
	}

	/**
	 * agrega el quiery basico LIMIT
	 * @author Carlos Garcia
	 * @param Integer|Array $limit
	 * @param Integer $offset
	 * @return Object
	 */
	public function limit($limit,$offset=null) {
		if(is_numeric($limit)) {
			$this->limit .= " LIMIT {$limit} ";
			if(is_numeric($offset))
				$this->limit .= " OFFSET {$offset} ";

			$this->db_instanced();
			return $this;
		}
		if(is_array($limit)) {
			$limit  = $limit[0];
			$offset = @$limit[1];
			$this->limit .= " LIMIT {$limit} ";
			if(is_numeric($offset))
				$this->limit .= " OFFSET {$offset} ";

			$this->db_instanced();
			return $this;
		}

		return null;
	}

	/**
	 * genera una lista dependiendo de los datos consultados
	 * @author Carlos Garcia
	 * @param Closure $closure
	 * @return mixed
	 */
	public function union($closure) {
		// si es un closure lo ejecutamos
		if ($closure instanceof \Closure) {
			// el resultado resuelto es almacenado en la variable $_ENV["union"]
			// para luego terminar de agruparlo
			$closure(new \Daniia\Daniia());
			$str = $_ENV["union"]->get_sql();
			$_ENV["union"] = null;
			$this->union .= ' UNION ' . $str;
		}

		$this->db_instanced();
		return $this;
	}



	public function __set($name, $value) {
		if(!is_object($this->data))
			$this->data = new \stdClass();
		$this->data->{$name} = $value;
	}

	public function __get($name) {
		return @$this->data->{$name};
	}
}
