<?php

    $dbhost = 'localhost'; // Unlikely to require changing
    $dbname = 'fetchnfts'; // Modify these...
    $dbuser = 'root'; // ...variables according
    $dbpass = ''; // ...to your installation
    $conn=new mysqli($dbhost, $dbuser,$dbpass, $dbname) or die('unable to connect: ');
?> 