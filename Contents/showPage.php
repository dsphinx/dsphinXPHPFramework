<?php
    /**
     *  Copyright (c) 29/12/14 , dsphinx@plug.gr
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
     *  Created : 1:08 PM - 29/12/14
     *
     */


    /*
    *   Direct Call - Ajax
    *
    *    load Page into div.content , skip Framework controller
    *
    *
     *    ?showPage&article=main
     *
    */
    if (!defined("APP_Version")) {
        require_once ('../App/Library/Ajax/AjaxSkipFrameworkToDiv.php');
    }


    $articleDB        = Controller::$_page['article'];
    $articleDBSection = Controller::$_page['section'];
    $articleDbID      = Controller::param_integer('id');
    $htmlContent      = NULL;

    $db = MyDB::db();

    if (empty($articleDB)) {
        echo Html::_error("Article", " failed to retrieve web page, <br/> retry later ! ");
        return;
    }


    $db->where("name", $articleDB)->where("del", 0);
    $sqlCmd = 'SELECT id FROM Labels   ';
    $tmp    = $db->query($sqlCmd);


    if ($tmp) {

        $sqlCmd         = "SELECT Contents_id FROM Contents_has_Labels WHERE Labels_id=" . $tmp[0]['id'];
        $row_articles   = $db->query($sqlCmd);
        $articleDbID_is = $row_articles[0]['Contents_id'];

        if ($articleDbID_is) {

            $sqlCmd = "SELECT title,  content
                                    FROM Contents_lang, Contents
                                    WHERE Contents_lang.Contents_id=Contents.id AND Contents_id=$articleDbID_is AND
                                    Contents_lang.Languages_id=" . Controller::$_page_languageID . "  AND del=0  ";


            /*  multilingual support
             *                           fallback to english content
             */
            if (!$row = $db->query($sqlCmd)) {

                $sqlCmd = "SELECT title,  content
                                    FROM Contents_lang, Contents
                                    WHERE Contents_lang.Contents_id=Contents.id AND Contents_id=$articleDbID_is AND
                                    Contents_lang.Languages_id=1  AND del=0  ";
                $row    = $db->query($sqlCmd);
            }

            $htmlContent = $row[0]['content'];
        }

    }


    echo $htmlContent;