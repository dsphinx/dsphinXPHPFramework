<?php

// Template controls
$_load_controls=array("menus"   =>NULL,
                      "controls"=>NULL,
                      "langs"   =>NULL,
                      "privacy" =>NULL,
                      "social"  =>NULL,
);


$_load_controls["login"]=isset( $_SESSION['Auth'] ) ? '<a class="btn btn-xs" href="?page=signin&cmd=logout"><i class="icon-star"></i> logout</a>' : '<a class="btn btn-xs" href="?page=signup"><i class="glyphicon glyphicon-cog"></i> Εγγραφή </a><a class="btn btn-xs" href="?page=signin"><i class="glyphicon glyphicon-user"></i> Σύνδεση </a>';

/**
 *
 *   Main Page Menus
 *
 */
$_tmp=array(//   'Αρχική'      => 'main',
            'Examples'   =>'examplesCalls',
            'DB'         =>'DBDeveloper',
            'Sessions'   =>'sessions',
            'Grep'       =>'grep',
            'debugDB'    =>'debug',
            'Responsive '=>'responsive',
            'Βοήθεια'    =>'html_BOOTSTRAP_examples',
            'Plugins'    =>'plugins',
            'Themes'     =>'showThemes',
);

$link=Controller::$_page['run'];

while ( list( $key, $value )=each($_tmp) ) {

	$_active=strcmp($link, $value) == 0 ? ' class="active" ' : NULL;
	$_load_controls["menus"].='<li ' . $_active . '><a  href="?page=' . $value . '" title="' . $key . '">' . $key . ' </a></li>';
}


/*
$_langs = array(
	'English'  => 'cy-GB.gif',
	'Ελληνικά' => 'el-GR.gif',
);


while (list($key, $value) = each($_langs))
	$_load_controls["langs"] .= '<a   href="?page=lang&set=' . $key . '"><span class="Language selected" title="' . $key . '" style="background-image: url(Media/images/' . $value . ')"> &nbsp;  </span></a>';
*/


$_load_controls_template=array('controls'=>$_load_controls["controls"],
                               'menus'   =>$_load_controls["menus"],
                               'langs'   =>$_load_controls["langs"],
                               'social'  =>$_load_controls["social"],
                               'privacy' =>$_load_controls["privacy"],
                               'login'   =>$_load_controls["login"],
);


return $_load_controls_template;