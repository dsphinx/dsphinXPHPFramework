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


    require_once ('Input_Global.php'); // SuperGlobal Hook
    require_once ('Input_Filter.php'); // Input XSS - Injection Protection


    class Input extends Input_Global
    {


        /**
         * @param $data
         *
         * @return $_POST[$name] or $_GET[$name] or $_SESSION[$name]
         */
        public static function In($data)
        {
            $ret = self::In_POST_GET($data);

            $ret = isset($ret) ? $ret : self::In_SESSION($data);

            return $ret;
        }

        /**
         * @param $name
         *
         * @return $_POST[$name] or $_GET[$name]
         */
        static function In_POST_GET($name)
        {
            return (isset($_POST[$name]) ? $_POST[$name] :
                (isset($_GET[$name]) ? $_GET[$name] : NULL));
        }

        static function In_SESSION($name)
        {
            return (isset($_SESSION[$name]) ? $_SESSION[$name] : NULL);
        }

        static function In_COOKIE($name)
        {
            return (isset($_COOKIE[$name]) ? $_COOKIE[$name] : NULL);
        }


        /**
         * @param $name
         *
         * $_GET a param , safe way
         *
         * @return null|string
         */

        static function In_GET_Url ( $name) {

            $ret = isset($_GET[$name]) ? $_GET[$name] : NULL;
            if ($ret) {
                $ret = self::safe($ret);

                $ret = trim(strtok($ret, " "));

            }



            return $ret;
        }

        static function safe($data, $encoding = 'UTF-8')
        {
            return htmlspecialchars($data, ENT_QUOTES, $encoding);
        }




        /**
         *        Integer
         */
        public static function _int($data)
        {
            $options = array(
                'options' => array(
                    'default' => NULL, // value to return if the filter fails
                    // other options here
                    // 'min_range' => 0
                ),
                //  'flags' => FILTER_FLAG_ALLOW_OCTAL,
            );
            $var     = filter_var($data, FILTER_VALIDATE_INT, $options);

            return $var;
        }

        static function positive($name)
        {
            $name = self::answer($name, TRUE);
            if ($name < 0)
                $name = 0;

            return $name;
        }

        static function number_decimal($value, $number = NULL)
        {
            $_ret = 0;
            $ret  = number_format($value, 2, '.', '');
            if ($number)
                $ret = number_format($ret, 2, ',', '');

            return $ret;
        }

        static function safe_sql($data, $link_mysli = NULL)
        {
            // return mysqli_real_escape_string($link_mysli, $data);
            $data = self::answer($data);


            return htmlentities($data, ENT_QUOTES, 'UTF-8');
        }

        static function web($data)
        {
            return self::answer_word($data);
        }

        static function answer_word($data)
        {
            $data = self::answer($data);
            if (stristr($data, ' '))
                $t = explode(" ", $data);
            elseif (stristr($data, '-'))
                $t = explode("-", $data);

            if (isset($t))
                $data = $t[0];

            return $data;
        }

        static function answer($name, $number = NULL, $is_SQL_query = FALSE)
        {

            $ret = self::classic_input($name);
            if (!$ret) {
                $ret = self::session_input($name) ? self::session_input($name) : self::cookie_input($name);
            }

            $ret = self::safe($ret);

            if ($is_SQL_query) { // mysqli link ?
                $ret = self::safe_mysqli($ret);
            }

            if ($number) {
                $ret = self::number($ret);
            }

            return $ret;
        }

        static function session_input($name)
        {
            return (isset($_SESSION[$name]) ? $_SESSION[$name] : NULL);
        }

        static function cookie_input($name)
        {
            return (isset($_COOKIE[$name]) ? $_COOKIE[$name] : NULL);
        }


        static function number($name)
        {
            $name += 0;
            settype($name, 'integer');

            return intval($name);
        }

        /*
         *   σε πειρπτώσεις που θέλω να αποθηκευση xml , script , xhtml etc
         */
        static function allow_XSS_strings($var) {
            parse_str( file_get_contents( 'php://input' ), $var );
            //            parse_str( file_get_contents( 'php://input' ), $_POST );
        }

        /**
         * @param        $date
         * @param string $format
         *
         * @return bool
         *
         *    return valid date
         */
        static function validateDate($date, $format = 'Y-m-d')
        {
            $d = DateTime::createFromFormat($format, $date);
            return $d && $d->format($format) == $date;
        }

    }


    /**
     *  initialise code
     *                            SUPER GLOBAL vars
     */
    $_REQUEST = new Input($_REQUEST, "_REQUEST");
    $_GET     = new Input($_GET, "_GET"); // Input XSS - Injection Protection
    $_POST    = new Input($_POST, "_POST"); // Input XSS - Injection Protection
// $_COOKIE 	= new Input_Global($_COOKIE, 	"_COOKIE");

