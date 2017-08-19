<?php
    /**
     *  Copyright (c) 7/9/14 , dsphinx@plug.gr
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
     *  Created : 8:31 PM - 7/9/14
     *
     */


    // Generate static fields
    $htmlFormInput        = dbTable::generateFieldsForm($dbFields, $dbFieldsTable);
    $idOf                 = Controller::param_integer('id');
    $htmlFormInputDisplay = NULL;


    /*
     *   Patch For Linked Data - Translation
     */
    $patchLanguageID = Controller::param_integer('lang');

    if ($patchLanguageID) {
        echo Html::_info("Locales", " New word/sentence ");
        $patchLinkID    = Controller::param_integer('linkID');
        $patchLinkIDTag = Controller::param_integer('linkIDTag');

        if ($patchLinkID) {
            $idOf = Drugs::getTranslatedWordNew($patchLinkID, $patchLanguageID);
        } else {
            $idOf = Drugs::getTranslatedWordNew($patchLinkIDTag, $patchLanguageID, 1);
        }
    }
    /*
     *   END Patch For Linked Data - Translation
     */


    if (dbTable::isSubmitted()) {

        if (dbTable::isSubmittedToRemove()) {

            if (!dbTable::delete()) {
                $htmlFormInputDisplay = ' <li style="float: right;">  <div  class="label label-warning"  >  Record not Removed   </div>  </li>';
            } else {
                $htmlFormInputDisplay = ' <li style="float: right;">  <div  class="label label-info"  >  Record Removed   </div>  </li>';
            }

        } else {
            if (!dbTable::insert()) {
                $htmlFormInputDisplay = ' <li style="float: right;">  <div  class="label label-danger"  >  Insert Failed  : ' . dbTable::$log_messages . ' </div>  </li>';
            } else {
                $htmlFormInputDisplay = ' <li style="float: right;">  <div  class="label label-info"  >  Action Completed </div>  </li>';
            }
        }


    }

    if ($idOf) {
        $records = dbTable::load($idOf);
        if (!$records) {
            $htmlFormInputDisplay = ' <li style="float: right;">  <div  class="label label-info"  >  Record is removed </div>  </li>';
                Controller::refreshPage('id');
        }
        // regenerate form with values  --- cpu load ??
      //  Echoc::object($records);
        $htmlFormInput = dbTable::generateFieldsForm($dbFields, $dbFieldsTable, $records);
    }


    $htmlOutput = '<div class="panel panel-info">
                      <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                         <li class="active"><a href="#tableInfo" data-toggle="tab"><span class="glyphicon glyphicon-list"></span> ' . dbTable::$_db_table . '</a></li>
                    ' . $htmlFormInputDisplay . ' </ul>
                     <div id="my-tab-content" class="tab-content " style="min-height: 101px;  padding: 20px 20px 20px 20px;">
                         <div class="tab-pane active" id="tableInfo">
                           <div class="row">
                            <div class="col-md-1"> </div>
                               <div class="col-md-10">';

    echo $htmlOutput . $htmlFormInput;

    echo '</div></div></div></div> </div>';