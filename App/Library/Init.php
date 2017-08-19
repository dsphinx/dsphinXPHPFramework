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
     *      This product includes software developed by the dsphinx@plug.gr.
     *   4. Neither the name of the dsphinx@plug.gr nor the
     *      names of its contributors may be used to endorse or promote products
     *     derived from this software without specific prior written permission.
     *
     *  THIS SOFTWARE IS PROVIDED BY dsphinx@plug.gr ''AS IS'' AND ANY
     *  EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
     *  WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
     *  DISCLAIMED. IN NO EVENT SHALL dsphinx@plug.gr BE LIABLE FOR ANY
     *  DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
     *  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
     *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
     *  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
     *  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
     *  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
     *
     *
     */

class Init
{

    /**
     *
     *
     */
    static public function _add_include_path($path)
    {

        foreach (func_get_args() AS $path) {
            if (!file_exists($path) OR (file_exists($path) && filetype($path) !== 'dir')) {
                trigger_error("Include path '{$path}' not exists", E_USER_WARNING);
                continue;
            }

            $paths = explode(PATH_SEPARATOR, get_include_path());

            if (array_search($path, $paths) === FALSE)
                array_push($paths, $path);

            set_include_path(implode(PATH_SEPARATOR, $paths));
        }
    }




    //  Συνάρτηση  για _load
    //				 φορτώνει με require_once τα αρχεία .php,inc
    //			     που βρίσκονται στο ./ , 	LIB_PATH,    HTML_PATH
    //
    // @param      <filename without extension >
    // @return   require_once filename.ext
    static public function _load($className, $extList = '.php,.inc,.htm,.html,.txt')
    {

        /**
         *    Directory Structure
         *
         *  eg      Mysql/Mysql.php
         */
        if (is_dir($_SESSION['PATHS']['LIBRARIES'] . $className)) {
            require_once($_SESSION['PATHS']['LIBRARIES'] . $className . "/$className.php");

            return TRUE;
        }

        $ext = explode(',', $extList);

        foreach ($ext as $x) {
            $fname = $className . $x;

            if (@file_exists($_SESSION['PATHS']['LIBRARIES'] . $fname)) { // depending  LIBRARIES include paths
                require_once($fname);

                return TRUE;
            }

            if (@file_exists($fname)) { // depending  on . current dir
                require_once($fname);

                return TRUE;
            }
        }


        return FALSE;
    }


    static public function _libraries()
    {

        self::_load('Input');       //  HTML  input
        self::_load('Echoc');       //  HTML  output  , scramble , metamorphic  ?


        self::_load('Html');        //  HTML  βιβλιοθήκη
        self::_load('Template');    //  HTML Templates
        self::_load('Semantic');    //  WEB Semantic - for ML



        self::_load('Mysql'); //  Mysql  βιβλιοθήκη
        //self::_load('Logger');		    	//  loggin


        self::_load('Timer'); //  Timer
        self::_load('Cookies'); //  Cookies  -- No Input XSS

        self::_load('Controller_files'); //  Minimal MVC implementation
        self::_load('Controller'); //  Minimal MVC implementation
        self::_load('Logger'); //   Logger Class

    }


    static public function _librariesAjax()
    {
        // Already in Ajax self::_load('Mysql'); //  Mysql  βιβλιοθήκη
        self::_load('Cookies');

    }

}