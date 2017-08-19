<?php
/**
 *  Copyright (c) 2013, dsphinx@plug.gr
 *  All rights reserved.


http://getbootstrap.com/2.3.2/components.html#navbar
 *
 *
 *
 *
 *
 *
 *  
 */


?>


<a target="_blank" class="btn btn-lg btn-danger" href="?developer"> Show documentation για το Framework </a>
<a target="_blank" class="btn btn-lg btn-danger" href="Developer/Framework_UML/"> Show UML για το Framework </a>

<br />
<br />
<?php
echo '


<div id="infomodal" class="modal fade" tabindex="-1"  role="dialog" aria-labelledby="WarnAboutCookies" aria-hidden="false">>
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

<a class="btn btn-info" id=""doITClick">  modal </a>

<script type="application/javascript">

	var options = {
		"backdrop" : "true",
		"show" : "true"
	}

	$(document).ready(function () {

		$( "#agreeWithPolicy" ).click(function() {
				$("#infomodal").hide();
		});

 		$( "#doITClick" ).click(function() {
			$("#infomodal").modal(options);
		});

	});

</script>
';

?>

<br />
<br />
<br />
<br />
<h3 class="well well-sm"> Div sections </h3>
<div class="row">
    <div class="col-md-1 btn-info">.col-md-1</div>
    <div class="col-md-1">.col-md-1</div>
    <div class="col-md-1">.col-md-1</div>
    <div class="col-md-1">.col-md-1</div>
    <div class="col-md-1">.col-md-1</div>
    <div class="col-md-1">.col-md-1</div>
    <div class="col-md-1">.col-md-1</div>
    <div class="col-md-1">.col-md-1</div>
    <div class="col-md-1">.col-md-1</div>
    <div class="col-md-1">.col-md-1</div>
    <div class="col-md-1">.col-md-1</div>
    <div class="col-md-1">.col-md-1</div>
</div>
<div class="row">
    <div class="col-md-8 btn-info">.col-md-8</div>
    <div class="col-md-4">.col-md-4</div>
</div>
<div class="row">
    <div class="col-md-4 btn-info">.col-md-4</div>
    <div class="col-md-4 btn-warning">.col-md-4</div>
    <div class="col-md-4 btn-danger">.col-md-4</div>
</div>
<div class="row">
    <div class="col-md-6 btn-info">.col-md-6</div>
    <div class="col-md-6 btn-warning">.col-md-6</div>
</div>

<br/> <br/> <h3 class="well well-sm"> Buttons </h3>


<button type="button" class="btn btn-default">Default</button>

<!-- Provides extra visual weight and identifies the primary action in a set of buttons -->
<button type="button" class="btn btn-primary">Primary</button>

<!-- Indicates a successful or positive action -->
<button type="button" class="btn btn-success">Success</button>

<!-- Contextual button for informational alert messages -->
<button type="button" class="btn btn-info">Info</button>

<!-- Indicates caution should be taken with this action -->
<button type="button" class="btn btn-warning">Warning</button>

<!-- Indicates a dangerous or potentially negative action -->
<button type="button" class="btn btn-danger">Danger</button>

<!-- Deemphasize a button by making it look like a link while maintaining button behavior -->
<button type="button" class="btn btn-link">Link</button>
<br/>

<p>
    <button type="button" class="btn btn-primary btn-lg">Large button</button>
    <button type="button" class="btn btn-default btn-lg">Large button</button>
</p>
<p>
    <button type="button" class="btn btn-primary">Default button</button>
    <button type="button" class="btn btn-default">Default button</button>
</p>
<p>
    <button type="button" class="btn btn-primary btn-sm">Small button</button>
    <button type="button" class="btn btn-default btn-sm">Small button</button>
</p>
<p>
    <button type="button" class="btn btn-primary btn-xs">Extra small button</button>
    <button type="button" class="btn btn-default btn-xs">Extra small button</button>
</p>

<br/> <br/> <h3 class="well well-sm"> Modal , popup </h3>


<!-- Button trigger modal -->
<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
    Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                lalal
                <br>
                tria poulak i

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<button type="button" class="btn  popit btn-default" data-container="body" data-toggle="popover" data-placement="left" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
    Popover on left
</button>

<button type="button" class="popit btn btn-default" data-container="body" data-toggle="popover" data-placement="top" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
    Popover on top
</button>

<button type="button" class="popit btn btn-default" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Vivamus
sagittis lacus vel augue laoreet rutrum faucibus.">
    Popover on bottom
</button>
<br/><br/><br/>

<button type="button" class="popit btn btn-default" data-container="body" data-toggle="popover" data-placement="right" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
    Popover on right
</button>
<script>


    $('.popit').popover('toggle');
    $('.popit').popover('hide');


</script>
<br/><br/><br/>


<form class="form-inline" role="form">
    <div class="form-group has-success has-feedback">
        <label class="control-label" for="inputSuccess4">Input with success</label>
        <input type="text" class="form-control" id="inputSuccess4">
        <span class="glyphicon glyphicon-ok form-control-feedback"></span>
    </div>
</form>

<a class="btn btn-large btn-info" href="http://getbootstrap.com/components/"> BootStrap url here </a>
<br/>
<span class="label label-success">Success</span>
<span class="label label-default">Default</span>
<span class="label label-primary">Primary</span>
<span class="label label-success">Success</span>
<span class="label label-info">Info</span>
<span class="label label-warning">Warning</span>
<span class="label label-danger">Danger</span>
<a href="#">Inbox <span class="badge">42</span></a>
<br/><br/><br/>

<ul class="nav nav-tabs">
    <li class="active"><a href="#">Home</a></li>
    <li><a href="#">Profile</a></li>
    <li><a href="#">Messages</a></li>
</ul>

<br/><br/><br/>

<div class="well"> asdas as as</div>
<div class="well well-sm"> asd as as a</div>
<ul class="nav nav-pills">
    <li class="active"><a href="#">Home</a></li>
    <li><a href="#">Profile</a></li>
    <li><a href="#">Messages</a></li>
</ul>
<ul class="nav nav-tabs nav-justified">
    ...
</ul>
<ul class="nav nav-pills nav-justified">
    ...
</ul>

<br/>
<br/><br/><br/>


<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="glyphglyphicon-bar"></span>
                <span class="glyphglyphicon-bar"></span>
                <span class="glyphglyphicon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Brand</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Link</a></li>
                <li><a href="#">Link</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
            </ul>
            <form class="navbar-form navbar-left" role="search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#">Link</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<br/>
<ul class="nav nav-tabs">
    <li class="active"><a href="#">Home</a></li>
    <li><a href="#">Profile</a></li>
    <li><a href="#">Messages</a></li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            Dropdown <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li><a href="#">Profile</a></li>
            <li><a href="#">Messages</a></li>        </ul>
    </li>
    ...
</ul>

<p class="lead">

    Λεαδ ti είναι αυτο ?

</p>

<form class="navbar-form pull-left">
    <input type="text" class="span2">
    <button type="submit" class="btn btn-default">Submit</button>
</form>

<div class="alert">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>Warning!</strong> Best check yo self, you're not looking too good.
</div>


<a class="btn btn-mini" href=?page=signin&cmd=logout><i class="glyphglyphicon-star"></i> logout</a>

<p class="text-left">Left aligned text.</p>
<p class="text-center">Center aligned text.</p>
<p class="text-right">Right aligned text.</p>

<p class="text-muted">ssda </p>

<p>
    <small>This line of text is meant to be treated as fine print.</small>
</p>

<p class="text-danger">Fusce dapibus, tellus ac cursus commodo, tortor mauris nibh.</p>
<p class="text-success">Etiam porta sem malesuada magna mollis euismod.</p>
<p class="text-error">Donec ullamcorper nulla non metus auctor fringilla.</p>
<p class="text-info">Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis.</p>
<p class="text-success">Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
<abbr title="attribute">attr</abbr>
<abbr title="HyperText Markup Language" class="initialism">HTML</abbr>


<address>
    <strong>Twitter, Inc.</strong><br>
    795 Folsom Ave, Suite 600<br>
    San Francisco, CA 94107<br>
    <abbr title="Phone">P:</abbr> (123) 456-7890
</address>

<address>
    <strong>Full Name</strong><br>
    <a href="mailto:#">first.last@example.com</a>
</address>
<blockquote>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
</blockquote>


<blockquote>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
    <small>Someone famous <cite title="Source Title">Source Title</cite></small>
</blockquote>
<pre>
  &lt;p&gt;Sample text here...&lt;/p&gt;
</pre>
<table class="table table-hover">
    <tr class="success">
        <td>1</td>
        <td>TB - Monthly</td>
        <td>01/04/2012</td>
        <td>Approved</td>
    </tr>
    <tr class="info">
        <td>1</td>
        <td>TB - Monthly</td>
        <td>01/04/2012</td>
        <td>Approved</td>
    </tr>
    <tr class="warning">
        <td>1</td>
        <td>TB - Monthly</td>
        <td>01/04/2012</td>
        <td>Approved</td>
    </tr>
    <tr class="error">
        <td>1</td>
        <td>TB - Monthly</td>
        <td>01/04/2012</td>
        <td>Approved</td>
    </tr>

</table>



<form>
    <fieldset>
        <legend>Legend</legend>
        <label>Label name</label>
        <input type="text" placeholder="Type something…">
        <span class="help-block">Example block-level help text here.</span>
        <label class="checkbox">
            <input type="checkbox"> Check me out
        </label>
        <button type="submit" class="btn btn-default">Submit</button>
    </fieldset>
</form>
<form class="form-search">
    <input type="text" class="input-medium search-query">
    <button type="submit" class="btn btn-default">Search</button>
</form>
<form class="form-horizontal">
    <div class="control-group">
        <label class="control-label" for="inputEmail">Email</label>
        <div class="controls">
            <input type="text" id="inputEmail" placeholder="Email">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputPassword">Password</label>
        <div class="controls">
            <input type="password" id="inputPassword" placeholder="Password">
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <label class="checkbox">
                <input type="checkbox"> Remember me
            </label>
            <button type="submit" class="btn btn-default">Sign in</button>
        </div>
    </div>
</form>
<div class="input-group">
    <span class="input-group-addon">@</span>
    <input class="span2" id="prependedInput" type="text" placeholder="Username">
</div>
<div class="input-group">
    <input class="span2" id="appendedInput" type="text">
    <span class="input-group-addon">.00</span>
</div>


<div class="input-append">
    <input class="span2" id="appendedDropdownButton" type="text">
    <div class="btn-group">
        <button class="btn dropdown-toggle" data-toggle="dropdown">
            Action
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li>sss</li>
        </ul>
    </div>
</div>


    <input class="input-xs" type="text" placeholder=".input-mini">
    <input class="input-sm" type="text" placeholder=".input-small">
    <input class="input-lg" type="text" placeholder=".input-medium">
    <input class="input-xlarge" type="text" placeholder=".input-large">
    <input class="input-x" type="text" placeholder=".input-xlarge">
    <input class="input-xxlarge" type="text" placeholder=".input-xxlarge">


    <div class="control-group warning">
        <label class="control-label" for="inputWarning">Input with warning</label>
        <div class="controls">
            <input type="text" id="inputWarning">
            <span class="help-inline">Something may have gone wrong</span>
        </div>
    </div>

    <div class="control-group error">
        <label class="control-label" for="inputError">Input with error</label>
        <div class="controls">
            <input type="text" id="inputError">
            <span class="help-inline">Please correct the error</span>
        </div>
    </div>

    <div class="control-group info">
        <label class="control-label" for="inputInfo">Input with info</label>
        <div class="controls">
            <input type="text" id="inputInfo">
            <span class="help-inline">Username is already taken</span>
        </div>
    </div>

    <div class="control-group success">
        <label class="control-label" for="inputSuccess">Input with success</label>
        <div class="controls">
            <input type="text" id="inputSuccess">
            <span class="help-inline">Woohoo!</span>
        </div>
    </div>


    <a href="#" class="btn btn-large btn-primary disabled">Primary link</a>
    <a href="#" class="btn btn-large disabled">Link</a>
<button type="button" class="btn btn-large btn-primary disabled" disabled="disabled">Primary button</button>
<button type="button" class="btn btn-large" disabled>Button</button>
<a class="btn btn-default" href="">Link</a>
<button class="btn btn-default" type="submit">Button</button>
<input class="btn btn-default" type="button" value="Input">
<input class="btn btn-default" type="submit" value="Submit">
<p class="bg-primary">...</p>
<p class="bg-success">...</p>
<p class="bg-info">...</p>
<p class="bg-warning">...</p>
<p class="bg-danger">...</p>

<button type="button" class="btn btn-primary btn-lg btn-block">Block level button</button>
<button type="button" class="btn btn-default btn-lg btn-block">Block level button</button>

<img src="..." class="img-rounded">
<img src="..." class="img-circle">
<img src="..." class="glyphicon glyphicon-polaroid">
<i class="glyphicon glyphicon-search"></i>

<div class="progress">
    <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
        <span class="sr-only">60% Complete</span>
    </div>
</div>
<div class="progress">
    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
        <span class="sr-only">40% Complete (success)</span>
    </div>
</div>
<div class="progress">
    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
        <span class="sr-only">20% Complete</span>
    </div>
</div>
<div class="progress">
    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
        <span class="sr-only">60% Complete (warning)</span>
    </div>
</div>
<div class="progress">
    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
        <span class="sr-only">80% Complete</span>
    </div>
</div>
<div class="progress progress-striped active">
    <div class="progress-bar"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
        <span class="sr-only">45% Complete</span>
    </div>
</div>


<div class="progress">
    <div class="bar" style="width: 60%;"></div>
</div>
<div class="progress progress-striped active">
    <div class="bar" style="width: 40%;"></div>
</div>
<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
    <li><a tabindex="-1" href="#">Action</a></li>
    <li><a tabindex="-1" href="#">Another action</a></li>
    <li><a tabindex="-1" href="#">Something else here</a></li>
    <li class="divider"></li>
    <li><a tabindex="-1" href="#">Separated link</a></li>
</ul>
<div class="btn-group">
    <button class="btn btn-default">Left</button>
    <button class="btn btn-default">Middle</button>
    <button class="btn btn-default">Right</button>
</div>

<div class="btn-group">
    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
        Action
        <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
        <!-- dropdown menu links -->
    </ul>
</div>

<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
    <li><a tabindex="-1" href="#">Action</a></li>
    <li><a tabindex="-1" href="#">Another action</a></li>
    <li><a tabindex="-1" href="#">Something else here</a></li>
    <li class="divider"></li>
    <li><a tabindex="-1" href="#">Separated link</a></li>
</ul>
<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
    <li><a tabindex="-1" href="#">Regular link</a></li>
    <li class="disabled"><a tabindex="-1" href="#">Disabled link</a></li>
    <li><a tabindex="-1" href="#">Another link</a></li>
</ul>


<div class="container-fluid">
    <div class="row-fluid">
        <div class="span2">
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                <li><a tabindex="-1" href="#">Action</a></li>
                <li><a tabindex="-1" href="#">Another action</a></li>
                <li><a tabindex="-1" href="#">Something else here</a></li>
                <li class="divider"></li>
                <li><a tabindex="-1" href="#">Separated link</a></li>
            </ul>
        </div>
        <div class="span10">
            <div class="btn-group">
                <button class="btn btn-default">Left</button>
                <button class="btn btn-default">Middle</button>
                <button class="btn btn-default">Right</button>
            </div>
        </div>
    </div>
</div>


