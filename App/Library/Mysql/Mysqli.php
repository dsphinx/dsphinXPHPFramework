<?php

/**
 *
 * @version         : 4.0
 * @date            : 5/9/2015
 * @date            : 24/01/2013
 * @author          Melisides Constantinos (dsphinx@gmail.com)
 * @author          Jeffery Way <jeffrey@jeffrey-way.com>
 * @author          Josh Campbell <jcampbell@ajillion.com>
 * @Description     :  mysqli interface   , mysqli
 *                  modified by dsphinx
 *                  required                 InnoDB Storage Engine ( transaction, level etc)
 *                  Licensed under MIT licence:
 *                  http://www.opensource.org/licenses/mit-license.php
 **/
class Dbi extends Exception
{

	/**
	 *    Security Tip
	 *
	 *    MySQL User SQL_USER must only have access : SELECT , INSERT, UPDATE, execute stored procedures
	 *
	 *      UPDATE : to other user ?
	 *
	 */
	var $Host=SQL_HOST;
	var $Database=SQL_BDD;
	var $User=SQL_USER;
	var $Password=SQL_PASSWORD;

	protected static $_lastStmtError;
	protected static $_instance;
	/**
	 * MySQLi instance
	 *
	 * @var object
	 */
	protected $_mysqli;
	/**
	 * The SQL query to be prepared and executed
	 *
	 * @var object
	 */
	protected $_query;
	/**
	 * An array that holds where conditions 'fieldname' => 'value'
	 *
	 * @var array
	 */
	protected $_where=array();
	/**
	 * Dynamic type list for where condition values
	 *
	 * @var array
	 */
	protected $_whereTypeList;
	/**
	 * Dynamic type list for table data values
	 *
	 * @var array
	 */
	protected $_paramTypeList;

	public $_last_insert_id;
	/**
	 * Dynamic array that holds a combination of where condition/table data value types and parameter referances
	 *
	 * @var array
	 */
	protected $_bindParams=array(''); // Create the empty 0 index
	protected $transaction_in_progress;

	/*
	 *
	 *  Object constructor
	 */
	public function __construct()
	{
		$this->_mysqli=new mysqli($this->Host, $this->User, $this->Password, $this->Database) or $this->halt("Link_ID == false, connect failed");

		if ( !mysqli_set_charset($this->_mysqli, "utf8") ) {
			self::halt("Mysqli interface : Error loading character set utf8: %s\n", mysqli_error($link));
		}


		self::$_instance=$this;
	}

	/**
	 * @param string $msg
	 *
	 *   display Error and die()
	 */
	function halt($msg="")
	{

		echo Html::_error(" Database error !", "  $this->Errno ($this->Error) ");

		die( "<h3>DB mysql / [$msg] Statement /</h3><br>Session halted." );
	}


	/**
	 * @param null $host
	 * @param null $user
	 * @param null $password
	 * @param null $DB
	 *
	 * @return bool
	 *
	 *   check if given Credentials is correct via configuration.php
	 */
	function checkCredentials($host=NULL, $user=NULL, $password=NULL, $DB=NULL)
	{

		$ret     =TRUE;
		$host    =isset( $host ) ? $host : $this->Host;
		$user    =isset( $user ) ? $user : $this->User;
		$password=isset( $password ) ? $password : $this->Password;
		$DB      =isset( $DB ) ? $DB : $this->Database;

		try {
			$db=new PDO("mysql:host=$host;dbname=$DB", $user, $password);
		} catch ( PDOException $e ) {

			echo Html::_error_light("MYSQL Connection Error : ","Host: $host  User: $user  Pass: $password DB: $DB  <br/> ".  $e->getMessage());
			$ret=FALSE;
		}


		return $ret;
	}


	/**
	 * A method of returning the static instance to allow access to the
	 * instantiated object from within another class.
	 * Inheriting this class would require reloading connection info.
	 *
	 * @uses $db = MySqliDb::getInstance();
	 *
	 * @return object Returns the current instance.
	 */
	public static function getInstance()
	{
		return self::$_instance;
	}


	//
	//  change DB
	//
	public function change_db($dbname)
	{
		mysqli_select_db($this->_mysqli, $dbname);
	}

	public function get_dbname()
	{

		if ( $result=mysqli_query($this->_mysqli, "SELECT DATABASE()") ) {
			$row=mysqli_fetch_row($result);
			mysqli_free_result($result);

			return ( $row[0] );
		}


	}


	public static function sql($query, $wherearray=NULL)
	{

		$db=self::getInstance();
		//	if ($wherearray)
		///		while( list($key,$val) = each($wherearray)) {
		//	   	 $db->where($key,$val);
		//			echo "Where FIX ME mysqli_interace.php : { $query }  [$key $val] <br/>";
		//		}

		$ret=$db->query($query);

		return $ret;
	}

	public function next_record()
	{


	}


	/**
	 * Reset states after an execution
	 *
	 * @return object Returns the current instance.
	 */
	protected function reset()
	{
		$this->_where     =array();
		$this->_bindParams=array(''); // Create the empty 0 index
		unset( $this->_query );
		unset( $this->_whereTypeList );
		unset( $this->_paramTypeList );
	}

	/**
	 * Pass in a raw query and an array containing the parameters to bind to the prepaird statement.
	 *
	 * @param string $query    Contains a user-provided query.
	 * @param array  $bindData All variables to bind to the SQL statment.
	 *
	 * @return array Contains the returned rows from the query.
	 */
	public function rawQuery($query, $bindParams=NULL)
	{
		$this->_query=filter_var($query, FILTER_SANITIZE_STRING);
		$stmt        =$this->_prepareQuery();

		if ( gettype($bindParams) === 'array' ) {
			$params=array(''); // Create the empty 0 index
			foreach ( $bindParams as $prop=>$val ) {
				$params[0].=$this->_determineType($val);
				//	array_push($params, &$bindParams[$prop]);
				$params[]=   &$bindParams[$prop];

			}

			call_user_func_array(array($stmt,
			                           'bind_param'
			), $params);
		}

		$stmt->execute();
		$this->reset();

		$results=$this->_dynamicBindResults($stmt);

		return $results;
	}

	/**
	 *
	 * @param string $query   Contains a user-provided select query.
	 * @param int    $numRows The number of rows total to return.
	 *
	 * @return array Contains the returned rows from the query.
	 */
	public function query($query, $numRows=NULL)
	{
		$this->_query=filter_var($query, FILTER_SANITIZE_STRING);
		$stmt        =$this->_buildQuery($numRows);
		$stmt->execute();
		$this->reset();

		$results=$this->_dynamicBindResults($stmt);

		return $results;
	}

	/**
	 * A convenient SELECT * function.
	 *
	 * @param string  $tableName The name of the database table to work with.
	 * @param integer $numRows   The number of rows total to return.
	 *
	 * @return array Contains the returned rows from the select query.
	 */
	public function get($tableName, $numRows=NULL)
	{

		$this->_query="SELECT * FROM $tableName";
		$stmt        =$this->_buildQuery($numRows);
		$stmt->execute();
		$this->reset();

		$results=$this->_dynamicBindResults($stmt);

		return $results;
	}

	/**
	 *
	 * @param <string $tableName The name of the table.
	 * @param array $insertData Data containing information for inserting into the DB.
	 *
	 * @return boolean Boolean indicating whether the insert query was completed succesfully.
	 */
	public function insert($tableName, $insertData)
	{
		$this->_query="INSERT into $tableName";
		$stmt        =$this->_buildQuery(NULL, $insertData);
		$stmt->execute();
		$this->reset();
		//  Echoc::object($insertData);
		( $stmt->affected_rows ) ? $result=$stmt->insert_id : $result=FALSE;

		if ( $stmt->errno ) {
			if ( RUNNING_MODE == "sandbox" ) {
				self::$_lastStmtError=" stmt_error = " . $stmt->errno . ", " . $stmt->error;
				echo __FILE__ . " Mysqli insert - Only for SANDBOX USE not on live  " . __LINE__ . " <br/>";
				Echoc::object($insertData);
				Echoc::object($stmt);
				echo "<br/> Var dump ";
				var_dump($insertData);
				var_dump($stmt);
				echo "<br/> " . self::$_lastStmtError;
			}
		}

		return $result;
	}

	/**
	 * Update query. Be sure to first call the "where" method.
	 *
	 * @param string $tableName The name of the database table to work with.
	 * @param array  $tableData Array of data to update the desired row.
	 *
	 * @return boolean
	 */
	public function update($tableName, $tableData)
	{
		$this->_query="UPDATE $tableName SET ";

		$stmt=$this->_buildQuery(NULL, $tableData);
		$stmt->execute();
		$this->reset();

		( $stmt->affected_rows ) ? $result=TRUE : $result=FALSE;

		return $result;
	}

	/**
	 * Delete query. Call the "where" method first.
	 *
	 * @param string  $tableName The name of the database table to work with.
	 * @param integer $numRows   The number of rows to delete.
	 *
	 * @return boolean Indicates success. 0 or 1.
	 */
	public function delete($tableName, $numRows=NULL)
	{
		$this->_query="DELETE FROM $tableName";

		$stmt=$this->_buildQuery($numRows);
		$stmt->execute();
		$this->reset();

		( $stmt->affected_rows ) ? $result=TRUE : $result=FALSE;

		return $result;
	}

	/**
	 * This method allows you to specify multipl (method chaining optional) WHERE statements for SQL queries.
	 *
	 * @uses $MySqliDb->where('id', 7)->where('title', 'MyTitle');
	 *
	 * @param string $whereProp  The name of the database field.
	 * @param mixed  $whereValue The value of the database field.
	 */
	public function where($whereProp, $whereValue)
	{
		$this->_where[$whereProp]=$whereValue;

		return $this;
	}

	/**
	 * This method is needed for prepared statements. They require
	 * the data type of the field to be bound with "i" s", etc.
	 * This function takes the input, determines what type it is,
	 * and then updates the param_type.
	 *
	 * @param mixed $item Input to determine the type.
	 *
	 * @return string The joined parameter types.
	 */
	protected function _determineType($item)
	{
		switch ( gettype($item) ) {
			case 'NULL':
			case 'string':
				return 's';
				break;

			case 'integer':
				return 'i';
				break;

			case 'blob':
				return 'b';
				break;

			case 'double':
				return 'd';
				break;
		}
	}

	/**
	 * Abstraction method that will compile the WHERE statement,
	 * any passed update data, and the desired rows.
	 * It then builds the SQL query.
	 *
	 * @param int   $numRows   The number of rows total to return.
	 * @param array $tableData Should contain an array of data for updating the database.
	 *
	 * @return object Returns the $stmt object.
	 */
	protected function _buildQuery($numRows=NULL, $tableData=NULL)
	{
		( gettype($tableData) === 'array' ) ? $hasTableData=TRUE : $hasTableData=FALSE;
		( !empty( $this->_where ) ) ? $hasConditional=TRUE : $hasConditional=FALSE;

		// Did the user call the "where" method?
		if ( !empty( $this->_where ) ) {

			// if update data was passed, filter through and create the SQL query, accordingly.
			if ( $hasTableData ) {
				$i  =1;
				$pos=strpos($this->_query, 'UPDATE');
				if ( $pos !== FALSE ) {
					foreach ( $tableData as $prop=>$value ) {
						// determines what data type the item is, for binding purposes.
						$this->_paramTypeList.=$this->_determineType($value);

						// prepares the reset of the SQL query.
						( $i === count($tableData) ) ? $this->_query.=$prop . ' = ?' : $this->_query.=$prop . ' = ?, ';

						$i++;
					}
				}
			}

			//Prepair the where portion of the query
			$this->_query.=' WHERE ';
			$i=1;
			foreach ( $this->_where as $column=>$value ) {
				// Determines what data type the where column is, for binding purposes.
				$this->_whereTypeList.=$this->_determineType($value);

				// Prepares the reset of the SQL query.
				( $i === count($this->_where) ) ? $this->_query.=$column . ' = ?' : $this->_query.=$column . ' = ? AND ';

				$i++;
			}

		}

		// Determine if is INSERT query
		if ( $hasTableData ) {
			$pos=strpos($this->_query, 'INSERT');

			if ( $pos !== FALSE ) {
				//is insert statement
				$keys  =array_keys($tableData);
				$values=array_values($tableData);
				$num   =count($keys);

				// wrap values in quotes
				foreach ( $values as $key=>$val ) {
					$values[$key]="'{$val}'";
					$this->_paramTypeList.=$this->_determineType($val);
				}

				$this->_query.='(' . implode($keys, ', ') . ')';
				$this->_query.=' VALUES(';
				while ( $num !== 0 ) {
					( $num !== 1 ) ? $this->_query.='?, ' : $this->_query.='?)';
					$num--;
				}
			}
		}

		// Did the user set a limit
		if ( isset( $numRows ) ) {
			$this->_query.=" LIMIT " . (int)$numRows;
		}

		// Prepare query
		$stmt=$this->_prepareQuery();

		// Prepare table data bind parameters
		if ( $hasTableData ) {
			$this->_bindParams[0]=$this->_paramTypeList;
			foreach ( $tableData as $prop=>$val ) {
				//	array_push($this->_bindParams, &$tableData[$prop]);
				$this->_bindParams[]=   &$tableData[$prop];

			}
		}
		// Prepare where condition bind parameters
		if ( $hasConditional ) {
			if ( $this->_where ) {
				$this->_bindParams[0].=$this->_whereTypeList;
				foreach ( $this->_where as $prop=>$val ) {
					//			array_push($this->_bindParams, &$this->_where[$prop]);
					$this->_bindParams[]=  &$this->_where[$prop];

				}
			}
		}
		// Bind parameters to statment
		if ( $hasTableData || $hasConditional ) {
			call_user_func_array(array($stmt,
			                           'bind_param'
			), $this->_bindParams);
		}

		return $stmt;
	}

	/**
	 * This helper method takes care of prepared statements' "bind_result method
	 * , when the number of variables to pass is unknown.
	 *
	 * @param object $stmt Equal to the prepared statement object.
	 *
	 * @return array The results of the SQL fetch.
	 */
	protected function _dynamicBindResults($stmt)
	{
		$parameters=array();
		$results   =array();

		$meta=$stmt->result_metadata();

		while ( $field=$meta->fetch_field() ) {
			//	array_push($parameters, &$row[$field->name]);
			$parameters[]=  &$row[$field->name];
			//		array_push($parameters, &$row[$field->name]);
		}

		call_user_func_array(array($stmt,
		                           'bind_result'
		), $parameters);

		while ( $stmt->fetch() ) {
			$x=array();
			foreach ( $row as $key=>$val ) {
				$x[$key]=$val;
			}
			array_push($results, $x);
		}

		return $results;
	}

	/**
	 * Method attempts to prepare the SQL query
	 * and throws an error if there was a problem.
	 */
	protected function _prepareQuery()
	{
		if ( !$stmt=$this->_mysqli->prepare($this->_query) ) {
			trigger_error("Problem preparing query ($this->_query) " . $this->_mysqli->error, E_USER_ERROR);
		}

		return $stmt;
	}

	public function __destruct()
	{
		$this->_mysqli->close();
	}

	public function close()
	{
		return TRUE;
	}

	//  January 2013  Shared

	/**
	 * set autocommit for transcation processing
	 * mysqli_autocommit | mysqli::autocommit
	 *
	 * @param boolean $mode
	 *
	 * @return boolean
	 */
	public function autocommit($mode=FALSE)
	{
		return $this->_mysqli->autocommit($mode);
	}

	/**
	 * commit
	 * mysqli_commit | mysqli::commit
	 *
	 * @return boolean
	 */
	public function commit()
	{
		$this->transaction_in_progress=FALSE;

		return $this->_mysqli->commit();
	}


	/**
	 * rollback
	 * mysqli_rollback | mysqli::rollback
	 *
	 * @return boolean
	 */
	public function rollback()
	{
		$this->transaction_in_progress=FALSE;

		return $this->_mysqli->rollback();
	}


	/*

	 * @param string/array $tablename
	 * @param string $lock_type
	 * @return boolean
	 */
	public function lock($tablename, $lock_type="WRITE")
	{
		$lock_types=array('READ',
		                  'READ LOCAL',
		                  'WRITE',
		                  'LOW_PRIORITY WRITE',
		);

		if ( !in_array($lock_type, $lock_types) ) {
			$lock_type='WRITE';
		}

		$query='LOCK TABLES ';
		if ( is_array($tablename) ) {
			foreach ( $tablename as $t ) {
				$query.="{$t} {$lock_type}, ";
			}
			$query=substr($query, 0, -2);
		} else {
			$query.="{$tablename} {$lock_type}";
		}

		return $this->_mysqli->query($query);
	}

	/**
	 *   unlock
	 *
	 * @return boolean
	 */
	public function unlock()
	{
		return $this->_mysqli->query("UNLOCK TABLES;");
	}

	// dsphinx error
	public function error()
	{
		$ret=array( //"sql"        => $this->_query,
		            "errorID"   =>$this->_mysqli->connect_errno,
		            "connectE"  =>$this->_mysqli->connect_error,
		            "error"     =>$this->_mysqli->error,
		            "errorno"   =>$this->_mysqli->errno,
		            'list'      =>$this->_mysqli->error_list,
		            "sqlstate"  =>$this->_mysqli->sqlstate,
		            'list'      =>$this->_mysqli->error_list,
		            "stmt_error"=>self::$_lastStmtError,
		);


		return $ret;
	}


	public function get_last_id()
	{

		return $this->_mysqli->insert_id;
	}


	//  TRANSACTIONS
	public function transaction()
	{
		$this->autocommit(FALSE);
		$this->transaction_in_progress=TRUE;
		register_shutdown_function(array($this,
		                                 "mysqli__shutdown_check"
		));
	}

	public function mysqli__shutdown_check()
	{
		if ( $this->transaction_in_progress ) {
			$this->rollback();
			//		trigger_error(" mysql auto shutdown functiom  ", E_USER_NOTICE);
		}
	}

	public function transaction_isolcation_level($level="REPEATABLE READ")
	{
		//  SERIALIZABLE , READ COMMITTED, READ UNCOMMITTED, REPEATABLE READ
		return $this->_mysqli->query(" SET SESSION TRANSACTION ISOLATION LEVEL $level ;");

	}


	public function procedure($name)
	{
		//run the store proc
		$result=$this->_mysqli->query("CALL `$name`; ");

		return $result;
	}

} // END class

