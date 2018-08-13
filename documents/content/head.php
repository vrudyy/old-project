<?php
$string = htmlspecialchars(basename($_SERVER['REQUEST_URI']));
$title = ucfirst(substr($string, 0, strlen($string)-4)); 

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>i-Nucleus :: <?php echo $title ?></title>
    <link href="css/grid.css" rel="stylesheet" >
    <link href="css/defaults.css" rel="stylesheet" >
    <link href="css/main.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="css/components.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
</head>
