<?php

/**
 *  Copyright (c) 2013-2015, dsphinx@plug.gr
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
 *  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 *  SERVICES;
 *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 *  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 *  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 *  THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *
 */
class Auth extends Sessions
{

	const       LOCK_AFTER_FAILED_LOGIN = 7;
	const       DEFAULT_ALGORITHM = 'sha512';
	const       PASSWORD_MIN_LENGTH = 7;

	static $PASSWORD_MESSAGE = NULL;
	static $AuthDB = NULL;
	static $secretCookieWord = DESCRIPTION;


	/**
	 * @return bool
	 *
	 *   check if user is already logged on
	 */
	static public function isLoggedIN()
	{

		self::init();
		$ret = FALSE;
		if ( empty( $_SESSION['Auth']['User_Session_Id'] ) || empty( $_SESSION['Auth']['UserName'] ) ) {

			if ( self::rememberMyLoginAtSessionValidation() ) {
				//trigger_error(" ok validate by cookie");
			}

			return $ret;
		}
		//Single User only
		if ( $_SESSION['Auth']['User_Session_Id'] == session_id() && $_SESSION['Auth']['UserName'] != "" && $_SESSION['Auth']['User_Session_Id'] == self::get_info("session_id")
		) // Only 1 USer
			// Multiuser logging
			//	if ($_SESSION['Auth']['User_Session_Id']==session_id()  && $_SESSION['Auth']['UserName']!="")
		{
			$ret = TRUE;
		}

		return $ret;
	}


	/**
	 *
	 *   default initialization for auth
	 */
	static public function init()
	{

		if ( !Cookies::Exists('Auth_Tries') || !isset( $_SESSION['Auth_Tries'] )
		) { //  			 **** 	Πρώτη επίσκεψη / κλήση
			$php_key = 1;
			Cookies::Set('Auth_Tries', $php_key, Cookies::Session); //					Α)   Δημιουργία  cookies
			$_SESSION['Auth_Tries'] = $php_key;
		} else {
			$_SESSION['Auth_Tries'] = $_SESSION['Auth_Tries'] + 1;
		}

		if ( isset( $_POST['logout'] ) || isset( $_GET['logout'] ) ) {
			self::logout();

			return;
		}
	}


	/**
	 * @return bool
	 *
	 *   logout and clear $_SEESSION
	 */
	static public function logout()
	{
		$db = new MyDB();

		$username = $_SESSION['Auth']['UserName'];
		self::_set_sessions();

		unset( $_SESSION['Auth_Tries'] );
		unset( $_SESSION['Auth'] );

		Cookies::Delete('Auth_Tries');
		Cookies::Delete('rememberLogin');


		$db->where('login', $username);
		if ( !$db->update("Auth", array('session_id' => '')) ) {
			trigger_error("ERROR  logging off  $username ! " . $db->error());
		}

		return TRUE;
	}


	/**
	 * @param null $sessions
	 * @param null $username
	 * @param null $level
	 *
	 * Prevent's session fixation
	 * Set SEssions Variables
	 *
	 */
	static public function _set_sessions($sessions = NULL, $username = NULL, $level = NULL)
	{

		if ( $sessions ) {
			session_regenerate_id(TRUE); // Prevent's session fixation
		} else {
			$TMP = $_SESSION['PATHS'];

			session_regenerate_id(TRUE);
			$_SESSION['PATHS'] = $TMP;
		}

		$_SESSION['Auth'] = array("User_Session_Id" => $sessions, "UserName" => $username, "Level" => $level);

		return;
	}


	static public function get_level()
	{
		return isset( $_SESSION['Auth'] ) ? intval($_SESSION['Auth']['Level']) : NULL;
	}


	static public function get_info($field = "surname")
	{

		$ret = NULL;
		$db = new MyDB();

		$db->where('login', self::get_username());
		$results = $db->query(" SELECT * FROM  `Auth`");

		if ( $results ) {
			return $results[0][$field];
		}
	}


	static public function get_username()
	{
		return isset( $_SESSION['Auth'] ) ? $_SESSION['Auth']['UserName'] : NULL;
	}


	/**
	 * @param $pass
	 * @param $pass_aes_iv
	 * @param $pass_aes_key
	 *
	 *
	 *   Encrypted Password from Javascript via CryptoJS with AES
	 *
	 *
	 *
	 *  return decrypted AES 128 password
	 *
	 * @return string
	 *
	 */
	static public function Decrypt_from_JS($pass, $pass_aes_iv, $pass_aes_key)
	{
		$_ret = $pass;

		if ( $pass_aes_iv && $pass_aes_key
		) { // isset($_SESSION['_call_form'])) {
			$key = $_SESSION['_call_form'];

			require_once( 'Crypt.php' );

			$_ret = Crypt::AES_Decrypt($pass, $pass_aes_iv, $pass_aes_key);

			// trigger_error(__METHOD__ . " AES ijmpotere κανονικός κωδιός ==>  [$_ret] <==" . print_r($_ret, TRUE));
		}

		return ( $_ret );
	}

	/**
	 * @param        $password
	 * @param null   $salt
	 * @param string $algorythm
	 *
	 *
	 *  Return SHA1 salted Password
	 *
	 *          SHA1 Salted Password
	 *
	 * @return string
	 */
	static public function salt_password($password, $salt = NULL, $algorithm = self::DEFAULT_ALGORITHM)
	{
		// trigger_error(" SALTED      [$salt]  len= ".strlen($salt)." ± pass [$password] len= ".strlen($password)." ");
		$password = isset( $salt ) ? $salt . $password : $password;

		$salted_password = hash($algorithm, $password);

		// $salted_password =   $password;
		//  trigger_error(" SALTED password with salt [$salt] ± pass [$password] len= ".strlen($salted_password)." ± salted [$salted_password]");

		return $salted_password;
	}

	/**
	 * @return string
	 *
	 *   generate salt fro password
	 */
	static public function salt_password_generate()
	{
		return hash(self::DEFAULT_ALGORITHM, uniqid(openssl_random_pseudo_bytes(16), TRUE));
	}


	/**
	 * @param        $section
	 * @param        $message
	 * @param string $table
	 * @param null   $geolocation
	 *
	 * @return bool
	 *
	 *  log events
	 */
	static public function Logging($section, $message, $table = "Logging", $geolocation = NULL)
	{

		$db = new MyDB();
		$_ret = TRUE;

		$record = array();
		$record['section'] = $section;
		$record['ip'] = isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : getenv("REMOTE_ADDR");
		$record['browser'] = getenv("HTTP_USER_AGENT");
		$record['message'] = $message;
		$record['coordinates'] = $geolocation;

		if ( !$db->insert($table, $record) ) {
			$_ret = FALSE;
		}

		return $_ret;
	}


	/**
	 *   Κατώφλι
	 *   threshold for failed logins
	 */
	static public function validation_tried()
	{

		$_ret = TRUE;

		self::init();

		if ( isset( $_SESSION['Auth_Tries'] ) ) {
			if ( $_SESSION['Auth_Tries'] + 0 == self::LOCK_AFTER_FAILED_LOGIN ) {

				self::Logging('Authentication', 'Brute Force Attack, Locked Sessions too many tries');
				$_SESSION['Auth_Tries'] = $_SESSION['Auth_Tries'] + 1;

				//
				//           mail username for attacked account
				//
				$_ret = FALSE;
			} elseif ( $_SESSION['Auth_Tries'] + 0 > self::LOCK_AFTER_FAILED_LOGIN
			) {
				//
				//			 do nothing ?
				//
				$_ret = FALSE;
			}
		}

		return $_ret;

	}


	/**
	 * @param        $password
	 * @param string $password_verification
	 * @param null   $userid
	 *
	 *  Change user password
	 *
	 *
	 * @return bool
	 */
	public static function changepassword($password, $password_verification = "", $userid = NULL)
	{

		$ret = FALSE;

		if ( empty( $password_verification ) ) {
			return $ret;
		} else {
			if ( strcmp($password_verification, $password) != 0 ) {
				return $ret;
			}
		}

		$salt = self::salt_password_generate();
		$crypted_passwd = self::salt_password($password, $salt);

		$db = new MyDB();
		$rec = array();
		$rec['passwd'] = $crypted_passwd;
		$rec['salt'] = $salt;

		if ( $userid ) {
			$db->where('id', $userid);
		} else {
			$db->where('login', self::get_username());
		}


		if ( !$db->update("Auth", $rec) ) {
			$ret = FALSE;
		} else {
			$ret = TRUE;
		}

		$db->close();

		return $ret;
	}


	static public function getAuthDB()
	{

		if ( !self::$AuthDB ) {

			$db = new MyDB();

			$db->where('name', SQL_BDD)->where('trash', 0);
			$results = $db->query("SELECT id FROM  `AuthDB`");
			if ( !$results ) {
				self::$AuthDB = $db->insert('AuthDB', array('name' => SQL_BDD, 'description' => ' auto insert' . date('Y-m-d')));
			} else {
				self::$AuthDB = $results[0]['id'];
			}
		}

		return self::$AuthDB;
	}


	/**
	 * @param      $loginname
	 * @param      $pass
	 * @param null $pass_aes_iv
	 * @param null $pass_aes_key
	 *
	 *
	 *  Validate input login name and password ...
	 *
	 * @return bool
	 */
	static public function validation($loginname, $pass, $pass_aes_iv = NULL, $pass_aes_key = NULL)
	{

		$db = new MyDB();
		$_ret = TRUE;

		if ( !self::validation_tried() ) {
			return self::validation_tried();
		}

		$pass = self::Decrypt_from_JS($pass, $pass_aes_iv, $pass_aes_key);

		//     $db->where('email', $loginname);
		$db->where('login', $loginname);  // OLD School

		$results = $db->query("SELECT passwd,locked,ipallow,level,counter,id,salt FROM  `Auth`");


		$pass_enc = self::salt_password($pass, $results[0]['salt']);

		//       trigger_error(__METHOD__ . " Username/EMAIL  = $loginname  , Decrypted $pass  , Encrypted $pass_enc with salt ". $results[0]['salt']. " --> DB pass = ". $results[0]['passwd'] );


		if ( strcmp($results[0]['passwd'], $pass_enc) === 0
		) { //   Έγκυρη αυθεντικοποίηση  &&	  Επιπλέον ρυθμίσεις ασφαλείας

			if ( $results[0]['locked'] == 1 ) { // Ενεργός ?
				self::Logging('Authentication', " User " . $loginname . " has been LOCKED by administrator ! trying to connect ");
				$_ret = FALSE;
			}

			if ( $results[0]['ipallow'] != "" ) //  Μη έκγυρη  IP
			{
				if ( $results[0]['ipallow'] != getenv("REMOTE_ADDR") ) {
					self::Logging('Authentication', " User " . $loginname . " has logged in from disallowed IP address ! rejected ");

					$_ret = FALSE;
				}
			}

			// AKAMAI
			//self::_set_sessions(session_id($_COOKIE['PHPSESSID']), $loginname, $results[0]['level']);
			self::_set_sessions(TRUE, $loginname, $results[0]['level']);
			$cx = $results[0]['counter'] + 1;

			$rec = array();
			$rec['session_id'] = session_id();
			$rec['counter'] = $cx;
			$rec['AuthDB_id'] = self::getAuthDB();

			$db->where('login', $loginname);
			//  $db->where('email', $loginname);
			if ( !$db->update("Auth", $rec) ) {
				echo "ERROR Updating  User!! " . __FILE__;
			}

			$record = array();
			$record['Auth_id'] = $results[0]['id'];
			$record['IP'] = getenv("REMOTE_ADDR");
			$record['User_Agent'] = getenv("HTTP_USER_AGENT");
			$record['Geolocation'] = NULL;

			if ( !$db->insert("AuthLogging", $record) ) {
				trigger_error(" Auth Logging error " . __FILE__);
			}


			$db->close();

			$_SESSION['Auth_Tries'] = 1;

		} else { // Αποτυχία Πιστοποίησης

			unset( $_SESSION['Auth'] );
			if ( isset( $_SESSION['Auth_Tries'] ) ) {
				$_SESSION['Auth_Tries'] = $_SESSION['Auth_Tries'] + 1;
			} else {
				$_SESSION['Auth_Tries'] = 0;
			}

			self::Logging('Authentication', "FAILED, Tried [" . $_SESSION['Auth_Tries'] . "]  login [" . $loginname . "] passwd [" . $pass . "]");

			$_ret = FALSE;
		}

		return $_ret;
	}

	/**
	 * @param null $pass
	 *
	 *  Password unit test
	 *
	 * @return bool
	 */
	static function password_rules($pass = NULL)
	{

		$_ret = TRUE;

		$length_pass = ( strlen($pass) > self::PASSWORD_MIN_LENGTH ) ? TRUE : FALSE;
		$all_str_pass = ctype_alpha($pass);
		$all_num_pass = ctype_alnum($pass);
		$all_ctrl_pass = ctype_cntrl($pass);
		$all_dgts_pass = ctype_digit($pass);
		$all_punt_pass = ctype_punct($pass);
		$all_whitespaces_pass = ctype_space($pass);

		if ( $all_str_pass || $all_ctrl_pass || $all_dgts_pass || $all_num_pass || $all_punt_pass || $all_whitespaces_pass
		) {
			$_ret = FALSE;
			self::$PASSWORD_MESSAGE = " All Characters are the same category/case [ must be combination upper/lower and numbers ] !, please retry ..";
		}

		if ( !$length_pass ) {
			$_ret = FALSE;
			self::$PASSWORD_MESSAGE = " Password must be greater than " . self::PASSWORD_MIN_LENGTH;
		}

		return $_ret;
	}


	static function password_rules_text()
	{
		return self::$PASSWORD_MESSAGE;
	}

	/**
	 * @param $username
	 *
	 * @return bool
	 *
	 *    Check if Cookie exists end bypass validation
	 */
	static function rememberMyLoginAtSessionValidation()
	{

		$ret = FALSE;

		$check = Cookies::Get('rememberLogin');
		if ( $check ) {
			list( $login, $cookie_hash ) = explode(',', $check);
			if ( md5($login . self::$secretCookieWord) == $cookie_hash ) {
				trigger_error(" ok validate by cookie");

				$db = new MyDB();

				$results = $db->rawQuery("SELECT level,counter,id,salt FROM  `Auth` WHERE login=? AND locked=0", array($login));

				self::_set_sessions(TRUE, $login, $results[0]['level']);
				$cx = $results[0]['counter'] + 1;

				$rec = array();
				$rec['session_id'] = session_id();
				$rec['counter'] = $cx;

				$db->where('login', $login);
				if ( !$db->update("Auth", $rec) ) echo "ERROR Updating  User!! " . __FILE__;

				$db->close();
				header('Location: login.php');

				$ret = TRUE;
			}
		}


		return $ret;
	}

	/**
	 * @param $username
	 *
	 *   Remember my login at session
	 */
	static function rememberMyLoginAtSession($username, $remember = FALSE)
	{
		// is checked Remember chekcbox
		if ( $remember ) {
			Cookies::Delete('rememberLogin');
			Cookies::Set('rememberLogin', $username . ',' . md5($username . self::$secretCookieWord));
			// Cookies::Set('rememberLogin', $username . ',' . md5($username . self::$secretCookieWord), Cookies::OneDay);
		}
	}


}