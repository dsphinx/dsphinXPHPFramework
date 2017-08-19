<?php

    // Template controls
    $_load_controls = array(
        "menus"    => NULL,
        "controls" => NULL,
        "langs"    => NULL,
        "privacy"  => NULL,
        "social"   => NULL,
        "login"   => NULL,
    );


    $_load_controls["login"] .=
        '
         <div class="btn-group "> … </div>
     <div class="btn-group">

         <a class="btn btn-mini dropdown-toggle" href="#" data-toggle="dropdown">  Action    <span class="caret"></span>    </a>
     <ul class="dropdown-menu">

         <li><a href="login.php?Controlpanel">'. $_SESSION['Auth']['UserName'].' </a> </li>
         <li class="divider"> </li>
         <li><a href="login.php?Administrator&cmd=admins"> Διαχειριστές </a>  </li>
         <li><a href="login.php?Administrator&cmd=users"> Χρήστες </a>  </li>
         <li class="divider"> </li>
         <li><a href="login.php?Administrator&cmd=logs"> Logs </a>  </li>

     </ul>
     </div>';


    $_load_controls["login"] .= '<a class="btn btn-mini" href="?page=signin&cmd=logout"><i class="icon-plus-sign"></i> Προσθήκη </a>';

    $_load_controls["login"] .= '<a class="btn btn-mini" href="?page=signin&cmd=logout"><i class="icon-user"></i> Έξοδος </a>';



    /**
     *
     *   Main Page Menus
     *
     */
    $_tmp = array(
        //   'Αρχική'      => 'main',
        'Αναζήτηση'   => 'search',
        'Όροι Χρήσης' => 'call_files',
        'Responsive ' => 'responsive',
        'Βοήθεια'     => 'html_BOOTSTRAP_examples',
        'Plugins'     => 'plugins',
    );


    $link = Controller::$_page['run'];

    while (list($key, $value) = each($_tmp)) {

        $_active = strcmp($link, $value) == 0 ? ' class="active" ' : NULL;
        $_load_controls["menus"] .= '<li ' . $_active . '><a  href="?page=' . $value . '" title="' . $key . '">' . $key . ' </a></li>';
    }


    /*
    $_langs = array(
        'English'  => 'cy-GB.gif',
        'Ελληνικά' => 'el-GR.gif',
    );


    while (list($key, $value) = each($_langs))
        $_load_controls["langs"] .= '<a   href="?page=lang&set=' . $key . '"><span class="Language selected" title="' . $key . '" style="background-image: url(Media/images/' . $value . ')"> &nbsp;  </span></a>';
    */


    $_load_controls_template = array(
        'controls' => $_load_controls["controls"],
        'menus'    => $_load_controls["menus"],
        'privacy'  => $_load_controls["privacy"],
        'login'    => $_load_controls["login"],
    );


    return $_load_controls_template;