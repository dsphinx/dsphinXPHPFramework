<?php

/**
 *  Copyright (c) 2014, dsphinx@plug.gr
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
class Policy extends Auth
{
	static $Auth_Level = NULL;

	static function init()
	{

		if ( isset( $_SESSION['Auth']['Level'] ) ) {
			self::$Auth_Level = Input::_int($_SESSION['Auth']['Level']);
		}
	}


	/**
	 * @param        $level_required { string or integer }
	 * @param string $title
	 * @param string $message
	 *
	 * @return bool
	 *
	 *
	 * integer:
	 * if (Controller::Policy(0, " Admin Pages")) {  return;  }
	 *
	 *
	 * string:
	 * if (Controller::Policy("sendmail", " Admin Pages")) {  return;  }
	 *
	 */

	static function Access($level_required, $title = "Security", $message = ' Not enough Privileges, to access !')
	{
		$_ret = FALSE;

		self::init();

		if ( is_numeric($level_required) ) {
			if ( $level_required < self::$Auth_Level ) {
				echo Html::_error_light($title, $message);
				self::Logging($title, "user " . $_SESSION['Auth']['UserName'] . "  $message");
				$_ret = TRUE;
			}
		}

		//echo __FILE__." $_ret  Required $level_required Has " . self::$Auth_Level;
		return $_ret;
	}


	/**
	 * @param        $level_required
	 * @param string $title
	 * @param string $message
	 *
	 * @return bool
	 *
	 *
	 *   return TRUE if is on showUsersPermissions or if is admin == 1
	 */
	static function AccessAction($level_required, $title = "Security", $message = ' Not enough Privileges, to access !')
	{
		$_ret = TRUE;

		self::init();

		// string
		$db = MyDB::db();
		$authDB = Auth::getAuthDB();
		$level_required = strtolower(trim($level_required));

		$sql = "SELECT * from showUsersPermissions where LOWER(Action)=? AND AuthDB_id=?";
		$tmp = $db->rawQuery($sql, array($level_required, $authDB));

		if ( isset( $tmp[0]['level'] ) ) {
			$newLevel_required = $tmp[0]['level'];

			//if ($newLevel_required  ) {
			// echo __FILE__ . " $level_required  Required $newLevel_required Has " . self::$Auth_Level;
			if ( $newLevel_required == self::$Auth_Level ||  self::$Auth_Level == 1) {
				$_ret = FALSE;
				//Echoc::object($tmp);
			}
		} else {
			$message .= " specific Action Not found !";
		}


		if ($_ret) {
			echo Html::_error_light($title, $message);
			self::Logging($title, "user " . $_SESSION['Auth']['UserName'] . "  $message");

		}

		//echo __FILE__." $_ret  Required $level_required Has " . self::$Auth_Level;
		return $_ret;
	}


}