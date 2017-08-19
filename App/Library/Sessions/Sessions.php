<?php

/**
 *
 * modified (c) dsphinx 2013
 *
 * @author http://stephenmcintyre.net/blog/custom-php-session-class
 *         Session Handling Functions using MySQL.
 *         simply include() this file at the top of any script you wish to use Sessions in.
 *         As long as the table exists in the database, all Sessions will be stored in that table.
 *         This file can be places onto multiple webservers running the same website and they will begin to share
 *         Sessions between them.
 *
 * TODO:  Secure Cookie , must be over https to prevent session hijack session.cookie_secure
 *
 *
 * Licensed under MIT licence:
 *   http://www.opensource.org/licenses/mit-license.php
 **/
class Sessions
{
	private $alive = FALSE;
	private $dbc = NULL;


	/**
	 *
	 * Trying to protect against well known attacks
	 *
	 */
	function securing()
	{
		// **PREVENTING SESSION HIJACKING**
		/// Prevents javascript XSS attacks aimed to steal the session ID
		ini_set('session.cookie_httponly', 1);

		// **PREVENTING SESSION FIXATION**
		// Session ID cannot be passed through URLs
		ini_set('session.use_only_cookies', 1);

		// Uses a secure connection (HTTPS) if possible
		if ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] == 'on' ) {
			ini_set('session.cookie_secure', 1);
		}
	}


	function __construct()
	{
		session_set_save_handler(array(&$this, 'open'), array(&$this, 'close'), array(&$this, 'read'), array(&$this, 'write'), array(&$this, 'destroy'), array(&$this, 'clean'));

		ini_set('session.gc_probability', 97);
		ini_set('session.gc_divisor', 100);
		ini_set('session.gc_maxlifetime', 604800);
		ini_set('session.hash_function', 1);
		ini_set('session.hash_bits_per_character', 5);
		ini_set("session.save_handler", "user");
		$this->securing();

		session_cache_limiter('nocache');
		session_start();
	}

	function __destruct()
	{
		if ( $this->alive ) {
			session_write_close();
			$this->alive = FALSE;
		}
	}

	function delete()
	{
		if ( ini_get('session.use_cookies') ) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
		}
		session_destroy();
		$this->alive = FALSE;
	}

	function open()
	{
		$this->dbc = new MYSQLi(SQL_HOST, SQL_USER, SQL_PASSWORD, SQL_BDD) OR die( 'Could not connect to database for Sessions handler.' );

		return TRUE;
	}

	function close()
	{
		return $this->dbc->close();
	}

	function read($sid)
	{
		$q = "SELECT `contents` FROM `AuthSessions` WHERE `id` = '" . $this->dbc->real_escape_string($sid) . "' LIMIT 1";
		$r = $this->dbc->query($q);

		if ( $r->num_rows == 1 ) {
			$fields = $r->fetch_assoc();

			return $fields['contents'];
		}

		return ''; // else
	}

	function write($sid, $data)
	{
		$q = "REPLACE INTO `AuthSessions` (`id`, `contents`) VALUES ('" . $this->dbc->real_escape_string($sid) . "', '" . $this->dbc->real_escape_string($data) . "')";
		$this->dbc->query($q);

		return $this->dbc->affected_rows;
	}



	function destroy($sid)
	{
		$q = "DELETE FROM `AuthSessions` WHERE `id` = '" . $this->dbc->real_escape_string($sid) . "'";
		$this->dbc->query($q);

		$_SESSION = array();

		return $this->dbc->affected_rows;
	}

	function clean($expire)
	{
		$sessionMaxLifetime = 1440;
		//	$q = "DELETE FROM `Sessions` WHERE DATE_ADD(`modify_date`, INTERVAL ".(int) $expire." SECOND) < NOW()";
		$q = 'DELETE FROM `AuthSessions` WHERE `modify_date` < DATE_SUB( NOW(), INTERVAL ' . $sessionMaxLifetime . ' SECOND )';
		$this->dbc->query($q);

		return $this->dbc->affected_rows;
	}


}


new Sessions();