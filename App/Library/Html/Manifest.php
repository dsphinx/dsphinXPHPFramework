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

    class Manifest
    {
        const  HTML_EOL = "\n";
        const  FALLBACK = "offline.html";

        /**
         * @param null $cached_files
         *
         * HTML5 Manifest file
         *
         * @return string
         */
        public function load($cached_files = NULL)
        {
            // header = 'header("Content-Type: text/cache-manifest");';
            $ret = 'CACHE MANIFEST' . self::HTML_EOL;
            $ret .= '# version 7' . self::HTML_EOL;
            $ret .= self::HTML_EOL . 'CACHE:' . self::HTML_EOL;
            if (is_array($cached_files)) {
                foreach ($cached_files as $cache) {
                    $ret .= $cache . self::HTML_EOL;
                }
            }
            $ret .= self::HTML_EOL . 'NETWORK:' . self::HTML_EOL;
            $ret .= self::HTML_EOL . 'FALLBACK:' . self::HTML_EOL;
            $ret .= self::FALLBACK .  self::HTML_EOL;

            return $ret;
        }
    }