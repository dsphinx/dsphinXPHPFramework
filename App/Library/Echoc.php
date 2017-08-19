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


    /**
     *  Namespace ${NAMESPACE}
     *  Class Echoc
     *
     *  Responsible to output HTML / Javascript on Client Side
     *
     *
     *  TODO :  metamorphism technique ?
     *
     */
    class Echoc extends Exception
    {
        const     HTML_SCRAMBLER = FALSE; // Scramble HTML code ?
        static $_EOL = "\n";
        static $_EOL_replace = " ";

        /**
         * @param $text
         *
         *  Return  html p tag
         */
        public static function  p($text)
        {
            self::output('<p>' . $text . '</p>');
        }

        public static function __callStatic($name, $arguments)
        {
            // Note: value of $name is case sensitive.
            self::output('<!-- Static Call --> ' . $name . implode(', ', $arguments), '<!-- EOF Static Call --> ');
        }

        /**
         * @param $text
         *
         *  Return Readable or not HTML output
         */
        public static function  output($text)
        {
            if (self::scrable_it()) {
                $text = preg_replace("/\n/", self::$_EOL_replace, $text); // $text = preg_replace("/\n|/\r/"," ",$text);
                $text = trim($text);
            } else
                $text .= self::$_EOL;

            echo ($text);
        }


        /**
         *   Scramble output if  config HTML_READABLE is set TRUE or HTML_SCRAMBLER
         *
         * @return bool
         */
        public static function  scrable_it()
        {
            $_ret = (defined('HTML_READABLE') && (HTML_READABLE == TRUE)) ? TRUE : FALSE;

            return ($_ret || self::HTML_SCRAMBLER);
        }

        /**
         *
         *  return metamorphism HTML data
         */
        public static function metamorphism()
        {
            $_ret = NULL;

        }

        /**
         * @param      $data
         * @param bool $return_data
         *
         *   Fancy Output of arrays , objects
         *      bracket with red color
         *
         * @return mixed
         */
        public static function object($data, $return_data = FALSE)
        {
            $data = print_r($data, TRUE);
            $data = str_replace(" ", "&nbsp;", $data);
            $data = str_replace("\r\n", "<br>\r\n", $data);
            $data = str_replace("\r", "<br>\r", $data);
            $data = str_replace("\n", "<br>\n", $data);

            $data= str_replace("[","<b style=\"color:red;\">[ </b>",$data);
            $data= str_replace("]","<b style=\"color:red;\"> ]</b>",$data);

            if (!$return_data)
                echo $data;
            else
                return $data;
        }

    }