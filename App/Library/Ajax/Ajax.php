<?php
/**
 *  Copyright (c) 2013, dsphinx@plug.gr
 *  All rights reserved.
 *
 *
 *      AJAX Calls with
 *                      DB support  because sessions are in Sessions Mysql Table
 *
 *
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
 *
 */


/**
 *
 *  Must Have Libraries in order to access $_SESSION stored in DB
 *
 */
//  require_once ('../config/configuration.php');
require_once( __DIR__ . '/../../config/configuration.php' );
require_once( $_SESSION['PATHS']['LIBRARIES'] . '/Mysql/Mysql.php' );

setlocale(LC_ALL, 'el_GR.UTF8');


/**
 * Class Ajax_Call
 *
 * Minimal Protections XSS
 *
 */
class Ajax_Call
{
	static $_page_languageID=1;
	static $db;

	static function db()
	{
		self::$db=new MyDB();

		return self::$db;
	}

	/**
	 * @param        $section
	 * @param        $message
	 * @param string $table
	 * @param null   $geolocation
	 *
	 * @return bool
	 *
	 * CREATE TABLE `Logging` (
	 * `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	 * `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
	 * `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	 * `section` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
	 * `browser` varchar(300) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
	 * `message` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
	 * `coordinates` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
	 * PRIMARY KEY (`id`)
	 * ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Logging ';
	 *
	 */
	static public function Logger($section, $message, $table="Logging", $geolocation=NULL)
	{

		$db  =self::$db;
		$_ret=TRUE;

		$record               =array();
		$record['section']    =$section;
		$record['ip']         =isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : getenv("REMOTE_ADDR");
		$record['browser']    =getenv("HTTP_USER_AGENT");
		$record['message']    =$message;
		$record['coordinates']=$geolocation;

		if ( !$db->insert($table, $record) ) $_ret=FALSE;

		return $_ret;
	}

	/**
	 * @return int
	 *
	 *  get current Language ID
	 */
	static public function getLanguageID()
	{

		$db=self::$db;

		$db->where("prefix", $_COOKIE['LANGUAGE']);
		$sqlCmd                ='SELECT id FROM Languages  ';
		$tmp                   =$db->query($sqlCmd);
		self::$_page_languageID=$tmp[0]['id'] ? $tmp[0]['id'] : 1;

		return self::$_page_languageID;
	}


	/**
	 * @param $variable , ex $_GET or $_POST
	 *
	 * Protect via Ajax Minimal XSS code
	 *
	 *
	 * @return bool|string
	 *
	 */
	public static function IN($variable)
	{
		$_variable=htmlentities(trim($variable), ENT_QUOTES, 'UTF-8');
		$_return  =$_variable;

		if ( preg_match("/[<&>]/", $_variable) ) {
			// trigger_error(" Possible XSS attack ? [ $variable ]");
			$_return=strip_tags($variable);
		}

		return $_return;
	}

	/**
	 * @param $variables
	 *
	 * Return JSON encode Output Info
	 *
	 *
	 */
	public static function OUT($variables)
	{

		if ( !is_array($variables) ) echo htmlspecialchars(json_encode(array("Code"=>$variables)), ENT_NOQUOTES); else
			echo htmlspecialchars(json_encode($variables), ENT_NOQUOTES);

		return;
	}

	/**
	 * @return bool
	 *
	 *  Accept only from domain
	 * Trying to drop calls outside Server domain calls
	 *
	 *  if ($allow_from_ip)
	 *   allow from server ip also
	 */
	public static function is_Call_from_Host($allow_from_ip=FALSE)
	{
		$_return=TRUE;

		$ref=$_SERVER['HTTP_REFERER'];
		$ssl=$_SERVER['SERVER_PORT'];
		$ip =$_SERVER['SERVER_ADDR'];

		$check1=stripos($ref, (string)EMAIL_HOST);
		$check2=strpos($ref, $ip);

		if ( $check1 === FALSE && $ssl != '443' ) {
			$_return=FALSE;
		}

		if ( $allow_from_ip ) {
			if ( $check2 !== FALSE ) {
				$_return=TRUE;
			}
		}

		return $_return;
	}

	public static function redir_after_calls($url)
	{
		echo '<script type="text/javascript">
            	window.location.href= "' . $url . '"</script>
            	<noscript><meta http-equiv="refresh" content="0;url=' . $url . '" /></noscript>	';

		return;
	}

}


$db=Ajax_Call::db();
