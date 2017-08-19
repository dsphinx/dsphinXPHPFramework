<?php
    /**
     *  Copyright (c) 18/9/14 , dsphinx@plug.gr
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
     *  Created : 3:10 PM - 18/9/14
     *
     */


    require_once 'Unix/Unix.php';

    DEFINE ('UPLOAD', $_SESSION['PATHS']['__ROOT__'] . EXPORT_TEMPLATES_DIR);

    $insert = Controller::param("restoreDB");


    $files_not_scanned = array(".DS_Store" => 1 // Not Show this files
    , ".thumbs"                            => 2
    , ".AppleDouble"                       => 2
    , ".zip"                               => 3
    );


    $iterator = new DirectoryIterator(UPLOAD);
    $counter  = 0;

    foreach ($iterator as $fileinfo) {

        $fname          = $fileinfo->getFilename();
        $file_extension = substr(strrchr($fname, "."), 1);

        if ($fileinfo->getSize() > 0 && $file_extension == "docx" && $fileinfo->isFile() && $fileinfo->isReadable() && !array_key_exists($fname, $files_not_scanned)) {
            $counter++;
            //echo  "file " .  	$fname . " " . $fileinfo->getATime() . " 		$counter <br>";
            $files[$counter][1] = $fname;

            $lang        = "English";
            $lang_prefix = strtoupper(substr($fname, 0, 3));


            if ($lang_prefix == "GR_")
                $lang = "Ελληνικά";
            elseif ($lang_prefix == "DE_")
                $lang = "Deutsch";
            elseif ($lang_prefix == "JP_")
                $lang = "Japanese";


            $files[$counter][2] = $lang;
            $files[$counter][3] = Unixfile::show_human($fileinfo->getSize());
            $files[$counter][4] = date('Y M d,  H:i', $fileinfo->getCTime());
            $files[$counter][5] = '<a class="btn btn-info btn-xs"  href="Contents/download.php?exportTEMAPLATE=True&name=' . basename($fname) . '"> download </a>';


        }
    }

    $_show_files = array(
        "filename"                                       => 530,
        "language"                                       => 100,
        "size"                                           => 80,
        "date"                                           => 130,
        '<span class="glyphicon glyphicon-save"></span>' => 70,
    );

    echo Html::javascript('jquery.tablesorter.min.js');

    Html_Table::show($_show_files, $files, "100%", "   Templates ");
    echo Html_Table::init_tablesorter();

    echo Html::_info("Template Directory", " Manual upload new on path [ " . UPLOAD . " ] ");


