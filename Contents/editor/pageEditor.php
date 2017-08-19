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
     *  Created : 4:54 PM - 28/12/14
     *
     */


    parse_str(file_get_contents('php://input'), $_POST); // Bypass XSS

    $page = isset($_POST['editor']) ? $_POST['editor'] :  Controller::loadPageFromDB($pageIDEdit);

    ckEditor::runCode($page);


    // echo $page;

    echo '    <a href="?page=editor&test=test"> mine test </a> ';


    echo "Normal view <hr/> " . $_POST['editor'] ." <hr/>";
    echo "<br/> <pre> " .   strip_tags($_POST['editor'], '<img><a><br><hr><p><b><i>')." </pre>";

    // ckEditor::templateFull("test","aaa0");
    echo '<form method="post" action=""> <input type="hidden" name="form" value="1">

         ';

    ckEditor::templateMinimal("editor", $page);
    echo '<br/> <br/> <br/><input type="submit" name="submit" > </form>';






