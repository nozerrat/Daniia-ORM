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
    * Contiene los CASE de las sentencia SQL
    * @var String
    */
   private $case = "";
   private $when = "";
   private $else = "";
    
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
    * Obtiene la cantidad de filas de un query
    * @var Array
    */
   private $rowCount = 0;

   /**
    * Obtiene los errores de la sentencia ejecutada
    * @var Array
    */
   private $error = [];

   /**
    * Obtiene la ID de un registro para salvar los datos consultados
    * @var Array
    */
   private $find_save_data = FALSE;
   private $find_first_data = FALSE;
   private $find_delete_data = FALSE;

   /**
    * Obtiene los nombres de los campos de una tabla dada
    * @var Array
    */
   private $columnsData = [];

   /**
    * Identifica el tipo de fetch
    * Tipos: row, array, object, all, assoc, column, key_pair
    * @var string
    */
   public  $type_fetch = "object";
   private $FETCH_ROW      = 1;
   private $FETCH_ASSOC    = 2;
   private $FETCH_ARRAY    = 3;
   private $FETCH_ALL      = 4;
   private $FETCH_OBJECT   = 5;
   private $FETCH_COLUMN   = \PDO::FETCH_COLUMN;
   private $FETCH_KEY_PAIR = \PDO::FETCH_KEY_PAIR;

   /**
    * Operadores SQL
    * @var Array
    */
   private $operators = ['=', '<', '>', '<=', '>=', '<>', '!=','like', 'not like' , 'in', 'not in', 'is', 'is not', 'ilike', 'not ilike', 'between', 'not between'];

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


   private function apply_config_db() {
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
   }


   /**
    * Establese la coneccion a la Base de Datdos
    * @author Carlos Garcia
    * @return Object
    */
   private function connection() {
      $this->apply_config_db();
      try {
         if ( @$_ENV["daniia_connection"] && !$this->id_conn ) {
            $this->id_conn = $_ENV["daniia_connection"];
         }
         elseif ( !$this->id_conn ) {
            $this->id_conn = new \PDO($this->dsn, $this->user, $this->pass);
         }
      } catch (\PDOException $e) {
         die("Error: " . $e->getMessage() . "<br/>");
      }

      $this->get_instance();
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
      $this->case    = "";
      $this->when    = "";
      $this->else    = "";
      $this->rows    = [] ;
      $this->placeholder_data = [] ;
      $_ENV["daniia_daniia"]  = null;

      $this->get_instance();
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
         $this->resultset->setFetchMode( $this->{'FETCH_' . strtoupper( $this->type_fetch )} );
         if ( $getData ) {
            $this->rows     = $this->resultset->fetchAll();
            $this->rowCount = $this->resultset->rowCount();
            // while ( $row = $this->resultset->fetch() ) {
            //    $this->rows[] = $row;
            //    $this->rowCount++;
            // }
         }

         $this->get_instance();
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
      return "( ".$this->get(false)." )";
   }

   /**
    * almacena el Objeto si es instanciado desde un closure
    * @author Carlos Garcia
    * @return void
    **/
   private function get_instance() {
      $_ENV["daniia_daniia"] = $this;
   }



   /**
    * Obtiene el numero de filas de un query
    * @author Carlos Garcia
    * @return Integer
    */
   final public function rowCount() {
      return $this->rowCount;
   }



   /**
    * Entrecomilla una cadena de caracteres para usarla en una consulta
    * @author Carlos Garcia
    * @param String $val
    * @return String
    */
   final public function quote($val) {
      $this->connection();
      return $this->id_conn->quote($val);
   }

   /**
    * Inicia una transacción
    * @author Carlos Garcia
    * @return Objecto
    */
   final public function begin() {
      $this->apply_config_db();
      if ( @!$_ENV["daniia_connection"] ) {
         try {
            $_ENV["daniia_connection"] = new \PDO($this->dsn, $this->user, $this->pass);
         } catch (\PDOException $e) {
            die("Error: " . $e->getMessage() . "<br/>");
         }
         $_ENV["daniia_connection"]->beginTransaction();
      }
      return $this;
   }
   final public function beginTransaction() {
      $this->begin();
      return $this;
   }

   /**
    * Consigna una transacción
    * @author Carlos Garcia
    * @return Objecto
    */
   final public function commit() {
      if ( @$_ENV["daniia_connection"] ) {
         $_ENV["daniia_connection"]->commit();
         $_ENV["daniia_connection"] = NULL;
      }
      return $this;
   }

   /**
    * Revierte una transacción
    * @author Carlos Garcia
    * @return Objecto
    */
   final public function rollback() {
      if ( @$_ENV["daniia_connection"] ) {
         $_ENV["daniia_connection"]->rollBack();
         $_ENV["daniia_connection"] = NULL;
      }
      return $this;
   }

   /**
    * Obtiene información ampliada del error asociado con la última operación del gestor de sentencia
    * @author Carlos Garcia
    * @return Array
    */
   final public function error()	{
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
   final public function query( string $sql, $closure=NULL ) {
      $this->connection();
      $this->last_sql = $this->sql = $sql;
      $this->fetch();
      $this->data = $this->rows;
      $this->reset();
      if ($closure instanceof \Closure) {
         $this->data = $closure( $this->data, $this );
      }
      return $this->data ?: [];
   }

   /**
    * Ejecuta una sentencia SQL, devolviendo un conjunto de resultados como un Array
    * @author Carlos Garcia
    * @param Closure $closure
    * @return Array
    */
   final public function queryArray( string $sql, $closure=NULL ) {
      $type_fetch = $this->type_fetch;
      $this->type_fetch = 'assoc';
      $this->query( $sql );
      $this->type_fetch = $type_fetch;
      // $this->data = array_map(function($v){return(array)$v;},$this->data);
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
   final public function get($closure=NULL) {
      $this->connection();
      if ($this->table===NULL) {
         if ( !preg_match('/^ *FROM +\(/', $this->from) ) {
            $this->from = '';
         }
      }
      else {
         if (preg_match('/\./', $this->table) || !$this->schema ) {
            $this->from = str_replace("_table_", $this->table, $this->from);
         }
         else {
            $this->from = str_replace("_table_", $this->schema.'.'.$this->table, $this->from);
         }
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

      return $this->data ?: [];
   }

   /**
    * Retorna los datos consultados en formato Array
    * @author Carlos Garcia
    * @param Closure $closure
    * @return Array
    */
   final public function getArray($closure=NULL) {
      $type_fetch = $this->type_fetch;
      $this->type_fetch = 'assoc';
      $this->get();
      $this->type_fetch = $type_fetch;
      // $this->data = array_map(function($v){return(array)$v;},$this->data);
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
   final public function all($closure=NULL) {
      return $this->get($closure);
   }

   /**
    * Alia del GETARRAY
    * @see function getArray
    * @author Carlos Garcia
    * @param Closure $closure
    * @return Array
    */
   final public function allArray($closure=NULL) {
      return $this->getArray($closure);
   }



   /**
    * Devuelve la primera fila de una consulta
    * @author Carlos Garcia
    * @param Closure $closure
    * @return Object
    */
   final public function first($closure=NULL) {
      if(!$this->find_first_data) {
         $this->limit( 1 );
         $this->get();
      }

      if(is_array($this->data) && count($this->data) >= 1) {
         $this->find_save_data = TRUE;
         $this->data = $this->data[0];
      }
      
      if ($this->find_first_data===TRUE && $closure instanceof \Closure) {
         $this->data = $closure( $this->data, $this );
      }

      $this->find_first_data = FALSE;

      return $this->data ?: [];
   }

   /**
    * Devuelve la primera fila de una consulta
    * @see function first
    * @author Carlos Garcia
    * @param Closure $closure
    * @return Array
    */
   final public function firstArray( $closure=NULL ) {
      $type_fetch = $this->type_fetch;
      $this->type_fetch = 'assoc';
      $data = $this->first( $closure );
      $this->type_fetch = $type_fetch;
      return $this->data = $data;
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
   final public function lists($column, $index=NULL, $closure=NULL) {

      if ($index instanceof \Closure) {
         $closure = $index;
         $index = NULL;
      }

      if( gettype( $column )==='boolean' ) {
         throw new \Exception('El valor no debe ser un Boolean');
      }
      elseif (gettype( $column )==='NULL') {
         throw new \Exception('El valor no debe ser un NULL');
      }

      $type_fetch = $this->type_fetch;

      if ( $index!==NULL ) {
         $this->type_fetch = 'KEY_PAIR';
         $this->select( $index, $column );
      }
      elseif ( $column ) {
         $this->select( $column );
      }

      $this->get();

      $this->type_fetch = $type_fetch;

      if (count($this->data)) {
         if ($column && $index===NULL) {
            $temp_datas = $this->data;
            $this->data = [];
            foreach ($temp_datas as $object) {
               $this->data[] = $object->{strtolower($column)};
            }
         }

         if ($closure instanceof \Closure) {
            $this->data = $closure( $this->data, $this );
         }

         $this->find_first_data = $this->find_save_data = $this->find_delete_data = FALSE;

         return $this->data ?: [];
      }
      return NULL;
   }

   /**
    * Busca uno o varios registros en la Base de Datos
    * @author Carlos Garcia
    * @param Array $ids
    * @return Array|Object
    */
   final public function find($ids=[]) {
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

      $this->find_first_data = $this->find_save_data = $this->find_delete_data = TRUE;

      if ( count($ids) && !(@$ids[0] instanceof \Closure) ) {
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
   final public function save($closure=NULL) {
      $updated = FALSE;
      if ($this->find_save_data && @$this->data->{$this->primaryKey}) {
         $updated = $this->update((array)$this->data);
      }

      $last_id = NULL;
      if (($this->find_save_data && !@$this->data->{$this->primaryKey}) || !$updated) {
         $last_id = $this->insertGetId((array)$this->data);
      }

      $this->find_save_data = FALSE;

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
   final public function primaryKey($primaryKey) {
      $this->primaryKey = $primaryKey;
      return $this;
   }

   /**
    * Obtiene los nombres de las columnas de la tabla seleccionada
    * @author Carlos Garcia
    * @param String $table
    * @return Object
    */
   final public function columns($table=null) {
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

      try {
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
   final public function truncate() {
      $this->connection();
      $this->sql = "TRUNCATE TABLE {$this->table};";

      if($this->driver=='firebird'||$this->driver=='sqlite')
         $this->sql = "DELETE FROM {$this->table};";

      // $this->fetch(false);
      $this->fetch();

      return $this->resultset?true:false;
   }

   /**
    * Establese el nombre de la tabla
    * @author Carlos Garcia
    * @param $tables String
    * @return Object
    */
   final public function table($table) {
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

      $this->find_first_data = $this->find_save_data = $this->find_delete_data = FALSE;

      $this->get_instance();
      return $this;
   }

   /**
    * Establese los nombres dee las columnas a consultar
    * @author Carlos Garcia
    * @param string $select
    * @return Object
    */
   final public function select($select = "*") {
      $this->connection();
      if (func_num_args()>1)
         $select = func_get_args();
      else if(is_string($select)) {
         $select = [$select];
      }

      $select_tmp = [];
      foreach ($select as $value) {

         if ( $value===NULL )
            $select_tmp[] = ' NULL ';
         elseif ( gettype( $value )==='boolean' )
            $select_tmp[] = $value ? ' TRUE ' : ' FALSE ';
         elseif ($value instanceof \Closure) {
            $value(new \Daniia\Daniia());
            if (preg_match("/^CASE/", $_ENV['daniia_daniia']->case)) {
               $select_tmp[] = '( '.$_ENV['daniia_daniia']->case.' '.$_ENV['daniia_daniia']->when.' '.$_ENV['daniia_daniia']->else.' END )';
               $_ENV['daniia_daniia']->case = NULL;
            }
            else {
               $select_tmp[] = $_ENV['daniia_daniia']->get_sql();
               $_ENV['daniia_daniia'] = null;
            }
         }
         elseif ( in_array(strtolower(trim($value)), $this->columnsData, true) )
            $select_tmp[] = $value;
         else {
            $select_tmp[] = $value;
         }

      }

      $select = implode(",", $select_tmp);
      $this->select = str_replace("*", $select, $this->select);

      $this->find_first_data = $this->find_save_data = $this->find_delete_data = FALSE;

      $this->get_instance();
      return $this;
   }

   /**
    * Establece el nombre de la tabla a consultar
    * @author Carlos Garcia
    * @param $table String|Closure
    * @param $alias String
    * @return Object
    */
   final public function from($table="",$aliasFrom="") {
      $this->connection();

      $sql = "";
      $closure = false;
      // si es un closure lo ejecutamos
      if ($table instanceof \Closure) {
         // el resultado resuelto es almacenado en la variable $_ENV["daniia_from"]
         // para luego terminar de agruparlo
         $table(new \Daniia\Daniia());
         $sql = $_ENV["daniia_daniia"]->get_sql();
         $_ENV["daniia_daniia"] = null;
         $sql.= " AS ".($aliasFrom ? $aliasFrom : " _alias_ ");
         $closure = true;
      }
      else {
         if (func_num_args()>1){
            $table = func_get_args();
         }
         elseif(is_string($table)) {
            $table = [$table];
         }
         
         $schema = '';
         if ($this->driver=='pgsql' && $this->schema) {
            $schema = $this->schema ? $this->schema.'.' : '';
         }
         
         $tmp_table = [];
         foreach($table as $val) {
            if (preg_match('/\./', $val)) {
               $tmp_table[] = $val;
            }
            else {
               $tmp_table[] = $schema.$val;
            }
         }
         
         $table = implode(",", $tmp_table);
      }
      
      if ($this->extended && is_string($table) && !$closure) {
         $table = $schema.$this->table.",".$table;
      }
      
      
      if (!$this->bool_group) {
         if ( !$this->table && gettype( $table )==='string') {
            $this->table = $table;
         }
         $this->from = str_replace("_table_", ($closure ? $sql : ($table ?: $this->table)), $this->from);
      }

      $this->find_first_data = $this->find_save_data = $this->find_delete_data = FALSE;

      $this->get_instance();
      return $this;
   }

   /**
    * inserta datos en la base de datos
    * @author Carlos Garcia
    * @param $datas Array
    * @param $returning_id Boolean
    * @return Boolean
    */
   final public function insert(array $datas, bool $returning_id = false) {
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
   final public function insertGetId(array $datas) {
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
   final public function update(array $datas, $noApplyPrimaryKey = false) {
      if (is_array($datas) && count($datas)) {
         if (!is_array(@$datas[0])) {
            $datas = [$datas];
         }

         $this->connection();
         $this->columns();
         
         $this->last_sql = [];
         $this->placeholder_data = [];

         $where = $this->where;
         $whereTemp = str_replace('WHERE', '', $this->where);

         $rowCount = [];
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

            // $this->fetch(false);
            $this->fetch();

            $rowCount[] = $this->rowCount;

            $this->reset();

            if ( $this->tableOrig!==null ) {
               $this->table = $this->tableOrig;
            }

            $error = $this->error();
            if($error['message']) return false;
         }
         if ( count( $rowCount )===1 ) {
            $this->rowCount = $rowCount[0];
         }
         else {
            $this->rowCount = $rowCount;
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
   final public function delete( $ids = [] ) {
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
      if ( $this->find_delete_data ) {
         if(is_object($this->data) && $this->data) {
            $ids[] = $this->data->{$this->primaryKey};
         }

         if(is_array($this->data) && $this->data) {
            foreach($this->data as $data)
               $ids[] = $data->{$this->primaryKey};
         }

         $this->find_delete_data = FALSE;

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

      // $this->fetch(false);
      $this->fetch();

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
      if ( $column===NULL )
         $column = ' NULL ' ;
      elseif ( gettype( $column )==='boolean' )
         $column = $column ? ' TRUE ' : ' FALSE ' ;
      elseif ($column instanceof \Closure) {
         // el resultado resuelto es almacenado en la variable $_ENV para luego terminar de agruparlo
         $column(new \Daniia\Daniia(true));
         if (preg_match("/^CASE/", $_ENV['daniia_daniia']->case)) {
            $str = '( '.$_ENV['daniia_daniia']->case.' '.$_ENV['daniia_daniia']->when.' '.$_ENV['daniia_daniia']->else.' END )';
            $_ENV['daniia_daniia']->case = NULL;
         }
         else {
            if ( $clauseLower==='on' ) {
               $column = $_ENV['daniia_daniia']->{$clauseLower}.")";
               $closure = TRUE;
            }
            else {
               $column = $_ENV['daniia_daniia']->get_sql();
            }
            $_ENV['daniia_daniia'] = null;
         }
      }


      // si no es un operador valido
      if ( $operator instanceof \Closure )
         list($operator, $value, $scape_quote) = ['=', $operator, $value];
      elseif ( is_array($operator) )
         list($operator, $value, $scape_quote) = ['in', $operator, $value];
      elseif ( !in_array(strtolower(trim($operator)), $this->operators, true) ) {
         list($operator, $value, $scape_quote) = ['=', $operator, $value];
         if ( $scape_quote==='' && $clauseLower==='on' ) {
            $scape_quote = FALSE;
         }
         elseif ( $scape_quote==='' && $clauseLower!=='on' ) {
            $scape_quote = TRUE;
         }
      }


      if ( $value===NULL ) {
         $scape_quote = FALSE;
         $value = ' NULL ' ;
      }
      elseif ( gettype($value)==='boolean' ) {
         $scape_quote = FALSE;
         $value = $value ? ' TRUE ' : ' FALSE ' ;
      }
      elseif ($value instanceof \Closure) {
         // el resultado resuelto es almacenado en la variable $_ENV para luego terminar de agruparlo
         $value(new \Daniia\Daniia());
         
         if (preg_match("/^CASE/", $_ENV['daniia_daniia']->case)) {
            $value = '( '.$_ENV['daniia_daniia']->case.' '.$_ENV['daniia_daniia']->when.' '.$_ENV['daniia_daniia']->else.' END )';
            $_ENV['daniia_daniia']->case = NULL;
         }
         else {
            $value = $_ENV['daniia_daniia']->get_sql();
            $_ENV['daniia_daniia'] = null;
         }
         
         $scape_quote = false;
      }
      elseif (is_array($value) && (strtolower($operator)=='between'||strtolower($operator)=='not between') ) {
         $scape_quote = FALSE;
         $value = ' '.$this->id_conn->quote($value[0]).' AND '.$this->id_conn->quote($value[1]).' ';
      }
      elseif( is_array($value) ) {
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


      if ( $this->table && !preg_match("/,/", $this->table) ) {
         $this->columns();
      }


      if(gettype( $column )==='string' || gettype( $column )==='integer' || gettype( $column )==='double') {
         if($closure || $value==='') {
            if ( @preg_match("/\( +SELECT +\* +\(/", $column) ) {
               $str = preg_replace("/\( +SELECT +\* +\(/", '(', $column);
            }
            else {
               $str = $column;
            }
         }
         else {
            if ( in_array(strtolower(trim($value)), $this->columnsData, true) ) {
               $str = $column.' '.strtoupper($operator).' '.$value." ";
            }
            else {
               $str = $column.' '.strtoupper($operator).' '.($scape_quote===true?$this->id_conn->quote($value):$value)." ";
            }
         }
      }


      if ($this->bool_group) {
         if (preg_match('/\(/', $this->{$clauseLower})) {
            $this->{$clauseLower} .= " {$logicaOperator} {$str} ";
         }
         else {
            $this->{$clauseLower} .= " ({$str} ";
         }
      }
      else {
         if (preg_match("/".$clauseUpper."/", $this->{$clauseLower})) {
            $this->{$clauseLower} .= " {$logicaOperator} {$str} ";
         }
         else {
            $this->{$clauseLower} = " {$clauseUpper} {$str} ";
         }
      }

      $this->find_first_data = FALSE;

      if ( preg_match("/^CASE/", $this->case) ) {
         $_ENV['daniia_daniia']->case = $this->case;
         $_ENV['daniia_daniia']->when = $this->when;
         $_ENV['daniia_daniia']->else = $this->else;
         $this->get_instance();
         return $str;
      }

      $this->get_instance();
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
         $str = $_ENV["daniia_daniia"]->on;
         $_ENV["daniia_daniia"] = null;
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
         $get_sql = $_ENV["daniia_daniia"]->get_sql();
         $_ENV["daniia_daniia"] = null;
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
      }
      else {
         $this->join .= " {$type} JOIN {$schema}{$table} {$str} ";
      }

      $this->get_instance();
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
   final public function join($table,$column,$operator=null,$value=null, $scape_quote=false) {
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
   final public function innerJoin($table,$column,$operator=null,$value=null, $scape_quote=false) {
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
   final public function leftJoin($table,$column,$operator=null,$value=null, $scape_quote=false) {
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
   final public function rightJoin($table,$column,$operator=null,$value=null, $scape_quote=false) {
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
   final public function on($column, $operator = '', $value = '', $scape_quote=false){
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
   final public function orOn($column, $operator = '', $value = '', $scape_quote=false){
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
   final public function andOn($column, $operator = '', $value = '', $scape_quote=false){
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
   final public function where($column, $operator = '', $value = '', $scape_quote=true) {
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
   final public function orWhere($column, $operator = '', $value = '', $scape_quote=true) {
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
   final public function andWhere($column, $operator = '', $value = '', $scape_quote=true) {
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
   final public function having($column, $operator = '', $value = '', $scape_quote=true) {
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
   final public function orHaving($column, $operator = '', $value = '', $scape_quote=true) {
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
   final public function andHaving($column, $operator = '', $value = '', $scape_quote=true) {
      $this->clause($column, $operator, $value, $scape_quote,'having', 'AND');
      return $this;
   }



   /**
    * agrega el query ORDER BY basico
    * @author Carlos Garcia
    * @param String|Array $fields
    * @return Object
    */
   final public function orderBy($fields) {
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

         $this->get_instance();
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
   final public function groupBy($fields) {
      if (func_num_args()>0) {
         if (func_num_args()==1) {
            if (!is_array($fields)) {
               $fields = (array) $fields;
            }
         }else{
            $fields = func_get_args();
         }

         $this->groupBy = " GROUP BY ".implode(',',$fields);

         $this->find_first_data = $this->find_save_data = $this->find_delete_data = FALSE;
         
         $this->get_instance();
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
   final public function limit($limit,$offset=null) {
      if (is_numeric($limit) || is_array($limit)) {
         if(is_array($limit)) {
            $temp   = $limit;
            $limit  =  $temp[0];
            $offset = @$temp[1];
         }

         $this->limit = " LIMIT {$limit} ";

         if(is_numeric($offset)) {
            $this->offset( $offset );
         }
         $this->find_first_data = $this->find_save_data = $this->find_delete_data = FALSE;

         $this->get_instance();
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
   final public function offset($offset) {
      if (is_numeric($offset)) {
         $this->offset = " OFFSET {$offset} ";

         $this->find_first_data = $this->find_save_data = $this->find_delete_data = FALSE;

         $this->get_instance();
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
   final public function union($closure) {
      // si es un closure lo ejecutamos
      if ($closure instanceof \Closure) {
         // el resultado resuelto es almacenado en la variable $_ENV["daniia_union"]
         // para luego terminar de agruparlo
         $closure(new \Daniia\Daniia());
         $str = $_ENV["daniia_daniia"]->get_sql();
         $_ENV["daniia_daniia"] = null;
         $this->union .= ' UNION ' . $str;
      }

      $this->get_instance();
      return $this;
   }

   /**
    * opera sobre las clausulas indicadas
    * @author Carlos Garcia
    * @param  $column String|Closure
    * @param  $scape_quote string
    * @return Objeto.
   Sintax:
      CASE expression
         WHEN value THEN result
         [WHEN ...]
         [ELSE result]
      END

   Examples SQL:
      SELECT CASE WHEN 2=1 THEN 'one'
               WHEN 2=2 THEN 'two'
               ELSE 'other'
            END;

      SELECT CASE WHEN 2=(SELECT 1) THEN 'one'
               WHEN 2=(SELECT 2) THEN 'two'
               ELSE 'other'
            END;

      SELECT CASE WHEN (SELECT 2)=(SELECT 1) THEN 'one'
               WHEN (SELECT 2)=(SELECT 2) THEN 'two'
               ELSE 'other'
            END;

      SELECT CASE 2 WHEN 1 THEN 'one'
                  WHEN 2 THEN 'two'
                  ELSE 'other'
            END;

      SELECT CASE (SELECT '1'::INTEGER) WHEN (SELECT 1) THEN 'one'
                  WHEN (SELECT 2) THEN 'two'
                  ELSE (SELECT 'other'::TEXT)
            END;
    */
   final public function case($column, $operator=NULL, $value=TRUE, $scape_quote=TRUE) {
      $this->connection();
      $this->case = "CASE ";

      $closure_column = FALSE;
      $closure_value  = FALSE;

      if ( $this->table && !preg_match("/,/", $this->table) ) {
         $this->columns();
      }


      // si es un closure lo ejecutamos
      if ( $column===NULL ) {
         $column = ' NULL ' ;
      }
      elseif ( gettype( $column )==='boolean' ) {
         $column = $column ? ' TRUE ' : ' FALSE ' ;
      }
      elseif ($column instanceof \Closure) {
         $column(new \Daniia\Daniia(true));
         $column = $_ENV['daniia_daniia']->get_sql();
         $closure_column = TRUE;
      }
      elseif ( gettype( $column )==='string' && ( !in_array(strtolower(trim($column)), $this->columnsData, true) ) ) {
         $column = $this->id_conn->quote($column);
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


      if ( $value===NULL ) {
         $scape_quote = FALSE;
         $value = ' NULL ' ;
      }
      elseif ( gettype($value)==='boolean' ) {
         $scape_quote = FALSE;
         $value = $value ? ' TRUE ' : ' FALSE ' ;
      }
      elseif ($value instanceof \Closure) {
         // el resultado resuelto es almacenado en la variable $_ENV para luego terminar de agruparlo
         $value(new \Daniia\Daniia());
         $value = $_ENV['daniia_daniia']->get_sql();
         $closure_value = TRUE;
      }
      elseif (is_array($value) && (strtolower($operator)=='between'||strtolower($operator)=='not between') ) {
         $scape_quote = FALSE;
         $value = ' '.$this->id_conn->quote($value[0]).' AND '.$this->id_conn->quote($value[1]).' ';
      }
      elseif( is_array($value) ) {
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

      if (func_num_args()===1 || ($closure_column && !$closure_value)) {
         $get_sql = $column;
      }
      elseif ( in_array(strtolower(trim($value)), $this->columnsData, true) || ($closure_column && $closure_value) ) {
         $get_sql = $column.' '.strtoupper($operator).' '.$value." ";
      }
      else {
         $get_sql = $column.' '.strtoupper($operator).' '.($scape_quote===true?$this->id_conn->quote($value):$value)." ";
      }

      $this->case .= " {$get_sql} ";

      $this->get_instance();
      return $this;
   }
   final public function when($column, $operator=NULL, $value=TRUE, $return_value=NULL, $scape_quote=TRUE, $scape_quote1=FALSE) {
      $this->connection();

      $closure_column = FALSE;
      $closure_value  = FALSE;
      
      if (!preg_match('/^CASE /', $this->case)) {
         $this->case = "CASE ";
      }

      if ( $this->table && !preg_match("/,/", $this->table) ) {
         $this->columns();
      }


      // si es un closure lo ejecutamos
      if ( $column===NULL )
         $column = ' NULL ' ;
      elseif ( gettype( $column )==='boolean' )
         $column = $column ? ' TRUE ' : ' FALSE ' ;
      elseif ($column instanceof \Closure) {
         $column(new \Daniia\Daniia(true));
         $column = $_ENV['daniia_daniia']->get_sql();
         $closure_column = TRUE;
      }
      elseif ( gettype( $column )==='string' && ( !in_array(strtolower(trim($column)), $this->columnsData, TRUE) ) ) {
         $column = $this->id_conn->quote($column);
      }

      // sobrecarga de la funcion
      if ( func_num_args()===2 )
         list($operator, $return_value) = [NULL, $operator];
      elseif ( func_num_args()===3 )
         list($value, $return_value) = [TRUE, $value];
      elseif ( func_num_args()===4 && !in_array(strtolower(trim($operator)), $this->operators, TRUE) )
         list($value, $return_value, $scape_quote) = [TRUE, $value, $return_value];
      elseif ( func_num_args()===5 && !in_array(strtolower(trim($operator)), $this->operators, TRUE) ) {
         list($value, $return_value, $scape_quote) = [TRUE, $value, $return_value];
      }


      // si no es un operador valido
      if ( $operator instanceof \Closure )
         list($operator, $value, $scape_quote) = ['=', $operator, $value];
      elseif ( is_array($operator) )
         list($operator, $value, $scape_quote) = ['in', $operator, $value];
      elseif ( !in_array(strtolower(trim($operator)), $this->operators, TRUE) ) {
         list($operator, $value, $scape_quote) = ['=', $operator, $value];
      }


      if ( $value===NULL ) {
         $value = ' NULL ' ;
         $scape_quote = FALSE;
      }
      elseif ( gettype($value)==='boolean' ) {
         $value = $value ? ' TRUE ' : ' FALSE ' ;
         $scape_quote = FALSE;
      }
      elseif ($value instanceof \Closure) {
         // el resultado resuelto es almacenado en la variable $_ENV para luego terminar de agruparlo
         $value(new \Daniia\Daniia());
         $value = $_ENV['daniia_daniia']->get_sql();
         $closure_value = TRUE;
         $scape_quote = FALSE;
      }
      elseif (is_array($value) && (strtolower($operator)=='between'||strtolower($operator)=='not between') ) {
         $value = ' '.$this->id_conn->quote($value[0]).' AND '.$this->id_conn->quote($value[1]).' ';
         $scape_quote = FALSE;
      }
      elseif( is_array($value) ) {
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
         $value = ' ('.implode(',',$in).') ';
         $scape_quote = FALSE;
      }


      if (func_num_args()===2 || ($closure_column && !$closure_value))
         $get_sql = $column;
      elseif ( in_array(strtolower(trim($value)), $this->columnsData, true) || ($closure_column && $closure_value) || (!$closure_column && $closure_value) )
         $get_sql = $column.' '.strtoupper($operator).' '.$value." ";
      else {
         $get_sql = $column.' '.strtoupper($operator).' '.($scape_quote1===true?$this->id_conn->quote($value):$value)." ";
      }


      if ( $return_value===NULL )
         $return_value = ' NULL ' ;
      elseif ( gettype( $return_value )==='boolean' )
         $return_value = $return_value ? ' TRUE ' : ' FALSE ' ;
      elseif ($return_value instanceof \Closure) {
         $return_value(new \Daniia\Daniia(true));
         $return_value = $_ENV['daniia_daniia']->get_sql();
      }
      else {
         if( is_array($return_value) ) {
            throw new \Exception('El Valor de retorno no debe ser un Array');
         }
         $return_value = $scape_quote===true ? $this->id_conn->quote($return_value) : $return_value;
      }

      $this->when .= " WHEN {$get_sql} THEN {$return_value} ";

      $this->get_instance();
      return $this;
   }
   final public function else($return_value=NULL) {
      $this->connection();

      if ( $this->table && !preg_match("/,/", $this->table) ) {
         $this->columns();
      }

      if ( $return_value===NULL )
         $get_sql = ' NULL ';
      elseif ( gettype( $return_value )==='boolean' )
         $get_sql = $return_value ? ' TRUE ' : ' FALSE ';
      elseif ($return_value instanceof \Closure) {
         $return_value(new \Daniia\Daniia());
         $get_sql = $_ENV['daniia_daniia']->get_sql();
      }
      elseif ( in_array(strtolower(trim($return_value)), $this->columnsData, true) )
         $get_sql = $return_value;
      elseif ( gettype( $return_value )==='string' )
         $get_sql = $this->id_conn->quote($return_value);
      else {
         $get_sql = $return_value;
      }

      $this->else = " ELSE {$get_sql} ";

      $this->get_instance();
      return $this;
   }

   final public function lastId() {
      return (integer) $this->last_id;
   }

   final public function lastQuery() {
      return $this->last_sql;
   }

   final public function getData() {
      return $this->data ?: [];
   }

   final public function getDataFirst() {
      if ( is_array( $this->data ) ) {
         if ( isset( $this->data[0] ) ) {
            return $this->data[0] ?: [];
         }
         elseif ($this->data) {
            return $this->data ?: [];
         }
         else {
            return [];
         }
      }
      elseif( $this->data ) {
         return $this->data ?: [];
      }
      else {
         return new \stdClass();
      }
   }

   final public function getDataLists($column,$index=NULL) {

      if (count($this->data)) {
         $temp_datas = $this->data;
         $new_data = [];
         if ($column && $index===NULL) {
            foreach ($temp_datas as $object) {
               if ( is_array( $object ) ) {
                  $new_data[] = $object[$column];
               }
               else {
                  $new_data[] = $object->{$column};
               }
            }
         }
         elseif ($column && $index!==NULL) {
            foreach ($temp_datas as $key => $object) {
               if ( is_array( $object ) ) {
                  $new_data[$object[$index]] = $object[$column];
               }
               else {
                  $new_data[$object->{$index}] = $object->{$column};
               }
            }
         }

         return $new_data;
      }
      return [];
   }



   final public function __set($name, $value) {
      if(!is_object($this->data))
         $this->data = new \stdClass();
      $this->data->{$name} = $value;
   }

   final public function __get($name) {
      return @$this->data->{$name};
   }
}