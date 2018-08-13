<?php
    session_start();
    session_destroy(); 
    ob_start();
    header('Location: '.'home.php');
    ob_end_flush();
    die();
