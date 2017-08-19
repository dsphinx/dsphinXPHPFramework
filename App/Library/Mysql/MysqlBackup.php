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
 *  Filename: MysqlBackup.php
 *  Created : 4/9/15 11:53 PM
 */


ini_set("memory_limit", "-1");
ini_set('max_execution_time', 2200);

require_once __DIR__ . '/../Unix/UnixFiles.php';


/**
 * Class MysqlBackup
 *
 *    Export Import SQL dump from UNIX Tools
 *
 *  Faster than custom code ..
 */
class MysqlBackup
{
	static $mysqlApps=array('mysql'     =>'/usr/bin/mysql',
	                        'mysqldump' =>'/usr/bin/mysqldump',
	                        'compress'  =>'/bin/bzip2',
	                        'uncompress'=>'/bin/bunzip2',
	                        'grep'      =>'/bin/grep',
	);


	/**
	 * @param      $fileOut
	 * @param null $user
	 * @param null $password
	 * @param null $DB
	 *
	 * @return bool
	 *
	 *         Export Current DB into compressed file  extension bz2
	 */
	static public function exportCompressFile($fileOut, $user=NULL, $password=NULL, $DB=NULL)
	{

		$user    =isset( $user ) ? $user : SQL_USER;
		$password=isset( $password ) ? $password : SQL_PASSWORD;
		$DB      =isset( $DB ) ? $DB : SQL_BDD;


		Unixfile::del($fileOut);
		ob_start();
		$cmd=self::$mysqlApps['mysqldump'] . " -u " . $user . " --password=" . $password . " " . $DB . " | " . self::$mysqlApps['grep'] . " -v 'SQL SECURITY DEFINER' |   " . self::$mysqlApps['compress'] . "  > $fileOut ";
		echo passthru($cmd);
		$var=ob_get_contents();
		ob_end_clean(); //Use this instead of ob_flush()

		$ret=( is_file($fileOut) && filesize($fileOut) > 0 ) ? TRUE : FALSE;

		return $ret;
	}


	/**
	 * @param      $compressedFile
	 * @param null $user
	 * @param null $password
	 * @param null $DB
	 *
	 * @return null|void
	 *
	 *   restore raw SQL File from cli
	 */
	static public function restoreFile($compressedFile, $user=NULL, $password=NULL, $DB=NULL)
	{

		$returnCode=NULL;
		$user      =isset( $user ) ? $user : SQL_USER;
		$password  =isset( $password ) ? $password : SQL_PASSWORD;
		$DB        =isset( $DB ) ? $DB : SQL_BDD;


		if ( !Controller::isRunningSandbox() ) {

			echo Html::_error_light('Restore DB', " only in sandbox function ");

			return;
		}

		$cmd      =self::$mysqlApps['mysql'] . " -u " . $user . " --password=" . $password . " " . $DB . "  <   $compressedFile  ";
		$last_line=exec($cmd, $DB, $returnCode);

		if ( $returnCode != 0 ) {
			echo Html::_error_light("MYSQL Error ", " failed to restore  $compressedFile " . "<br/> Error : $returnCode , last " . $last_line);
		}


		return $returnCode;
	}


	/**
	 * @param        $compressedFile
	 * @param bool   $remove
	 * @param string $fileExtension
	 *
	 * @return null|void
	 *
	 *
	 *  bzip2 compression
	 *
	 *  restore Compressed file bz2 into Mysql
	 */
	static public function restoreCompressFile($compressedFile, $remove=TRUE, $fileExtension="bz2")
	{

		$sqlImportBase=tempnam("/tmp", SQL_BDD . "_Import");

		$sqlImportSQL="$sqlImportBase.sql";
		$sqlImport   ="$sqlImportSQL.$fileExtension";

		Unixfile::smartCopy($compressedFile, $sqlImport);

		$retval=$returncode=NULL;

		$cmd      =self::$mysqlApps['uncompress'] . "  $sqlImport";
		$last_line=exec($cmd, $retval, $returncode);


		if ( $returncode == 0 ) {

			$returncode=self::restoreFile($sqlImportSQL);

			if ( $returncode == 0 ) {

				echo Html::_info("Restore DB", " Success on DB RESTORE file - $compressedFile ");

				trigger_error(" Diagnosis DB RESTORED [$compressedFile] , user system", "system");
			} else {
				echo Html::_error("Restore DB", "Error Code $returncode , failed to restore $compressedFile <br/> $php_errormsg");

			}
		}


		if ( $remove ) {
			Unixfile::del($compressedFile);
		}

		Unixfile::del($sqlImportBase);
		Unixfile::del($sqlImportSQL);
		Unixfile::del($sqlImport);

		return $returncode;
	}


}