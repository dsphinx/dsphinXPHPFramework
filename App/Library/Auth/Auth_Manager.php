<?php
/**
 *  Copyright (c) 2013, dsphinx@plug.gr
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
 *
 */


require_once( __DIR__ . '/Auth.php' );


class Auth_Manager extends Auth
{
	static $MAX_USER_LEVEL = 100;
	static $ADMIN_USER_LEVEL = 0;

	static $_rec = array(
		'id'      => NULL,
		'login'   => NULL,
		'surname' => NULL,
		'level'   => NULL,
		'email'   => NULL,
		'passwd'  => NULL,
		'ipallow' => NULL,
		'salt'    => NULL,
		'locked'  => 0,
		'message' => NULL,
	);


	/**
	 * @param $_posted
	 *        HTML input names must be the same with DB field to simplicity !
	 *
	 *
	 *  Insert new User
	 *
	 *
	 * @return bool
	 */
	static function insert($_posted)
	{
		$_ret = FALSE;
		$_tmp = self::$_rec;

		if ( self::existed_user($_posted['email']) ) {
			echo Html::_error_light("User Exists ", $_posted['email']);
		} else {

			while ( list( $key, $value ) = each($_tmp) ) {
				if ( isset( $_posted[$key] ) ) {
					$_tmp[$key] = $_posted[$key];
					//  echo " Found $key = $_tmp[$key] <br/>";
				}
			}

			$db = MyDB::db();

			if ( !$db->insert("Auth", $_tmp) )
				echo Html::_error_light("Error :  inserting  user ", $_tmp['email'] . " <br/> code :" . $db->error());
			else {
				$_ret = TRUE;
			}
		}


		return $_ret;
	}

	/**
	 * @param $email
	 *
	 * check if user exists
	 *
	 * @return bool
	 *
	 *
	 */
	static function existed_user($email)
	{
		$_ret = TRUE;

		$db = MyDB::db();

		$db->where('email', $email); // ->where('locked',0)->where('deleted',0);
		if ( !$db->query("SELECT * FROM Auth") ) {
			$_ret = FALSE;
		}

		return $_ret;
	}

	/**
	 * @param $_posted
	 *
	 *  Create simple USER with low  level priviledges
	 *
	 */
	static function insert_user($_posted)
	{
		$_tmp = self::$_rec;

		$_tmp['level'] = self::$MAX_USER_LEVEL;
		$_tmp['login'] = $_posted['email'];
		$_tmp['surname'] = $_posted['email'];
		$_tmp['email'] = $_posted['email'];
		$_tmp['message'] = 'Welcome user ';

		$_tmp['salt'] = self::salt_password_generate();
		$_tmp['passwd'] = self::salt_password($_posted['passwd'], $_tmp['salt']);

		// trigger_error($_tmp['passwd']);

		return ( self::insert($_tmp) );
	}


	static function list_user($sql = "SELECT * FROM Auth")
	{
		$db = MyDB::db();
		$ret = $db->sql($sql);

		return $ret;
	}

}