<?php
// 17-05-2012

    class myFiles
    {


        static $_download_script_file = "App/Library/file-functions.php";


//
//   Bumpbox for jquery to show pdf video etc
//
        static function Load_bumpbox_player()
        {


            if (isset($_SESSION['HTML5']) && $_SESSION['HTML5'] == true) // Speed
                echo '	<link rel="stylesheet" href="modules/BumpBox/media.css" />
		        <script  src="modules/BumpBox/bumpboxhtml5.js"></script> ';
            else
                echo '<link rel="stylesheet" href="modules/BumpBox/media.css" type="text/css">
		   	<script type="text/javascript" src="modules/BumpBox/bumpbox.js"></script>  ';
//
//			<!--		<script type="text/javascript" src="modules/Media/tools.flashembed-1.2.5.min.js"></script>
//					<script type="text/javascript" src="modules/Media/swfobject.js"></script>
//			-->		<script type="text/javascript" src="modules/Media/flowplayer-3.2.6.min.js"></script>


            // Bumpbox 2.1 jquery player


//	If you intent to view FLV and SWF files, add the following line:

//	<script type="text/javascript" src="js/jwplayer.min.js"></script>

        }


//
//  εμφανίζει σωστά την ημερομηνία με css
//
        static function display_date_timestamp_nice($timestamp)
        {

//	$date_info =   substr( $timestamp,0,10);
            $m = date("F");
            echo $m;


        }


//
//   force download a file
//
        static function output_file($file, $name, $mime_type = '')
        {

            if (!is_readable($file)) die('File not found or inaccessible!');

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
            die();
        }


//
//  επιστρέφη link για προβολή για bumpbox
//
        static function get_link_to_file($file_encode = "", $filenameoutput = "", $author_name = "", $title = "")
        {

            $known_mime_types = array(
                "pdf"  => "img/pdf.png",
                "doc"  => "img/word.png",
                "docx" => "img/word.png",
            );


            $uploadpath = "Media/uploads/";

            $file_extension = strtolower(substr(strrchr($file_encode, "."), 1));
            if (array_key_exists($file_extension, $known_mime_types)) {
                $mime_type = $known_mime_types[$file_extension];
                // με εμφάνιση bump box
                /*
                            Oλα τα pdf να ανοιγουν σε νεο tab


                            $return_Str = "	<a class=\"text_file_links bumpbox\"   title=\"$title\"  rel=\"966-400\" href=\"$uploadpath$file_encode\">$title</a><p class=\"text_author\"> $author_name </p> ";
                */

                $uploadpath = 'tools/download.php?file=' . $file_encode . "&out=" . $file_encode . "&old=";


                $return_Str = "	<a class=\"text_file_links\"  target=\"_blank\"  title=\"$title\"  rel=\"966-400\" href=\"$uploadpath$file_encode\">$title</a><p class=\"text_author\"> $author_name </p> ";

            } else {
                $return_Str = "	<a class=\"text_file_links bumpbox\"   title=\"$filenameoutput\"  rel=\"600-300\" href=\"$uploadpath$file_encode\">$filenameoutput</a><p class=\"text_author\"> $author_name</p> ";

            }

            return $return_Str;
        }

//
//  φορτώνει εικονα και link  gia download mono PDF
//
//   myFiles::get_img_link_to_file( $encod e-filename-in-uploas, $onomae-giadownload) 
//	
        static function get_img_link_to_file($file_encode = "", $filenameoutput = "", $author_name = "")
        {

            $known_mime_types = array(
                "pdf"  => "img/pdf.png",
                "doc"  => "img/word.png",
                "docx" => "img/word.png",
            );

            if ($author_name != "")
                $return_Str = '<p class="text_author">' . $author_name . '</p>';

            $file_extension = strtolower(substr(strrchr($file_encode, "."), 1));
            if (array_key_exists($file_extension, $known_mime_types)) {
                $mime_type = $known_mime_types[$file_extension];
                 $return_Str = '<a href="tools/download.php?file=' . $file_encode . '&out=' . $filenameoutput . '"><img class="imagebox" height="22" src="' . $mime_type . '"></a>';
            } else {
                $mime_type  = "img/media.png";
                $uploadpath = "Media/uploads/";

                $return_Str = '<a class="imagebox bumpbox"  title="  media"  rel="600-300"  href="' . $uploadpath . $file_encode . '"><img  height="22" src="' . $mime_type . '"></a>';


            }

            return $return_Str;
        }
    } // Class END


//
//   Forced download
//
    if (isset($_GET['file_enc'])) { //  κλήση για forced download

        set_time_limit(0);
        $path_where_is_uploas = "../../Media/uploads/";
        $name                 = $_GET['file_enc'];
        $out                  = $_GET['normal'];
        myFiles::output_file($path_where_is_uploas . $name, $out);

    }


?>