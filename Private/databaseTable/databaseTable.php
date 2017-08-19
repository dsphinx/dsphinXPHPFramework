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


    /*
     *   Απαιτείται σύνδεση επιπέδου 100, διαφορετικά δείξε μήνυμα
     */
    if (Controller::Policy(100, " database Table Page ")) {
        echo Html::_info("dbTables", " You must logged in , before you proceed in to  this page ");
        return;
    }


    // Απαραίτητες Βιβλιοθήκες ...
    require_once('Private/Classes/templateClassDB.php');
    require_once('Private/Classes/dbTable.php');
    require_once('Private/Classes/values.arrays.php');

    echo Html::javascript('jquery-ui.min.js'); //  Password not plain text
    echo '	<link rel="stylesheet" href="Media/images/chosen/chosen.css" />';
    echo Html::css('jquery-ui.min.css');

    echo  '<script  src="' . $_SESSION['PATHS']['FILES']['MEMBERS'] . Controller::$_page['run'] . "/" . Controller::$_page['run'] . '.js"></script>
    <script>   $(document).ready(function () {  $(".chzn-select").chosen();       });    </script>    ';


    $cmd = Controller::param("action");

    $_tmp = NULL;

    switch ($cmd) {

        case "courier":
            $_tmp = 'courier.php';
            break;
        case "locales":
            $_tmp = 'locales.php';
            break;
        case "localesTags":
            $_tmp = 'localesTags.php';
            break;

        default:
            $_tmp = 'donothing';
            break;
    }


    $filenameOfTest = __DIR__ . DIRECTORY_SEPARATOR . 'structure' . DIRECTORY_SEPARATOR . $_tmp;
    if (file_exists($filenameOfTest)) {
        include ($filenameOfTest);

        include ('databaseTableCreateForm.php');

    } else {
        trigger_error($filenameOfTest . " not found file " . __FILE__);
    }






