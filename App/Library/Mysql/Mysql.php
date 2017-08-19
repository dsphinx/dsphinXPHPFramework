<?php
/**
 *  Copyright (c) 2015, dsphinx
 *  All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without
 *  modification, are permitted provided that the following conditions are met:
 *   1. Redistributions of source code must retain the above copyright
 *      notice, this list of conditions and the following disclaimer.
 *   2. Redistributions in binary form must reproduce the above copyright
 *      notice, this list of conditions and the following disclaimer in the
 *      documentation and/or other materials provided with the distribution.
 *   3. All advertising materials mentioning features or use of this software
 *      must display the following acknowledgement:
 *      This product includes software developed by the dsphinx.
 *   4. Neither the name of the dsphinx nor the
 *      names of its contributors may be used to endorse or promote products
 *     derived from this software without specific prior written permission.
 *
 *  THIS SOFTWARE IS PROVIDED BY dsphinx ''AS IS'' AND ANY
 *  EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 *  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 *  DISCLAIMED. IN NO EVENT SHALL dsphinx BE LIABLE FOR ANY
 *  DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 *  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 *  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 *  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 *  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *  Filename: Mysql.php
 *  Created : 7/7/15 10:41 PM
 */


require_once( 'Mysqli.php' );

/**
 * Class MyDB
 *
 *    easy Access to Mysql DB
 *
 * Methods:
 *              insertRecord($tblName, $knownTblNameFields, $HtmlPostRecords)
 *
 */
class MyDB extends Dbi
{
	static $__db;

	const    PAGINATION_DEFAULT_ROWS=10;
	const    PAGINATION_MAX_VISIBLE_PAGES=10;

	/**
	 * @return \MyDB
	 *
	 *   intialize DB
	 */
	static function init_db()
	{
		if ( !self::$__db ) {
			self::$__db=new self();
		}

		return self::$__db;
	}


	/**
	 * @param null $host
	 * @param null $user
	 * @param null $password
	 * @param null $DB
	 *
	 *   Check if we cant connect to mysql
	 */
	static function isConnectionOK($host=NULL, $user=NULL, $password=NULL, $DB=NULL)
	{

		$db = self::db();

		return $db->checkCredentials($host, $user, $password, $DB);
	}


	/**
	 * @return bool
	 *
	 *    quick reference to ge DB object
	 */
	static function db()
	{

		return ( self::$__db ? self::$__db : FALSE );
	}

	/**
	 * @param $table
	 * @param $formPOSTS
	 *
	 *  return all POST inputs name if exists on table
	 *
	 * @return array
	 *
	 */
	static function getArrayValues($table, $formPOSTS)
	{

		$tmp=array();
		while ( list( $key, $value )=each($table) ) {
			if ( isset( $formPOSTS[$key] ) ) {
				$tmp[$key]=$formPOSTS[$key];
			}
		}

		return $tmp;
	}


	/**
	 * @param $tblName               DB Table  ' Auth '
	 * @param $knownTblNameFields    Structure of $tblName
	 * @param $HtmlPostRecords       POST Array
	 *
	 *  insert new record on $tblName from $_POST $HtmlPostRecords with structure $knownTblNameFields
	 *
	 * @return idOfnewRecord
	 */
	static function insertRecord($tblName, $knownTblNameFields, $HtmlPostRecords)
	{

		$ret=FALSE;
		if ( is_array($HtmlPostRecords) && is_array($knownTblNameFields) && !is_null($tblName) ) {

			$db =self::db();
			$tmp=self::getArrayValues($knownTblNameFields, $HtmlPostRecords);

			if ( !( $ret=$db->insert($tblName, $tmp) ) ) {
				echo Html::_error_light(__METHOD__ . ": Table $tblName ", " Failed to insert new record, Table $tblName ");
			} else {
				//	echo Html::_error_light('Success', " Table $tblName");
			}
		} else {
			echo Html::_error_light('Error ' . __METHOD__, " Failed not arguments given Table $tblName");
			//Echoc::object($HtmlPostRecords); Echoc::object($knownTblNameFields);
		}

		return $ret;
	}

	/**
	 * @param $tblName
	 * @param $knownTblNameFields
	 * @param $HtmlPostRecords
	 *
	 *  insert new record on $tblName from $_POST $HtmlPostRecords with structure $knownTblNameFields
	 *          WHERE $HtmlPostRecords['name']  is UNIQUE
	 *
	 *  Use for linked tables,  foreign keys to primary, vice versa
	 *
	 *
	 *  DB Design field must by UQ :
	 *
	 * @return idOfnewRecord
	 */
	static function insertRecordUniqueField($tblName, $knownTblNameFields, $HtmlPostRecords)
	{

		$ret=FALSE;
		if ( is_array($HtmlPostRecords) && is_array($knownTblNameFields) && !is_null($tblName) ) {

			$db =self::db();
			$tmp=self::getArrayValues($knownTblNameFields, $HtmlPostRecords);

			foreach ( $tmp as $field=>$value ) {
				$value=self::stringNormalise($value);
				$key  =$field;
			}

			$sql=$db->rawQuery("SELECT id FROM $tblName WHERE trash=0 AND LOWER($key)=? LIMIT 1", array($value));

			if ( isset( $sql[0] ) ) {
				$ret=$sql[0]['id'];
				// Echoc::object($sql);
			} else {
				$ret=self::insertRecord($tblName, $knownTblNameFields, $HtmlPostRecords);
			}
		}

		return $ret;
	}


	/**
	 * @param $table
	 *
	 * @return mixed
	 *
	 *   describe Fields on tbl
	 */
	function description($table)
	{
		return $this->sql(" describe  " . $table);
	}


	/**
	 * @return mixed
	 *
	 *   Mysql get all tables from DB
	 */
	function show_tables()
	{
		return $this->sql(" show tables ");
	}


	/**
	 * @return array
	 *
	 *   return table Status
	 */
	function show_table_status()
	{

		$tmp2=$this->sql(" show TABLE STATUS ");
		$tmp =array();
		foreach ( $tmp2 as $r ) {
			$tmp[$r['Name']]=$r;
		}


		return $tmp;
	}


	/**
	 * @param $table
	 *
	 * @return mixed
	 *
	 *   optimize table not innodb
	 *
	 */
	function optimize_table($table)
	{

		$tmp=$this->sql(" OPTIMIZE table " . $table);

		return $tmp;
	}


	/**
	 * @return array
	 *
	 *  get DB Server status
	 */
	public function stats()
	{

		$db      =new self();
		$status  =explode('  ', mysqli_stat($db->_mysqli));
		$status[]="Server : " . mysqli_get_server_info($db->_mysqli);
		$db->close();

		return $status;
	}


	/**
	 * @param     $sql
	 * @param     $sql_params
	 * @param     $navigation
	 * @param     $_link_pagination
	 * @param int $current_page
	 *
	 *
	 *   Pagination SQL with rawQuery
	 *
	 * @return array
	 */
	static function pagination($sql, $sql_params, &$navigation, $_link_pagination, $current_page=1)
	{

		$db        =new self();
		$navigation='<div > <ul class="pagination"> ';
		$temp_sql  =explode("FROM", $sql);

		$temp="SELECT count(*) as temp_total FROM " . $temp_sql[1];

		$start=( $current_page - 1 ) * self::PAGINATION_DEFAULT_ROWS;

		$sql.=" LIMIT $start," . self::PAGINATION_DEFAULT_ROWS;
		//echo $sql;
		$logging=$db->rawQuery($temp, $sql_params);
		$totals =$logging[0]['temp_total'];

		$pages=ceil($totals / self::PAGINATION_DEFAULT_ROWS);

		if ( $current_page > 1 ) {
			$navigation.='<li><a href="' . $_link_pagination . ( $current_page - 1 ) . '">  &lsaquo; </a> </li>';
		}

		$_start=$current_page;
		$_end  =$pages;

		if ( $pages > self::PAGINATION_MAX_VISIBLE_PAGES ) {

			$_end=$_start + self::PAGINATION_MAX_VISIBLE_PAGES;

			if ( $_end > $pages ) {
				$_end=$pages;
			}

			if ( ( $_end - $_start ) < self::PAGINATION_MAX_VISIBLE_PAGES ) {
				$_start=$_end - self::PAGINATION_MAX_VISIBLE_PAGES;
			}
		}


		for ( $i=$_start; $i <= $_end; $i++ ) {
			$def=( $current_page == $i ) ? ' class="active" ' : NULL;
			$navigation.='<li ' . $def . '><a  href="' . $_link_pagination . ( $i ) . '"> ' . $i . ' </a> </li>';
		}


		if ( $current_page >= 1 && $current_page < $pages ) {
			$navigation.='<li><a href="' . $_link_pagination . ( $current_page + 1 ) . '">  &rsaquo; </a> </li>';
		}


		$navigation.="</ul>  </div> ";
		$logging=$db->rawQuery($sql, $sql_params);

		return $logging;
	}

	/**
	 *  normalise strings
	 *
	 * @param $string
	 *
	 * @return string
	 *
	 */
	public function stringNormalise($string)
	{
		return trim(strtolower($string));
	}


	/**
	 * @param $d
	 *
	 *   return any object to array
	 *
	 *    convert INPUT object to Array
	 *
	 * @return array
	 */
	static function objectToArray($d)
	{
		if ( is_object($d) ) {
			$d=get_object_vars($d);
		}

		if ( is_array($d) ) {
			return array_map(__METHOD__, $d);
		} else {
			return $d;
		}
	}

}