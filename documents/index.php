<?php

ob_start();
header('Location: '.'error.php');
ob_end_flush();

die();