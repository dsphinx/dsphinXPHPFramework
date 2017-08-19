<?php
    /**
     *  Copyright (c) 3/10/14 , dsphinx@plug.gr
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
     *  Created : 9:35 PM - 3/10/14
     *
     */


    echo Html::javascript('jquery.dataTables.min.js');
    echo Html::javascript('dataTables.bootstrap.js');
    echo Html::javascript('dataTables.tableTools.min.js');
    echo Html::javascript('dataTables.editor.min.js');


    echo Html::css('dataTables.bootstrap.css');
    echo Html::css('dataTables.tableTools.css');
    echo Html::css('dataTables.editor.css');

    $tags = Controller::param("sended") ? "?send=true" : NULL;

         echo '<table cellpadding="0" cellspacing="0" border="0" class="table table-hover table-striped table-bordered" id="docs">
	<thead>
		<tr>
			<th> type </th>
			<th> date </th>
			<th> mailed to  </th>
			<th> diagnosis </th>
			<th> user </th>
 		</tr>
	</thead>
	<tbody>  <tr>
        <td colspan="4">loading ... </td>
      </tr>
     </tbody>
</table>
';



    echo '
	<script type="text/javascript" charset="utf-8">


	$(document).ready(function() {



				$("#docs").dataTable( {
                          "deferRender": true,
                           "serverSide": true,
                           "iDisplayLength": 25,
                           "ajax":  {
                                "url": "App/Ajax/listSendMail.php' . $tags . '",
                                "type": "POST",
                                 "data": function ( d ) {
                                        }
                                    },
                                            dom: \'T<"clear">lfrtip\',

                                            tableTools: {
                                                          "sSwfPath": "App/Javascript/swf/csv_xls_pdf.swf",
                                                           sRowSelect: "os",
                                                             aButtons: [
                                                                "copy",
                                                                "print",
                                                                {
                                                                    "sExtends":    "collection",
                                                                    "sButtonText": "Save",
                                                                    "aButtons":    [ "csv", "xls", "pdf" ]
                                                                },

                                                                                    ]
                                                             }

				 } );
			} );
		</script>';
