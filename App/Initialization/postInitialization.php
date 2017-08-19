<?php
/**
 *  Copyright (c) 27/12/14 , dsphinx@plug.gr
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
 *  Created : 6:53 PM - 27/12/14
 *
 */


/**
 *   Cookie :  WarnAboutCookies
 *
 *   bootstrap :  <div class="modal fade">
 *
 *  use cookieManager (send cookies via ajax ) within JS , because we already send the headers
 *
 *
 * show.bs.modal: fired just before the modal is open.
 * shown.bs.modal: fired after the modal is shown.
 * hide.bs.modal: fired just before the modal is hidden.
 * hidden.bs.modal: fired after the modal is closed.
 * loaded.bs.modal: fired when remote content is successfully loaded in the modal’s content area using the remote
 * option mentioned above.
 *
 *  CSS:  modal fix z-index
 */
if ( !Cookies::Get('WarnAboutCookies') ) {

echo '


	<div id="WarnAboutCookies" class="modal fade" tabindex="-1"  role="dialog" aria-labelledby="WarnAboutCookies" aria-hidden="false">>
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
					<h4 id="modal-label"><span class="glyphicon glyphicon-info-sign"></span> Cookies Policy</h4>
				</div>
				<div class="modal-body"> ' . AppMessages::Show("warnCookie", FALSE) . '
				</div>
				<div class="modal-footer">
		            <button id="agreeWithPolicy"  class="btn btn-success">I Agree , don\'t show it again !</button>
				</div>
			</div>
		</div>
	</div>

	<script type="application/javascript">

		var options = {
			"backdrop" : "true",
			"show" : "true"
		}

		$(document).ready(function () {

			$("#WarnAboutCookies").modal(options);

			$( "#agreeWithPolicy" ).click(function() {

				cookieManager("WarnAboutCookies","1","31536000");
				$("#WarnAboutCookies").hide();

			});

		});

	</script>
';


}






