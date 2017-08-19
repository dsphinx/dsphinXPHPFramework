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
class AppMessages
{

	// Mysql DB Land id
	static $languageDBId=1;
	static $localesPO=array();

	// Exporting Template prefix
	static $languageTemplate_Prefix=NULL;

	static function message($index_no)
	{
		return self::Show($index_no, FALSE);
	}


	static function getTranslation()
	{

		if ( !self::$localesPO ) {
			if ( file_exists($_SESSION['LANGUAGE_INFO']) ) {
				include( $_SESSION['LANGUAGE_INFO'] );
				self::$languageTemplate_Prefix=$__lang_tempalte_prefix;
				self::$languageDBId           =$__lang_id;
				//   trigger_error("Sss");
			} else {
				include( 'App/Locales/EN.site.php' );
			}
			self::$localesPO=$tempLocale;
			//  Echoc::object(self::$localesPO);
		}

		return self::$localesPO;
	}

	/**
	 *
	 *  Locale message display
	 *
	 * @param string $index_no
	 *
	 * @return mixed
	 */
	static function Show($index_no, $printIT=TRUE)
	{

		$tempLocale=self::getTranslation();


		$_messages_are=array('default'         =>' error ! ',
		                     'Cookies_login'   =>' Tried to Access Members Area, without login ! Fall back ',
		                     'Register_subject'=>'Registration of  ' . TITLE,
		                     'Password_changed'=>'Your Password is changed',
		                     'PageNotfound'    =>' oopss ... <br/> url is broken  !!!  <br/> <br/> <p><a onclick="window.history.back()"  class="btn btn-danger btn-sm " role="button">&laquo; επιστροφή </a></p>',
		                     'nohtml5browser'  =>' Your browser is not <a href="http://en.wikipedia.org/wiki/HTML5">HTML5 Compatible</a>  and you may not see correctly this web site ! You must upgrade your browser',

		                     'blog'            =>'Blog',
		                     'event'           =>'Events',
		                     'testimonial'     =>'Testimonials',
		                     'warnCookie'      =>'Web site uses cookies to store information on your computer. Some are essential to make our Site work; others help us improve the user experience.
                By using this Site, you consent to the placement of these cookies.'
		);


		$ret=( isset( $_messages_are[$index_no] ) ) ? $_messages_are[$index_no] : $tempLocale[$index_no];


		if ( $printIT ) {
			echo $ret;
		}

		return $ret;
	}


	static function getMenusPatients()
	{
		$ret=array();

		$tempLocale=self::getTranslation();



		$ret['tests']='<li class="btn btn-sm  dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="#"> ' . $tempLocale[55] . '  <span class="caret"></span> </a>
                                <ul class="dropdown-menu text-left">
                                    <li><a href="' . '?tests&cmd=add' . '"> <span class="glyphicon glyphicon-plus"> ' . $tempLocale[55] . '  </span> </a></li>
                                    <li><a href="' . '?tests' . '"><span class="glyphicon glyphicon-list-alt"> ' . $tempLocale[55] . ' </span></a></li>
                                                                        <li><a href="' . '?tests&expired=yes' . '"><span class="glyphicon glyphicon-list-alt"> Expired ' . $tempLocale[55] . ' </span></a></li>
                          <li><a href="' . '?tests&pending=yes' . '"><span class="glyphicon glyphicon-list-alt"> Pending ' . $tempLocale[55] . ' </span></a></li>

                                                                                      <li class="divider"></li>
                                    <li><a href="' . '?tests&cmd=statistic' . '"><span class="glyphicon glyphicon-hand-right">  ' . $tempLocale[1][2] . " " . $tempLocale[55] . ' </span></a></li>

                                </ul>
                            </li>';




		return $ret;
	}


	static function getMenus($menuIndex=0)
	{
		$__lang=array();

		$tempLocale=self::getTranslation();

		$__lang[0]=array($tempLocale[0][0]=>'?main',
		                 $tempLocale[0][1]=>'?aboutus',
		                 $tempLocale[0][2]=>'?expertise',
 		);


		$__lang[1]=array();

		//
		// Admin menus


		return $__lang[$menuIndex];
	}


	static function getUserMenus($menuIndex=0)
	{
		$__lang=array();

		$tempLocale=self::getTranslation();


		$__lang[0]=array($tempLocale[0][0]=>'?page=main',

		);


		$__lang[1]=array($tempLocale[1][3] =>'?test&cmd=all',
		                 $tempLocale[1][4] =>'?none',


		);


		return $__lang[$menuIndex];
	}

}