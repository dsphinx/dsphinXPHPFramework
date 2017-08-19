<?php
    /**
     *  Copyright (c) 28/12/14 , dsphinx@plug.gr
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
     *  DISCLAIMED. IN NO EVENT SHALL dsphinx BE LIABLE FOR ANY
     *  DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
     *  (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
     *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
     *  ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
     *  (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
     *  SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
     *
     *  Created : 7:52 PM - 28/12/14
     *
     */

    DEFINE ("__AJAX", TRUE);

    require_once (__DIR__.'/../Library/Ajax/Ajax.php');
    require_once (__DIR__. '/../Library/Cookies.php');


    function output_file($file, $name, $mime_type = "application/pdf")
    {


        $file = DOWNLOAD_PATH . $file;

       // trigger_error($file);

        if (!is_readable($file)) {
            //    echo Ajax_Call::redir_after_calls("?main");
            echo ("  Files - failed to retrieve  ".$name);
            die();
        }

        $size = filesize($file);
        $name = rawurldecode($name);

        /* Figure out the MIME type (if not specified) */
        $known_mime_types = array(
            "pdf"  => "application/pdf",
            "txt"  => "text/plain",
            "html" => "text/html",
            "htm"  => "text/html",
            "exe"  => "application/octet-stream",
            "zip"  => "application/zip",
            "gz"   => "application/gz",
            "doc"  => "application/msword",
            "docx" => "application/msword",
            "xls"  => "application/vnd.ms-excel",
            "ppt"  => "application/vnd.ms-powerpoint",
            "gif"  => "image/gif",
            "png"  => "image/png",
            "jpeg" => "image/jpg",
            "jpg"  => "image/jpg",
            "php"  => "text/plain"
        );

        if ($mime_type == '') {
            $file_extension = strtolower(substr(strrchr($file, "."), 1));
            if (array_key_exists($file_extension, $known_mime_types)) {
                $mime_type = $known_mime_types[$file_extension];
            } else {
                $mime_type = "application/force-download";
            }
            ;
        }
        ;

        @ob_end_clean(); //turn off output buffering to decrease cpu usage

        // required for IE, otherwise Content-Disposition may be ignored
        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: attachment; filename="' . $name . '"');
        header("Content-Transfer-Encoding: binary");
        header('Accept-Ranges: bytes');

        /* The three lines below basically make the
           download non-cacheable */
        header("Cache-control: private");
        header('Pragma: private');
        header("Expires: Mon, 26 Jul 2011 05:00:00 GMT");

        // multipart-download and download resuming support
        if (isset($_SERVER['HTTP_RANGE'])) {
            list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
            list($range) = explode(",", $range, 2);
            list($range, $range_end) = explode("-", $range);
            $range = intval($range);
            if (!$range_end) {
                $range_end = $size - 1;
            } else {
                $range_end = intval($range_end);
            }

            $new_length = $range_end - $range + 1;
            header("HTTP/1.1 206 Partial Content");
            header("Content-Length: $new_length");
            header("Content-Range: bytes $range-$range_end/$size");
        } else {
            $new_length = $size;
            header("Content-Length: " . $size);
        }

        /* output the file itself */
        $chunksize  = 1 * (1024 * 1024); //you may want to change this
        $bytes_send = 0;
        if ($file = fopen($file, 'r')) {
            if (isset($_SERVER['HTTP_RANGE']))
                fseek($file, $range);

            while (!feof($file) &&
                (!connection_aborted()) &&
                ($bytes_send < $new_length)
            ) {
                $buffer = fread($file, $chunksize);
                print($buffer); //echo($buffer); // is also possible
                flush();
                $bytes_send += strlen($buffer);
            }
            fclose($file);
        } else die('Error - can not open file.');

        return;
    }
    function output_file_log($downloadFilenameOut){
        Ajax_Call::Logger("file: ".$downloadFilenameOut,"download");
    }


    if ($results = file_get_contents("php://input")) {

        $results = $_POST;

        $downloadPath        = isset($results['path']) ? $results['path'] : "uploads/";
        $downloadFile        = isset($results['md5']) ? $results['md5'] : NULL;
        $downloadFilenameOut = isset($results['name']) ? $results['name'] : NULL;

        /*
         *  patch  old site
         * */
        DEFINE ("DOWNLOAD_PATH", __DIR__ . '/../../../../www/Media/' . $downloadPath);
      //  DEFINE ("DOWNLOAD_PATH", __DIR__ . '/../Media/' . $downloadPath);

       // trigger_error(DOWNLOAD_PATH);

        if (!$downloadFile || !$downloadFilenameOut) {
            echo "  Files - failed to retrieve ".$downloadFilenameOut;
            return;
        }

        //$downloads = Cookies::Get("LastDownload") ;
        $downloads = "$downloadFile";
        Cookies::Set("LastDownload", $downloads, Cookies::Lifetime);

        output_file($downloadFile, $downloadFilenameOut);

        output_file_log($downloadFilenameOut);

        return; // 1 - no output
    }