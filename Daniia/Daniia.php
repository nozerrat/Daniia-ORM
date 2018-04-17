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
   private $schema = '';


   /**
    * Nombre de la Tabla
    * @var String
    */
   private $tableOrig = NULL;
   protected $table = NULL;

   /**
    * Contiene en nombre de la clave primaria de la tabla
    * @var String
    */
   protected $primaryKey = "_id";

   /**
    * Contiene los SELECT de las sentencia SQL
    * @var String
    */
   private $select = "SELECT * ";

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
    * Contiene el LIMIT de la consulta
    * @var Integer
    */
   private $limit = "";

   /**
    * Contiene el OFFSET de la consulta
    * @var Integer
    */
   private $offset = "";

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
   private $sql;

   public $last_sql;

   /**
    * última ID insertada en la base de datos
    * @var String
    */
   public $last_id;

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
   private $saveData = FALSE;

   private $firstData = FALSE;

   private $deleteData = FALSE;

   /**
    * Obtiene los nombres de los campos de una tabla dada
    * @var Array
    */
   private $columnsData = [];

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
   private $data = [];

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
      if ( function_exists('get_instance') && !$this->id_conn ) {
         /**
          * Si el framework Daniia es instalado en el framework CodeIgniter 3
          * entonces tomamos la configuración de conexión a la base de datos
          */
         $CI =& get_instance();

         if ( @$CI ) {
            @$CI->db ?: $CI->load->database();
            if(!defined('USER'))   define("USER",   $CI->db->username);
            if(!defined('PASS'))   define("PASS",   $CI->db->password);
            if(!defined('SCHEMA')) define("SCHEMA",@$CI->db->schema ?: '');
            if(!defined('DSN'))    define("DSN",    $CI->db->dsn);
         }
      }

      if (static::class!=='Daniia\Daniia' && $this->table===NULL) {
         $this->table = strtolower( static::class );
      }

      if ( $this->tableOrig===NULL ) {
         $this->tableOrig = $this->table;
      }

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
         if ( !$this->id_conn )
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
      $this->offset  = " ";
      $this->union   = " ";
      $this->rows    = [] ;
      $this->placeholder_data = [] ;
      $_ENV["daniia_from"]  = null;
      $_ENV["daniia_where"] = null;
      $_ENV["daniia_having"]= null;
      $_ENV["daniia_join"]  = null;
      $_ENV["daniia_on"]    = null;
      $_ENV["daniia_union"] = null;

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
    * Comprueba si un array es asociativo
    * @author Carlos Garcia
    * @param String $array
    * @return String
    */
   private function is_assoc( array $array ) {

        foreach(array_keys($array) as $key) {
            if (!is_int($key)) return true;
        }

        return false;

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
      $_ENV["daniia_from"]   =
      $_ENV["daniia_join"]   =
      $_ENV["daniia_on"]     =
      $_ENV["daniia_where"]  =
      $_ENV["daniia_having"] =
      $_ENV["daniia_union"]  = $this;
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
   public function error()	{
      if ($this->resultset) {
         $pdo_error        = $this->resultset->errorInfo();
         $error['code']    = @$pdo_error[1] ? $pdo_error[0].'/'.$pdo_error[1] : $pdo_error[0];
         $error['message'] =  $pdo_error[2];
      } 
      else {
         $error['code']    = '00000';
         $error['message'] = 'ERROR: No hay conexión a la base de datos';
      }

      return $error;
   }

   /**
    * Ejecuta una sentencia SQL, devolviendo un conjunto de resultados como un objeto
    * @author Carlos Garcia
    * @param String $sql
    * @return Array
    */
   public function query( string $sql, $closure=NULL ) {
      $this->connection();
      $this->last_sql = $this->sql = $sql;
      $this->fetch();
      $this->data = $this->rows;
      $this->reset();
      if ($closure instanceof \Closure) {
         $this->data = $closure( $this->data, $this );
      }
      return $this->data;
   }

   /**
    * Ejecuta una sentencia SQL, devolviendo un conjunto de resultados como un Array
    * @author Carlos Garcia
    * @param Closure $closure
    * @return Array
    */
    public function queryArray( string $sql, $closure=NULL ) {
      $this->query( $sql );
      $this->data = array_map(function($v){return(array)$v;},$this->data);
      if ($closure instanceof \Closure) {
         $this->data = $closure( $this->data, $this );
      }
      return $this->data ?: [];
   }



   /**
    * Retorna los datos consultados
    * @author Carlos Garcia
    * @param Closure $closure
    * @return Array|object
    */
   public function get($closure=NULL) {
      $this->connection();
      if (preg_match('/\./', $this->table) || !$this->schema ) {
         $this->from = str_replace("_table_", $this->table, $this->from);
      }
      else {
         $this->from = str_replace("_table_", $this->schema.'.'.$this->table, $this->from);
      }

      $this->last_sql = $this->sql = $this->select.' '.$this->from.' '.$this->join.' '.$this->where.' '.$this->union.' '.$this->groupBy.' '.$this->having.' '.$this->orderBy.' '.$this->limit.' '.$this->offset;

      if ($closure===FALSE) return $this->sql;

      $this->fetch();

      $this->data = $this->rows;

      $this->reset();

      if ( $this->tableOrig!==NULL ) {
         $this->table = $this->tableOrig;
      }

      if ($closure instanceof \Closure) {
         $this->data = $closure( $this->data, $this );
      }

      return $this->data;
   }

   /**
    * Retorna los datos consultados en formato Array
    * @author Carlos Garcia
    * @param Closure $closure
    * @return Array
    */
   public function getArray($closure=NULL) {
      $this->get();
      $this->data = array_map(function($v){return(array)$v;},$this->data);
      if ($closure instanceof \Closure) {
         $this->data = $closure( $this->data, $this );
      }
      return $this->data ?: [];
   }

   /**
    * Alia del GET
    * @see function get
    * @author Carlos Garcia
    * @param Closure $closure
    * @return Array|object
    */
   public function all($closure=NULL) {
      return $this->get($closure);
   }

   /**
    * Alia del GETARRAY
    * @see function getArray
    * @author Carlos Garcia
    * @param Closure $closure
    * @return Array
    */
   public function allArray($closure=NULL) {
      return $this->getArray($closure);
   }



   /**
    * Devuelve la primera fila de una consulta
    * @author Carlos Garcia
    * @param Closure $closure
    * @return Object
    */
   public function first($closure=NULL) {
      if(!$this->firstData && !count($this->data)) {
         $this->get();
      }

      if(is_array($this->data) && count($this->data) >= 1) {
         $this->saveData = TRUE;
         $this->data = $this->data[0];
      }
      
      if ($this->firstData===TRUE && $closure instanceof \Closure) {
         $this->data = $closure( $this->data, $this );
      }

      $this->firstData = FALSE;
      
      $data = $this->data;
      $this->data = [];
      
      return $data;
   }

   /**
    * Devuelve la primera fila de una consulta
    * @see function first
    * @author Carlos Garcia
    * @param Closure $closure
    * @return Array
    */
   public function firstArray( $closure=NULL ) {
      return $this->data = (array) $this->first( $closure );
   }

   /**
    * genera una lista dependiendo de los datos consultados
    * @author Carlos Garcia
    * @param $column String
    * @param $index String|Closure
    * @param $closure Closure
    * 
    * @return mixed
    */
   public function lists($column,$index=null,$closure=NULL) {
      if ($index instanceof \Closure) {
         $closure = $index;
         $index = null;
      }

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

         if ($closure instanceof \Closure) {
            $this->data = $closure( $this->data, $this );
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
    public function find($ids=[]) {
      if (func_num_args()>0) {
         if (func_num_args()===1) {
            if (!is_array($ids)) {
               $ids = (array) $ids ;
            }
         }
         else {
            $ids = func_get_args();
         }
      }

      $closure = NULL;
      if ( count(func_get_args())>1 ) {
         if ( $ids[count($ids)-1] instanceof \Closure ) {
            $closure = array_pop($ids);
         }
         if (is_array($ids[0])) {
            $ids = $ids[0];
         }
      }

      $this->firstData = $this->saveData = $this->deleteData = TRUE;

      if ( count($ids) && !($ids[0] instanceof \Closure) ) {
         if ( $this->is_assoc( $ids ) ) {
            $this->where( $ids );
         }
         else {
            $this->where( $this->primaryKey, 'in', $ids );
         }
      }

      $this->get();

      if (count($ids)===1 && count($this->data)===1) {
         $this->data = $this->data[0];
      }

      if ($closure instanceof \Closure) {
         $this->data = $closure( $this->data, $this );
      }

      return $this;
   }

   /**
    * actualiza o crea un registro en la base de datos
    * @author Carlos Garcia
    * @return boolean
    */
    public function save($closure=NULL) {
      $updated = FALSE;
      if ($this->saveData && @$this->data->{$this->primaryKey}) {
         $updated = $this->update((array)$this->data);
      }

      $last_id = NULL;
      if (($this->saveData && !@$this->data->{$this->primaryKey}) || !$updated) {
         $last_id = $this->insertGetId((array)$this->data);
      }

      $this->saveData = FALSE;
      $lastQuery = $this->lastQuery();

      $error = $this->error();
      if ( $error['message'] ) {
         return FALSE;
      }

      if ( $last_id ) {
         $this->where( $this->primaryKey, $last_id );
         $this->get();
         $this->last_sql = $lastQuery ."\n". $this->lastQuery();
      }

      if ($closure instanceof \Closure) {
         $this->data = $closure( $this->data, $this );
      }

      $error = $this->error();
      return $error['message'] ? FALSE : TRUE;
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
      $schema = $this->schema ? "{$this->schema}." : '';
      $table  = $table ?: $this->table;
      $table  = explode('.',$table);
      $table  = $table[count($table)-1];



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

         $this->columnsData = [];
         while ($row = $stmt->fetch()) {
            $this->columnsData[] = $row[$field];
         }
      } catch (\PDOException $e) {
         die("Error: " . $e->getMessage() . "<br/>");
      }

      return $this->columnsData;
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
    * @param $tables String
    * @return Object
    */
   public function table($table) {
      $this->connection();
      if (func_num_args()>1)
         $table = func_get_args();
      else if(is_string($table))
         $table = [$table];
      
      $schema = '';
      if ($this->driver=='pgsql' && $this->schema)
         $schema = $this->schema ? $this->schema.'.' : '';

      $tmp_table = [];
      foreach($table as $table) {
         if (preg_match('/\./', $table)) {
            $tmp_table[] = $table;
         }
         else {
            $tmp_table[] = $schema.$table;
         }
      }

      $table = implode(",", $tmp_table);

      if ($this->extended) {
         if ( $this->table ) {
            $this->table = $schema.$this->table.",".$table;
         } else {
            $this->table = $table;
         }
      }
      else {
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
   public function select($select = "*") {
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
   public function from($table="",$aliasFrom="") {
      $this->connection();

      $sql = "";
      $closure = false;

      // si es un closure lo ejecutamos
      if ($table instanceof \Closure) {
         // el resultado resuelto es almacenado en la variable $_ENV["daniia_from"]
         // para luego terminar de agruparlo
         $table(new \Daniia\Daniia());
         $sql = $_ENV["daniia_from"]->get_sql();
         $_ENV["daniia_from"] = null;
         $sql.= " AS ".($aliasFrom ? $aliasFrom : " _alias_ ");
         $closure = true;
      } else {
         if (func_num_args()>1)
            $table = func_get_args();
         else if(is_string($table))
            $table = [$table];

         $schema = '';
         if ($this->driver=='pgsql' && $this->schema)
            $schema = $this->schema ? $this->schema.'.' : '';

         $tmp_table = [];
         foreach($table as $table) {
            if (preg_match('/\./', $table)) {
               $tmp_table[] = $table;
            }
            else {
               $tmp_table[] = $schema.$table;
            }
         }

         $table = implode(",", $tmp_table);
      }

      if ($this->extended && is_string($table) && !$closure) {
         $table = $schema.$this->table.",".$table;
      }


      if (!$this->bool_group) {
         $this->from = str_replace("_table_", ($closure ? $sql : ($table ?: $this->table)), $this->from);
      }

      $this->db_instanced();
      return $this;
   }

   /**
    * inserta datos en la base de datos
    * @author Carlos Garcia
    * @param $datas Array
    * @param $returning_id Boolean
    * @return Boolean
    */
   public function insert(array $datas, bool $returning_id = false) {
      if (is_array($datas) && count($datas)) {
         if (!is_array(@$datas[0]))
            $datas = [$datas];

         $this->connection();
         $this->columns();

         $placeholders = [];
         $this->placeholder_data = [];

         foreach ($datas as $data) {
            $placeholder = [];
            foreach($this->columnsData as $column) {
               if(isset($data[$column])) {
                  $placeholder[$column] = "NULL";
                  if ( gettype($data[$column])==='boolean' )
                     $placeholder[$column] = $data[$column] ? 'TRUE' : 'FALSE';
                  elseif ( $data[$column]!=='' && $data[$column]!==NULL ) {
                     $placeholder[$column] = "?";
                     $this->placeholder_data[] = $data[$column];
                  }
               }
            }
            $placeholders[] = "(".implode(",", $placeholder).")";
         }

         $columns = "(".implode(",", array_keys($placeholder)).")";

         $placeholders = implode(",", $placeholders);


         $schema = '';
         $returning = '';
         if ($this->driver=='pgsql' && $this->schema) {
            if (!preg_match('/\./', $this->table)) {
               $schema = $this->schema ? $this->schema.'.' : '';	
            }
            $returning = $returning_id ? 'RETURNING '.$this->primaryKey : '';
         }

         $this->last_sql = $this->sql = "INSERT INTO {$schema}{$this->table} {$columns} VALUES {$placeholders} {$returning};";
         
         $this->fetch();

         $this->data = $this->rows;

         $this->reset();

         if ( $this->tableOrig!==NULL ) {
            $this->table = $this->tableOrig;
         }

         $error = $this->error();
         return $error['message'] ? FALSE : TRUE;
      }
      return FALSE;
   }

   /**
    * inserta y luego retorna la clave primaria del registro
    * @author Carlos Garcia
    * @param $datas Array
    * @return integer
    */
   public function insertGetId(array $datas) {
      $this->connection();
      $this->last_id = NULL;
      if ( $this->driver=='pgsql' ) {
         $this->insert( $datas, TRUE );
         if ( @$this->data[0] ) {
            $this->last_id = @$this->data[0]->{$this->primaryKey};
         }
      }
      else {
         $this->insert( $datas );
         $this->last_id = $this->id_conn->lastInsertId();
      }
      return (int) $this->last_id;
   }

   /**
    * actualiza los datos en la base de datos
    * @author Carlos Garcia
    * @param Array $datas
    * @param Boolean $noApplyPrimaryKey
    * @return Boolean
    */
   public function update(array $datas, $noApplyPrimaryKey = false) {
      if (is_array($datas) && count($datas)) {
         if (!is_array(@$datas[0]))
            $datas = [$datas];

         $this->connection();
         $this->columns();
         
         $this->last_sql = [];
         $this->placeholder_data = [];

         $where = $this->where;
         $whereTemp = str_replace('WHERE', '', $this->where);

         foreach ($datas as $data) {
            $placeholder = [];
            $isID = NULL;
            foreach($this->columnsData as $column) {
               if(isset($data[$column])) {
                  if($this->primaryKey==$column && $noApplyPrimaryKey===false) {
                     $isID = $data[$column];
                  } 
                  else {
                     $placeholder[$column] = "{$column}=NULL";
                     if ( gettype($data[$column])==='boolean' )
                        $placeholder[$column] = $data[$column] ? "{$column}=TRUE" : "{$column}=FALSE";
                     elseif ( $data[$column]!=='' && $data[$column]!==NULL ) {
                        $placeholder[$column] = "{$column}=?";
                        $this->placeholder_data[] = $data[$column];
                     }
                  }
               }
            }


            if($isID!==NULL) {
               $this->where = '';
               $this->where( $this->primaryKey, '=', $isID );
               if (trim($whereTemp)) {
                  $where = $this->where .' AND '. $whereTemp;
               }
               else {
                  $where = $this->where;
               }
            }

            $schema = '';
            if($this->driver=='pgsql' && $this->schema) {
               if (!preg_match('/\./', $this->table)) {
                  $schema = $this->schema ? $this->schema.'.' : '';	
               }
            }

            $placeholders = implode(",", $placeholder);
            $this->last_sql[] = $this->sql = "UPDATE {$schema}{$this->table} SET {$placeholders} {$where}";

            $this->fetch(false);

            $this->reset();

            if ( $this->tableOrig!==null ) {
               $this->table = $this->tableOrig;
            }

            $error = $this->error();
            if($error['message']) return false;
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
   public function delete( $ids = [] ) {
      if (func_num_args()>0) {
         if (func_num_args()==1) {
            if (!is_array($ids)) {
               $ids = (array) $ids;
            }
         }
         else {
            $ids = func_get_args();
         }
      }

      $this->connection();
      if ( $this->deleteData ) {
         if(is_object($this->data) && $this->data) {
            $ids[] = $this->data->{$this->primaryKey};
         }

         if(is_array($this->data) && $this->data) {
            foreach($this->data as $data)
               $ids[] = $data->{$this->primaryKey};
         }

         $this->deleteData = FALSE;

         if (!count( $ids )) {
            return FALSE;
         }
         
      }

      if ( $this->is_assoc( $ids ) ) {
         $this->where( $ids );
         $ids = NULL;
      }
      else {
         $this->placeholder_data = $ids;
         $placeholder = $this->get_placeholder($ids);
      }
      

      $where = '';
      if ( $ids ) {
         if (preg_match("/WHERE/", $this->where))
            $where = " {$this->where} AND {$this->primaryKey} IN({$placeholder}) ";
         else
            $where = " WHERE {$this->primaryKey} IN({$placeholder}) ";
      }
      else {
         if (preg_match("/WHERE/", $this->where))
            $where = $this->where;
      }

      $schema = '';
      if($this->driver=='pgsql' && $this->schema) {
         if (!preg_match('/\./', $this->table)) {
            $schema = $this->schema ? $this->schema.'.' : '';	
         }
      }

      $this->last_sql = $this->sql = "DELETE FROM {$schema}{$this->table} {$where}";

      $this->fetch(false);

      $this->reset();

      if ( $this->tableOrig!==null ) {
         $this->table = $this->tableOrig;
      }

      $error = $this->error();
      return $error['message'] ? false : true;
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


      if ( is_array($column) ) {
         foreach ($column as $key => $val) {
            // $this->operators
            if (preg_match('/([\.a-zA-Z0-9_-]+) *(.*)/i', $key,$match)) {
               if (trim($match[2])) {
                  $this->clause(trim($match[1]), trim($match[2]), $val, $scape_quote, $clause, $logicaOperator);
               }
               else {
                  $this->clause(trim($match[1]), $val, $value, $scape_quote, $clause, $logicaOperator);
               }
            }
         }
         return;
      }

      // si es un closure lo ejecutamos
      if ($column instanceof \Closure) {
         // el resultado resuelto es almacenado en la variable $_ENV para luego terminar de agruparlo
         $column(new \Daniia\Daniia(true));
         $str = $_ENV['daniia_'.$clauseLower]->{$clauseLower}.")";
         $_ENV['daniia_'.$clauseLower] = null;
         $closure = true;
      }

      // si no es un operador valido
      if ( $operator instanceof \Closure ) {
         list($operator, $value, $scape_quote) = ['=', $operator, $value];
      }
      elseif ( is_array($operator) ) {
         list($operator, $value, $scape_quote) = ['in', $operator, $value];
      }
      elseif ( !in_array(strtolower(trim($operator)), $this->operators, true) ) {
         list($operator, $value, $scape_quote) = ['=', $operator, $value];
      }

      if ($value instanceof \Closure) {
         // el resultado resuelto es almacenado en la variable $_ENV para luego terminar de agruparlo
         $value(new \Daniia\Daniia());
         $get_sql = $_ENV['daniia_'.$clauseLower]->get_sql();
         $_ENV['daniia_'.$clauseLower] = null;
         $scape_quote = false;
         $value = $get_sql;
      }

      if(is_array($value)&&(strtolower($operator)=='between'||strtolower($operator)=='not between')){
         $scape_quote = false;
         $value = ' '.$this->id_conn->quote($value[0]).' AND '.$this->id_conn->quote($value[1]).' ';
      }

      if( is_array($value) ) {
         $in = [];
         foreach($value as $val){
            if ( $val===NULL ) {
               $in[] = ' NULL ' ;
            }
            elseif ( gettype($val)==='boolean' ) {
               $in[] = $val ? ' TRUE ' : ' FALSE ' ;
            }
            else {
               $in[] = $this->id_conn->quote($val);
            }
         }
         $scape_quote = FALSE;
         $value = ' ('.implode(',',$in).') ';
      }
      elseif ( $value===NULL ) {
         $scape_quote = FALSE;
         $value = ' NULL ' ;
      }
      elseif ( gettype($value)==='boolean' ) {
         $scape_quote = FALSE;
         $value = $value ? ' TRUE ' : ' FALSE ' ;
      }

      // if (is_null($value)) {
      // 	return $this->whereNull($column, $boolean, $operator != '=');
      // }

      if (!$closure && preg_match("/,/", $this->table)) {
         $str = $column.' '.strtoupper($operator).' '.($scape_quote===true?$this->id_conn->quote($value):$value)." ";
      }else if(!$closure) {
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
      }
      else {
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

      if (is_array($column)) {
         foreach ($column as $key => $val) {
            // $this->operators
            if (preg_match('/([\.a-zA-Z0-9_-]+) *(.*)/i', $key,$match)) {
               if (trim($match[2])) {
                  $this-> clauseJoin($table,trim($match[1]), trim($match[2]), $val, $scape_quote, $type);
               }
               else {
                  $this-> clauseJoin($table,trim($match[1]), $val, $value, $scape_quote, $type);
               }
            }
         }
         return;
      }

      // si es un closure lo ejecutamos
      if ($column instanceof \Closure) {
         // el resultado resuelto es almacenado en la variable $_ENV para luego terminar de agruparlo
         $column(new \Daniia\Daniia());
         $str = $_ENV["daniia_on"]->on;
         $_ENV["daniia_on"] = null;
         $closure = true;
      }

      // si no es un operador valido
      if ( $operator instanceof \Closure ) {
         list($operator, $value, $scape_quote) = ['=', $operator, $value];
      }elseif ( is_array($operator) ) {
         list($operator, $value, $scape_quote) = ['in', $operator, $value];
      }elseif ( !in_array(strtolower(trim($operator)), $this->operators, true) ) {
         list($operator, $value, $scape_quote) = ['=', $operator, $value];
      }

      if ($value instanceof \Closure) {
         // el resultado resuelto es almacenado en la variable $_ENV para luego terminar de agruparlo
         $value(new \Daniia\Daniia());
         $get_sql = $_ENV["daniia_on"]->get_sql();
         $_ENV["daniia_on"] = null;
         $value = $get_sql;
      }

      if( is_array($value) ) {
         $in = [];
         foreach($value as $val){
            if ( $val===NULL ) {
               $in[] = ' NULL ' ;
            }
            elseif ( gettype($val)==='boolean' ) {
               $in[] = $val ? ' TRUE ' : ' FALSE ' ;
            }
            else {
               $in[] = $this->id_conn->quote($val);
            }
         }
         $scape_quote = FALSE;
         $value = ' ('.implode(',',$in).') ';
      }
      elseif ( $value===NULL ) {
         $scape_quote = FALSE;
         $value = ' NULL ' ;
      }
      elseif ( gettype($value)==='boolean' ) {
         $scape_quote = FALSE;
         $value = $value ? ' TRUE ' : ' FALSE ' ;
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
    * agrega el query basico LIMIT
    * @author Carlos Garcia
    * @param Integer|Array $limit
    * @param Integer $offset
    * @return Object
    */
   public function limit($limit,$offset=null) {
      if (is_numeric($limit) || is_array($limit)) {
         if(is_array($limit)) {
            $temp   = $limit;
            $limit  =  $temp[0];
            $offset = @$temp[1];
         }

         $this->limit = " LIMIT {$limit} ";

         if(is_numeric($offset))
            $this->offset( $offset );

         $this->db_instanced();
         return $this;
      }

      return null;
   }

   /**
    * agrega el query basico OFFSET
    * @author Carlos Garcia
    * @param Integer|Array $limit
    * @param Integer $offset
    * @return Object
    */
   public function offset($offset) {
      if (is_numeric($offset)) {
         $this->offset = " OFFSET {$offset} ";

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
         // el resultado resuelto es almacenado en la variable $_ENV["daniia_union"]
         // para luego terminar de agruparlo
         $closure(new \Daniia\Daniia());
         $str = $_ENV["daniia_union"]->get_sql();
         $_ENV["daniia_union"] = null;
         $this->union .= ' UNION ' . $str;
      }

      $this->db_instanced();
      return $this;
   }



   public function lastId() {
      return (integer) $this->last_id;
   }

   public function lastQuery() {
      return $this->last_sql;
   }

   public function getData() {
      return $this->data;
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