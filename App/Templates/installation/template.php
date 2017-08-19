<?php

$_load_controls=array('login'=>'',
                      'menus'=>'',
);

$_load_controls["login"]='<a class="btn btn-xs" target="_blank" href="Contents/showPDF.php?file=information.pdf&path=installation/">  Documentation </a><a class="btn btn-xs" target="_blank" href="Contents/showPDF.php?file=MVCStructure.pdf&path=installation/">  File Structure </a> <a class="btn btn-xs" href="installation.php">  Installation </a> ';



/**
 *
 *   Main Page Menus
 *
 */
$_tmp=array(//   'Αρχική'      => 'main',
            'Examples' =>'examplesCalls',
            'Dependencies' =>'this&section=installation_dependecies',
            'Database'     =>'this&section=installation_database',
            'Configuration'=>'this&section=installation_config',
);

$link=Controller::$_page['run'];

while ( list( $key, $value )=each($_tmp) ) {

	$_active=strcmp($link, $value) == 0 ? ' class="active" ' : NULL;
	$_load_controls["menus"].='<li ' . $_active . '><a  href="?page=' . $value . '" title="' . $key . '">' . $key . ' </a></li>';
}


$_load_controls_template=array('menus'=>$_load_controls["menus"],
                               'login'=>$_load_controls["login"],
);


return $_load_controls_template;