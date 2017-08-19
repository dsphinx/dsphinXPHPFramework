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

   // DEFINE ("__AJAX", TRUE); // Without Sessions from configuration
    // ena off to __AJAX δεν θα έχει  username


    require_once ('../Library/Ajax/Ajax.php');

    function download_output_file($file, $_NAME, $mime_type = '')
    {
        /*
        This function takes a path to a file to output ($file),
        the filename that the browser will see ($_NAME) and
        the MIME type of the file ($mime_type, optional).

        If you want to do something on download abort/finish,
        register_shutdown_function('function_name');
        */
        if (!is_readable($file)) die('File not found or inaccessible!');

        $size  = filesize($file);
        $_NAME = rawurldecode($_NAME);

        /* Figure out the MIME type (if not specified) */
        $known_mime_types = array(
            "pdf"  => "application/pdf",
            "txt"  => "text/plain",
            "html" => "text/html",
            "htm"  => "text/html",
            "exe"  => "application/octet-stream",
            "zip"  => "application/zip",
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
        header('Content-Disposition: attachment; filename="' . $_NAME . '"');
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

        die();
    }


    /*
    Make sure script execution doesn't time out.
    Set maximum execution time in seconds (0 means no limit).
    */
    set_time_limit(0);
    $_PATH_DIRECTORY = isset($_GET['PATH_DIRECTORY']) ? $_GET['PATH_DIRECTORY'] : "Media/photos/";
    $_NAME           = isset($_GET['name']) ? $_GET['name'] : "no_image.png";
    $_USERNAME       = isset($_SESSION['Auth']) ? $_SESSION['Auth']['UserName'] : "";
    $_CLIENT         = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "localhost";
    $_STAMP          = isset($_GET['STAMP']) ? $_GET['STAMP'] : date("F j, Y");

    // From Ajax. php
    // TODO : logging donwloads ?
    // already $db = new MyDB();

    $_filename_to_download = $_SESSION['PATHS']['__ROOT__'] . $_PATH_DIRECTORY . $_NAME;


    $_filename = "arxeio eksodou  " . $_STAMP . " - " . $_NAME  . ".png";


    $record                = array();
    $record['section']     = "Downloads";
    $record['ip']          = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : getenv("REMOTE_ADDR");
    $record['browser']     = getenv("HTTP_USER_AGENT");
    $record['message']     = "User: $_USERNAME, file $_filename_to_download download as  $_filename";
    $record['coordinates'] = "";

    if (!$db->insert("Logging", $record))
        $_ret = FALSE;


    download_output_file($_filename_to_download, $_filename );

