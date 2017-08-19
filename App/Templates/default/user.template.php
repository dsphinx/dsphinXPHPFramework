<?php

    $_load_controls_template = include('template.php');

    $message = 'Γεια σου   '.Auth::get_username().' &nbsp;';

    $message .= '
     <div class="btn-group">

         <a class="btn btn-mini dropdown-toggle" href="#" data-toggle="dropdown">  Action    <span class="caret"></span>    </a>
     <ul class="dropdown-menu">

         <li><a href="login.php?Controlpanel">'. $_SESSION['Auth']['UserName'].' </a> </li>
         <li class="divider"> </li>

     </ul>
     </div>';

    $_load_controls_template["login"] = isset($_SESSION['Auth']) ? $message.'<a class="btn btn-mini" href="?page=signin&cmd=logout"><i class="icon-star"></i> logout</a>' : NULL;


    return $_load_controls_template;