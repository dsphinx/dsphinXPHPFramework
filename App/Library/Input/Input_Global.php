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


    class Input_Global IMPLEMENTS ArrayAccess, Countable
    {


        var $__vars = array(); // previous superglobal array
        var $__filter = array(); // filterchain method stack
        var $__always = array();
        protected $__title;

        /**
         * Initialize object from
         *
         * @param array  one of $_REQUEST, $_GET or $_POST etc.
         */
        function __construct ($_INPUT, $title)
        {
            $this->__vars  = $_INPUT; // stripslashes on magic_quotes_gpc might go here, but we have no word if we actually receive a superglobal or if it wasn't already corrected
            $this->__title = $title;
        }

        /**
         *     Fancy  Output
         */
        public static function dump ($object = NULL, $return_data = FALSE)
        {

            $data_input     = self::objectToArray ($object);
            $data_input_var = $data_input[ '__vars' ];
            $data           = "<h4> dump   $" . $data_input[ '__title' ];
            $data .= "</h4>--------------------------------------------------<br/>";
            $data .= print_r ($data_input_var, TRUE);
            $data = str_replace (" ", "&nbsp;", $data);
            $data = str_replace ("\r\n", "<br>\r\n", $data);
            $data = str_replace ("\r", "<br>\r", $data);
            $data = str_replace ("\n", "<br>\n", $data);
            $data .= "-----------------------";

            if (! $return_data)
                echo $data;

            return $data;
        }

        /**
         *    Object to Array
         */
        public static function objectToArray ($d)
        {
            if (is_object ($d)) {
                $d = get_object_vars ($d);
            }

            if (is_array ($d)) {
                return array_map (__METHOD__, $d);
            } else {
                return $d;
            }
        }

        /**
         * Array[name] access.
         *
         */
        function offsetGet ($varname)
        {
            // never chains
            return $this->filter ($varname);
        }

        /**
         *     Filter Input
         */
        function filter ($data)
        {


            $data = isset( $this->__vars[ $data ] ) ? $this->__vars[ $data ] : NULL;

            if (preg_match ("/[<&>]/", $data)) { // looks remotely like html  -- XSS ?
                $data = $this->_xss ($data);
            }

            return $data;
        }

        /**
         *        Prevent from XSS attack
         */
        public static function _xss ($data)
        {
            trigger_error(" Possible XSS Attack [$data]");

            require_once( 'Input_Filter.php' );

            $myFilter = new Input_Filter();
            // process input
            $result = $myFilter->process ($data);
            // script timer stop
            return htmlentities($result , ENT_QUOTES, 'UTF-8' );
        }

        /**
         * Needed for commonplace isset($_POST["var"]) checks.
         *
         */
        function offsetExists ($name)
        {
            return isset( $this->__vars[ $name ] );
        }

        /**
         * Stubs to satisfy arrayaccess interface.
         *
         */
        function offsetSet ($name, $value)
        {
        }

        function offsetUnset ($name)
        {
        }

        /**
         * Allows testing variable presence with e.g. if ( $_POST() )
         *
         */
        function __invoke ()
        {
            return $this->count ();
        }

        /**
         * Countable
         *
         */
        function count ()
        {
            return count ($this->__vars);
        }
    }